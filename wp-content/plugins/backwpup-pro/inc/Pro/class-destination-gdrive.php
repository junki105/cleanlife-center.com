<?php
/**
 * @link https://github.com/google/google-api-php-client
 *
 * @uses google-api-php-client Version 1.1.7
 */

use Inpsyde\BackWPup\Pro\Restore;
use \Inpsyde\BackWPupShared\File\MimeTypeExtractor;

/**
 * Class BackWPup_Pro_Destination_GDrive
 *
 * @package BackWPup
 */
class BackWPup_Pro_Destination_GDrive extends BackWPup_Destinations {

	/**
	 * Folder Cache
	 *
	 * @var array
	 */
	private $gdrive_folders_cache = array();

	/**
	 * Google Drive Service
	 *
	 * @var $service \Google_Service_Drive
	 */
	private $service = null;

	/**
	 * Google Client
	 *
	 * @var Google_Client
	 */
	private $client;

	/**
	 * Folder ID
	 *
	 * @var string
	 */
	private $folder_id;

	/**
	 * Default options
	 *
	 * @return array The default options.
	 */
	public function option_defaults() {

		return array(
			'gdriverefreshtoken' => '',
			'gdrivemaxbackups'   => 15,
			'gdrivesyncnodelete' => true,
			'gdriveusetrash'     => true,
			'gdrivedir'          => trailingslashit( sanitize_file_name( get_bloginfo( 'name' ) ) ),
		);
	}

	/**
	 * Edit Tab
	 *
	 * @param int $jobid The job ID.
	 *
	 * @return void
	 */
	public function edit_tab( $jobid ) {

		$google_client_id     = get_site_option( BackWPup_Pro_Settings_APIKeys::OPTION_GOOGLE_CLIENT_ID );
		$google_client_secret = get_site_option( BackWPup_Pro_Settings_APIKeys::OPTION_GOOGLE_CLIENT_SECRET );

		if ( ! $google_client_id || ! $google_client_secret ) {
			BackWPup_Admin::message(
				sprintf(
					__(
						'Looks like you haven’t set up any API keys yet. Head over to <a href="%s">Settings | API-Keys</a> and get Google Drive all set up, then come back here.',
						'backwpup'
					),
					network_admin_url( 'admin.php' ) . '?page=backwpupsettings#backwpup-tab-apikey'
				),
				true
			);
		}

		//google authentication
		set_site_transient( 'backwpup_gdrive_jobid_' . get_current_user_id(), $jobid, HOUR_IN_SECONDS );
		$refresh_token = BackWPup_Encryption::decrypt( BackWPup_Option::get( $jobid, 'gdriverefreshtoken' ) );

		BackWPup_Admin::display_messages();
		?>

		<h3 class="title"><?php esc_html_e( 'Login', 'backwpup' ); ?></h3>
		<p></p>
		<table class="form-table">
			<tr>
				<th scope="row"><?php esc_html_e( 'Authenticate', 'backwpup' ); ?></th>
				<td><?php if ( empty( $refresh_token ) ) { ?>
						<span class="bwu-message-error"><?php esc_html_e( 'Not authenticated!', 'backwpup' ); ?></span><br/>
					<?php } else { ?>
						<span class="bwu-message-success"><?php esc_html_e( 'Authenticated!', 'backwpup' ); ?></span><br/>
					<?php } ?>
					<a class="button secondary"
					   href="<?php echo admin_url(
						   'admin-ajax.php'
					   ); ?>?action=backwpup_dest_gdrive"><?php esc_html_e( 'Reauthenticate', 'backwpup' ); ?></a>
				</td>
			</tr>
		</table>


		<h3 class="title"><?php esc_html_e( 'Backup settings', 'backwpup' ); ?></h3>
		<p></p>
		<table class="form-table">
			<tr>
				<th scope="row"><label
						for="idgdrivedir"><?php esc_html_e( 'Folder in Google Drive', 'backwpup' ) ?></label></th>
				<td>
					<input id="idgdrivedir" name="gdrivedir" type="text"
					       value="<?php echo esc_attr( BackWPup_Option::get( $jobid, 'gdrivedir' ) ); ?>"
					       class="regular-text"/>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'File Deletion', 'backwpup' ); ?></th>
				<td>
					<?php
					if ( BackWPup_Option::get( $jobid, 'backuptype' ) === 'archive' ) {
						?>
						<label for="idgdrivemaxbackups">
							<input id="idgdrivemaxbackups" name="gdrivemaxbackups" type="number" min="0" step="1"
							       value="<?php echo esc_attr( BackWPup_Option::get( $jobid, 'gdrivemaxbackups' ) ); ?>"
							       class="small-text"/>
							&nbsp;<?php _e( 'Number of files to keep in folder.', 'backwpup' ); ?>
						</label>
						<p><?php _e(
								'<strong>Warning</strong>: Files belonging to this job are now tracked. Old backup archives which are untracked will not be automatically deleted.',
								'backwpup'
							) ?></p>
					<?php } else { ?>
						<label for="idgdrivesyncnodelete">
							<input class="checkbox" value="1"
							       type="checkbox" <?php checked(
								BackWPup_Option::get( $jobid, 'gdrivemaxbackups' ),
								true
							); ?>
							       name="gdrivemaxbackups" id="idgdrivesyncnodelete"/>
							&nbsp;<?php _e( 'Do not delete files while syncing to destination!', 'backwpup' ); ?>
						</label>
					<?php } ?>
					<br/>&nbsp;<br/>
					<label for="idgdriveusetrash">
						<input
							class="checkbox"
							value="1"
							type="checkbox"
							<?php checked( BackWPup_Option::get( $jobid, 'gdriveusetrash' ), true ); ?>
							name="gdriveusetrash"
							id="idggdriveusetrash"/>
						<?php esc_html_e(
							'Consider using trash to delete files. If trash is not enabled, files will be deleted permanently.',
							'backwpup'
						); ?>
					</label>
				</td>
			</tr>
		</table>

		<?php
	}

	/**
	 * Authentication over ajax
	 */
	public function edit_ajax() {

		// on wizards
		$wiz_data_id = '';
		$wiz_data    = array();
		if ( ! empty( $_COOKIE['BackWPup_Wizard_ID'] ) ) {
			$wiz_data_id = $_COOKIE['BackWPup_Wizard_ID'];
		}
		if ( empty( $wiz_data_id ) && ! empty( $_POST['BackWPup_Wizard_ID'] ) ) {
			$wiz_data_id = $_POST['BackWPup_Wizard_ID'];
		}

		//start using sessions
		if ( ! empty( $wiz_data_id ) ) {
			$wiz_data = get_site_transient( 'BackWPup_Wiz_' . $wiz_data_id );
		}

		$client = new Google_Client();
		if ( BackWPup::get_plugin_data( 'cacert' ) ) {
			$client->getIo()
			       ->setOptions( array( CURLOPT_CAINFO => BackWPup::get_plugin_data( 'cacert' ) ) );
		} else {
			$client->getIo()
			       ->setOptions( array( CURLOPT_SSL_VERIFYPEER => false ) );
		}
		$client->setApplicationName( BackWPup::get_plugin_data( 'name' ) );
		$client->setClientId( get_site_option( BackWPup_Pro_Settings_APIKeys::OPTION_GOOGLE_CLIENT_ID ) );
		$client->setClientSecret(
			BackWPup_Encryption::decrypt( get_site_option( BackWPup_Pro_Settings_APIKeys::OPTION_GOOGLE_CLIENT_SECRET ) )
		);
		$client->setScopes( array( 'https://www.googleapis.com/auth/drive' ) );
		$client->setRedirectUri( admin_url( 'admin-ajax.php' ) . '?action=backwpup_dest_gdrive' );
		$client->setAccessType( 'offline' );

		if ( isset( $_GET['code'] ) ) {

			if ( ! empty( $wiz_data ) ) {
				try {
					$client->authenticate( $_GET['code'] );
					$access_token = $client->getAccessToken();
					$access_token = json_decode( $access_token );
					# print_r( $access_token ); Is this a debug leftover?
					if ( ! empty( $access_token->refresh_token ) ) {
						$wiz_data['job_settings']['gdriverefreshtoken'] = BackWPup_Encryption::encrypt(
							$access_token->refresh_token
						);
						BackWPup_Admin::message( __( 'GDrive: Authenticated.', 'backwpup' ) );
					} else {
						$wiz_data['job_settings']['gdriverefreshtoken'] = '';
						$client->revokeToken( $access_token->access_token );
						BackWPup_Admin::message(
							__( 'GDrive: No refresh token received. Try to Authenticate again!', 'backwpup' ),
							true
						);
					}
					set_site_transient( 'BackWPup_Wiz_' . $wiz_data_id, $wiz_data, HOUR_IN_SECONDS );
					$this->redirect(
						array(
							'page'               => 'backwpupwizard',
							'BackWPup_Wizard_ID' => $wiz_data_id,
							'step'               => 'DEST-GDRIVE',
						)
					);

				} catch ( Exception $e ) {
					BackWPup_Admin::message( sprintf( __( 'GDrive API: %s', 'backwpup' ), $e->getMessage() ), true );
					$this->redirect(
						array(
							'page'               => 'backwpupwizard',
							'BackWPup_Wizard_ID' => $wiz_data_id,
							'step'               => 'DEST-GDRIVE',
						)
					);
				}
			}

			// on edit job
			$jobid = get_site_transient( 'backwpup_gdrive_jobid_' . get_current_user_id() );
			if ( ! empty( $jobid ) ) {
				try {
					$client->authenticate( $_GET['code'] );
					$access_token = $client->getAccessToken();
					$access_token = json_decode( $access_token );
					if ( ! empty( $access_token->refresh_token ) ) {
						BackWPup_Option::update(
							$jobid,
							'gdriverefreshtoken',
							BackWPup_Encryption::encrypt( $access_token->refresh_token )
						);
						BackWPup_Admin::message( __( 'GDrive: Authenticated.', 'backwpup' ) );
					} else {
						$client->revokeToken( $access_token->access_token );
						BackWPup_Option::delete( $jobid, 'gdriverefreshtoken' );
						BackWPup_Admin::message(
							__( 'GDrive: No refresh token received. Try to Authenticate again!', 'backwpup' ),
							true
						);
					}
					$this->redirect(
						array( 'page' => 'backwpupeditjob', 'jobid' => $jobid, 'tab' => 'dest-gdrive' ),
						'',
						'edit-job'
					);
				} catch ( Exception $e ) {
					BackWPup_Admin::message( sprintf( __( 'GDrive API: %s', 'backwpup' ), $e->getMessage() ), true );
					BackWPup_Option::delete( $jobid, 'gdriverefreshtoken' );
					$this->redirect(
						array( 'page' => 'backwpupeditjob', 'jobid' => $jobid, 'tab' => 'dest-gdrive' ),
						'',
						'edit-job'
					);
				}
			}
		} else {

			if ( ! empty( $wiz_data ) ) {
				try {
					$refresh_token = BackWPup_Encryption::decrypt( $wiz_data['job_settings']['gdriverefreshtoken'] );
					if ( ! empty( $refresh_token ) ) {
						$client->refreshToken( $refresh_token );
						$client->revokeToken( $refresh_token );
						$wiz_data['job_settings']['gdriverefreshtoken'] = '';
						set_site_transient( 'BackWPup_Wiz_' . $wiz_data_id, $wiz_data, HOUR_IN_SECONDS );
					}
					$client->setRedirectUri( admin_url( 'admin-ajax.php' ) . '?action=backwpup_dest_gdrive' );
					$this->redirect( array(), $client->createAuthUrl() );
				} catch ( Exception $e ) {
					BackWPup_Admin::message( sprintf( __( 'GDrive API: %s', 'backwpup' ), $e->getMessage() ), true );
					$wiz_data['job_settings']['gdriverefreshtoken'] = '';
					set_site_transient( 'BackWPup_Wiz_' . $wiz_data_id, $wiz_data, HOUR_IN_SECONDS );
					$this->redirect(
						array(
							'page'               => 'backwpupwizard',
							'BackWPup_Wizard_ID' => $wiz_data_id,
							'step'               => 'DEST-GDRIVE',
						)
					);
				}
			}

			$jobid = get_site_transient( 'backwpup_gdrive_jobid_' . get_current_user_id() );
			if ( ! empty( $jobid ) ) {
				try {
					$refresh_token = BackWPup_Encryption::decrypt(
						BackWPup_Option::get( $jobid, 'gdriverefreshtoken' )
					);
					if ( ! empty( $refresh_token ) ) {
						$client->refreshToken( $refresh_token );
						$client->revokeToken( $refresh_token );
						BackWPup_Option::delete( $jobid, 'gdriverefreshtoken' );
					}
					$client->setRedirectUri( admin_url( 'admin-ajax.php' ) . '?action=backwpup_dest_gdrive' );
					$this->redirect( array(), $client->createAuthUrl() );
				} catch ( Exception $e ) {
					BackWPup_Admin::message( sprintf( __( 'GDrive API: %s', 'backwpup' ), $e->getMessage() ), true );
					BackWPup_Option::delete( $jobid, 'gdriverefreshtoken' );
					$this->redirect(
						array( 'page' => 'backwpupeditjob', 'jobid' => $jobid, 'tab' => 'dest-gdrive' ),
						'',
						'edit-job'
					);
				}
			}
		}
	}

	/**
	 * Update Settings
	 *
	 * @param int $jobid The job id to update.
	 *
	 * @return void
	 */
	public function edit_form_post_save( $jobid ) {

		$data = filter_input_array(
			INPUT_POST,
			array(
				'gdrivesyncnodelete' => FILTER_VALIDATE_BOOLEAN,
				'gdriveusetrash'     => FILTER_VALIDATE_BOOLEAN,
				'gdrivemaxbackups'   => FILTER_SANITIZE_NUMBER_INT,
				'gdrivedir'          => FILTER_SANITIZE_URL,
			)
		);

		BackWPup_Option::update( $jobid, 'gdrivesyncnodelete', (bool) $data['gdrivesyncnodelete'] );
		BackWPup_Option::update( $jobid, 'gdriveusetrash', (bool) $data['gdriveusetrash'] );
		BackWPup_Option::update( $jobid, 'gdrivemaxbackups', abs( (int) $data['gdrivemaxbackups'] ) );

		if ( ! $data['gdrivedir'] ) {
			return;
		}

		$gdrivedir = wp_normalize_path( $data['gdrivedir'] );

		if ( substr( $gdrivedir, 0, 1 ) !== '/' ) {
			$gdrivedir = '/' . $gdrivedir['gdrivedir'];
		}

		BackWPup_Option::update( $jobid, 'gdrivedir', $gdrivedir );
	}

	/**
	 * Wizard Page
	 *
	 * @param array $job_settings The job settings.
	 */
	public function wizard_page( array $job_settings ) {

		if ( ! get_site_option( BackWPup_Pro_Settings_APIKeys::OPTION_GOOGLE_CLIENT_ID )
		     || ! get_site_option(
				BackWPup_Pro_Settings_APIKeys::OPTION_GOOGLE_CLIENT_SECRET
			) ) {
			BackWPup_Admin::message(
				sprintf(
					__(
						'Looks like you haven’t set up any API keys yet. Head over to <a href="%s">Settings | API-Keys</a> and get Google Drive all set up, then come back here.',
						'backwpup'
					),
					network_admin_url( 'admin.php' ) . '?page=backwpupsettings#backwpup-tab-apikey'
				),
				true
			);
		}

		BackWPup_Admin::display_messages();
		?>
		<table class="form-table">
			<tr>
				<td>
					<fieldset>
						<?php
						//display if not automatized
						if ( empty( $job_settings['gdriverefreshtoken'] ) ) { ?>
							<strong><?php esc_html_e( 'Login:', 'backwpup' ); ?></strong>&nbsp;
							<span class="bwu-message-error"><?php esc_html_e( 'Not authenticated!', 'backwpup' ); ?></span>
							<a class="button secondary"
							   href="<?php echo admin_url(
								   'admin-ajax.php'
							   ); ?>?action=backwpup_dest_gdrive"><?php esc_html_e(
									'Authenticate',
									'backwpup'
								); ?></a>
						<?php } else { ?>
							<strong><?php esc_html_e( 'Login:', 'backwpup' ); ?></strong>&nbsp;
							<span class="bwu-message-success"><?php esc_html_e( 'Authenticated!', 'backwpup' ); ?></span>
							<a class="button secondary"
							   href="<?php echo admin_url(
								   'admin-ajax.php'
							   ); ?>?action=backwpup_dest_gdrive"><?php esc_html_e(
									'Reauthenticate',
									'backwpup'
								); ?></a>
							<br/>
							<br/>
							<strong><label
									for="idgdrivedir"><?php esc_html_e( 'Folder:', 'backwpup' ); ?></label></strong>
							<br/>
							<input name="gdrivedir" id="idgdrivedir" type="text"
							       value="<?php echo esc_attr( $job_settings['gdrivedir'] ); ?>"
							       class="user large-text"/><br/>
							<?php
							if ( $job_settings['backuptype'] === 'archive' ) { ?>
								<label for="idgdrivemaxbackups">
									<input
										name="gdrivemaxbackups"
										id="idgdrivemaxbackups"
										type="text"
										size="3"
										value="<?php echo esc_attr( $job_settings['gdrivemaxbackups'] ); ?>"
										class="small-text"
										title="<?php esc_attr_e(
											'Oldest files will be deleted first.',
											'backwpup'
										); ?>"
									/>
									<?php esc_html_e( 'Number of files to keep in folder.', 'backwpup' ); ?></label>
							<?php } else { ?>
								<label for="idgdrivesyncnodelete"><input class="checkbox" value="1"
								                                         type="checkbox" <?php checked( $job_settings['gdrivesyncnodelete'],
										true ); ?>
								                                         name="gdrivesyncnodelete"
								                                         id="idgdrivesyncnodelete"/> <?php esc_html_e(
										'Do not delete files while syncing to destination!',
										'backwpup'
									); ?>
								</label>
								<?php
							}
						} ?>
					</fieldset>
				</td>
			</tr>
		</table>
		<?php
	}

	/**
	 * Save Wizard info
	 *
	 * @param array $job_settings The job settings.
	 *
	 * @return array The filtered job settings
	 */
	public function wizard_save( array $job_settings ) {

		$data = filter_input_array(
			INPUT_POST,
			array(
				'gdrivedir'          => FILTER_SANITIZE_URL,
				'gdrivesyncnodelete' => FILTER_VALIDATE_BOOLEAN,
				'gdrivemaxbackups'   => FILTER_SANITIZE_NUMBER_INT,
			)
		);

		if ( isset( $data['gdrivedir'] ) ) {
			$gdrivedir = wp_normalize_path( $data['gdrivedir'] );
			if ( substr( $gdrivedir, 0, 1 ) !== '/' ) {
				$gdrivedir = '/' . $gdrivedir;
			}
			$job_settings['gdrivedir'] = $gdrivedir;
		}

		$job_settings['gdrivesyncnodelete'] = (bool) $data['gdrivesyncnodelete'];
		if ( isset( $data['gdrivemaxbackups'] ) ) {
			$job_settings['gdrivemaxbackups'] = abs( (int) $data['gdrivemaxbackups'] );
		}

		return $job_settings;
	}

	/**
	 * Delete File
	 *
	 * @param string $jobdest    Destination job.
	 * @param string $backupfile The file path to remove.
	 */
	public function file_delete( $jobdest, $backupfile ) {

		$files = get_site_transient( 'backwpup_' . strtolower( $jobdest ) );
		list( $jobid, $dest ) = explode( '_', $jobdest );

		if ( BackWPup_Option::get( $jobid, 'gdriverefreshtoken' ) ) {
			try {
				$this->get_gdrive( $jobid );
				if ( BackWPup_Option::get( $jobid, 'gdriveusetrash' ) ) {
					$this->service->files->trash( $backupfile );
				} else {
					$this->service->files->delete( $backupfile );
				}
				//update file list
				foreach ( $files as $key => $file ) {
					if ( is_array( $file ) && $file['file'] == $backupfile ) {
						unset( $files[ $key ] );
					}
				}
			} catch ( Exception $e ) {
				BackWPup_Admin::message( 'Google Drive: ' . $e->getMessage() );
			}
		}
		set_site_transient( 'backwpup_' . strtolower( $jobdest ), $files, YEAR_IN_SECONDS );
	}

	/**
	 * Files List
	 *
	 * @param string $jobdest The Job Destination
	 *
	 * @return array The files list containing extra informations such as jobid, action, page etc...
	 */
	public function file_get_list( $jobdest ) {

		// Retrieve the JOB ID.
		// N_DESTNAME.
		$jobid = intval( substr( $jobdest, 0, strpos( $jobdest, '_', 1 ) ) );

		if ( ! $jobid ) {
			return array();
		}

		$registry = Restore\Functions\restore_registry();
		$files    = (array) get_site_transient( 'backwpup_' . strtolower( $jobdest ) );
		$files    = array_filter( $files );

		// Include the Restore link.
		foreach ( $files as $index => &$file ) {
			$file['restoreurl'] = add_query_arg(
				array(
					'page'         => 'backwpuprestore',
					'action'       => 'restore-destination_gdrive',
					'file'         => $files[ $index ]['file'],
					'restore_file' => $registry->uploads_folder . '/' . basename( $files[ $index ]['filename'] ),
					'jobid'        => $jobid,
					'service'      => 'Gdrive',
				),
				network_admin_url( 'admin.php' )
			);
		}

		return $files;
	}

	/**
	 * File Update List
	 *
	 * Update the list of files in the transient.
	 *
	 * @param BackWPup_Job|int $job    Either the job object or job ID
	 * @param bool             $delete Whether to delete old backups.
	 */
	public function file_update_list( $job, $delete = false ) {

		if ( $job instanceof BackWPup_Job ) {
			$job_object = $job;
			$jobid      = $job->job['jobid'];
		} else {
			$job_object = null;
			$jobid      = $job;
		}

		$backupfilelist = array();
		$filecounter    = 0;
		$files          = array();
		$this->get_gdrive( $jobid );
		$metadata = $this->search_files(
			"'" . $this->folder_id . "' in parents" . ( $job_object ? " and mimeType = '" . MimeTypeExtractor::fromFilePath(
					$job_object->backup_folder . $job_object->backup_file
				) . "' " : '' ),
			$this->service
		);
		if ( is_array( $metadata ) ) {
			foreach ( $metadata as $data ) {
				$file = $data->title;
				if ( $this->is_backup_archive( $file )
				     && $this->is_backup_owned_by_job(
						$file,
						$jobid
					) == true ) {
					$backupfilelist[ strtotime( $data->modifiedDate ) ] = $data->id;
				}
				$files[ $filecounter ]['folder']      = BackWPup_Option::get( $jobid, 'gdrivedir' );
				$files[ $filecounter ]['file']        = $data->id;
				$files[ $filecounter ]['filename']    = $data->title;
				$files[ $filecounter ]['downloadurl'] = network_admin_url(
					'admin.php?page=backwpupbackups&action=downloadgdrive&file=' . $data->id . '&local_file=' . $data->title . '&jobid=' . $jobid
				);
				$files[ $filecounter ]['filesize']    = $data->fileSize;
				$files[ $filecounter ]['time']        = strtotime( $data->modifiedDate ) + ( get_option(
					                                                                             'gmt_offset'
				                                                                             ) * 3600 );
				$filecounter ++;
			}
		}
		if ( $delete && $job_object && $job_object->job['gdrivemaxbackups'] > 0 ) { //Delete old backups
			if ( count( $backupfilelist ) > $job_object->job['gdrivemaxbackups'] ) {
				ksort( $backupfilelist );
				$numdeltefiles = 0;
				while ( $file = array_shift( $backupfilelist ) ) {
					if ( count( $backupfilelist ) < $job_object->job['gdrivemaxbackups'] ) {
						break;
					}
					//delete files on Cloud
					if ( $job_object->job['gdriveusetrash'] ) {
						$this->service->files->trash( $file );
					} else {
						$this->service->files->delete( $file );
					}
					foreach ( $files as $key => $filedata ) {
						if ( $filedata['file'] == $file ) {
							unset( $files[ $key ] );
						}
					}
					$numdeltefiles ++;
				}
				if ( $numdeltefiles > 0 ) {
					$job_object->log(
						sprintf(
							_n(
								'One file deleted from Google Drive',
								'%d files deleted on Google Drive',
								$numdeltefiles,
								'backwpup'
							),
							$numdeltefiles
						),
						E_USER_NOTICE
					);
				}
			}
		}
		set_site_transient( 'backwpup_' . $jobid . '_gdrive', $files, YEAR_IN_SECONDS );
	}

	/**
	 * @param $job_object
	 *
	 * @return bool
	 */
	public function job_run_archive( BackWPup_Job $job_object ) {

		$job_object->substeps_todo = 2 + $job_object->backup_filesize;
		if ( $job_object->steps_data[ $job_object->step_working ]['SAVE_STEP_TRY'] != $job_object->steps_data[ $job_object->step_working ]['STEP_TRY'] ) {
			$job_object->log(
				sprintf(
					__( '%d. Try to send backup file to Google Drive&#160;&hellip;', 'backwpup' ),
					$job_object->steps_data[ $job_object->step_working ]['STEP_TRY']
				),
				E_USER_NOTICE
			);
		}

		try {
			$this->get_gdrive( $job_object->job['jobid'] );

			//get the folder id and create folder
			if ( empty( $job_object->steps_data[ $job_object->step_working ]['folder_id'] ) ) {
				$job_object->steps_data[ $job_object->step_working ]['folder_id'] = $this->folder_id;
			}

			// put the file
			if ( $job_object->steps_data[ $job_object->step_working ]['SAVE_STEP_TRY'] != $job_object->steps_data[ $job_object->step_working ]['STEP_TRY'] && $job_object->substeps_done < $job_object->backup_filesize ) {
				$job_object->log( __( 'Uploading to Google Drive&#160;&hellip;', 'backwpup' ) );
			}

			if ( $job_object->substeps_done < $job_object->backup_filesize ) {
				// Get resumable session url
				if ( empty ( $job_object->steps_data[ $job_object->step_working ]['resumable_uri'] ) ) {
					$post_data           = new stdClass();
					$post_data->title    = $job_object->backup_file;
					$post_data->mimeType = MimeTypeExtractor::fromFilePath(
						$job_object->backup_folder . $job_object->backup_file
					);

					if ( $job_object->steps_data[ $job_object->step_working ]['folder_id'] !== 'root' ) {
						$post_data_parent       = new stdClass();
						$post_data_parent->kind = 'drive#fileLink';
						$post_data_parent->id   = $job_object->steps_data[ $job_object->step_working ]['folder_id'];
						$post_data->parents     = array( $post_data_parent );
					}

					$post_fields = json_encode( $post_data );

					$ch = curl_init();
					curl_setopt(
						$ch,
						CURLOPT_URL,
						'https://www.googleapis.com/upload/drive/v2/files?uploadType=resumable'
					);
					curl_setopt( $ch, CURLOPT_POST, true );
					curl_setopt( $ch, CURLOPT_POSTFIELDS, $post_fields );
					curl_setopt( $ch, CURLOPT_HEADER, true );
					curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
					if ( BackWPup::get_plugin_data( 'cacert' ) ) {
						curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, true );
						curl_setopt( $ch, CURLOPT_CAINFO, BackWPup::get_plugin_data( 'cacert' ) );
						curl_setopt( $ch, CURLOPT_CAPATH, dirname( BackWPup::get_plugin_data( 'cacert' ) ) );
					} else {
						curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
					}
					$access_token = json_decode( $this->client->getAccessToken() );
					curl_setopt(
						$ch,
						CURLOPT_HTTPHEADER,
						array(
							'Authorization: Bearer ' . $access_token->access_token,
							'Content-Length: ' . strlen( $post_fields ),
							'X-Upload-Content-Type: ' . MimeTypeExtractor::fromFilePath(
								$job_object->backup_folder . $job_object->backup_file
							),
							'X-Upload-Content-Length: ' . $job_object->backup_filesize,
							'Content-Type: application/json; charset=UTF-8',
						)
					);

					$response    = curl_exec( $ch );
					$curlgetinfo = curl_getinfo( $ch );
					curl_close( $ch );

					if ( $curlgetinfo['http_code'] == 200 || $curlgetinfo['http_code'] == 201 ) {
						if ( preg_match( '/Location:(.*?)\r/i', $response, $matches ) ) {
							$job_object->steps_data[ $job_object->step_working ]['resumable_uri'] = trim(
								$matches[1]
							);
						}
					}

					// error checking
					if ( empty( $job_object->steps_data[ $job_object->step_working ]['resumable_uri'] ) ) {
						$job_object->log(
							__( 'Could not create resumable file transfer to Google Drive', 'backwpup' ),
							E_USER_ERROR
						);

						return false;

					}
				} else {
					//get actual position
					$ch = curl_init();
					curl_setopt(
						$ch,
						CURLOPT_URL,
						$job_object->steps_data[ $job_object->step_working ]['resumable_uri']
					);
					curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'PUT' );
					curl_setopt( $ch, CURLOPT_HEADER, true );
					curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
					if ( BackWPup::get_plugin_data( 'cacert' ) ) {
						curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, true );
						curl_setopt( $ch, CURLOPT_CAINFO, BackWPup::get_plugin_data( 'cacert' ) );
						curl_setopt( $ch, CURLOPT_CAPATH, dirname( BackWPup::get_plugin_data( 'cacert' ) ) );
					} else {
						curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
					}
					$access_token = json_decode( $this->client->getAccessToken() );
					curl_setopt(
						$ch,
						CURLOPT_HTTPHEADER,
						array(
							'Authorization: Bearer ' . $access_token->access_token,
							'Content-Length: 0',
							'Content-Range: bytes */' . $job_object->backup_filesize,
						)
					);
					$response    = curl_exec( $ch );
					$curlgetinfo = curl_getinfo( $ch );
					curl_close( $ch );
					if ( $curlgetinfo['http_code'] == 308
					     && preg_match(
						     '/Range:(.*?)\r/i',
						     $response,
						     $matches
					     )
					) {
						$range  = trim( $matches[1] );
						$ranges = explode( '-', $range );

						$job_object->substeps_done = $ranges[1] + 1;
					} else {
						$job_object->log(
							__( 'Can not resume transfer backup to Google Drive!', 'backwpup' ),
							E_USER_ERROR
						);

						return false;
					}
				}

				//Upload in chunks
				$chunk_size   = 4194304; //4194304 = 4MB
				$created_file = null;
				if ( $file_handel = fopen( $job_object->backup_folder . $job_object->backup_file, 'rb' ) ) {
					//seek to file pos
					if ( ! empty( $job_object->substeps_done ) ) {
						fseek( $file_handel, $job_object->substeps_done );
					}

					while ( $data_chunk = fread( $file_handel, $chunk_size ) ) {

						$chunk_upload_start = microtime( true );

						$ch = curl_init();
						curl_setopt(
							$ch,
							CURLOPT_URL,
							$job_object->steps_data[ $job_object->step_working ]['resumable_uri']
						);
						curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'PUT' );
						curl_setopt( $ch, CURLOPT_POSTFIELDS, $data_chunk );
						curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
						if ( BackWPup::get_plugin_data( 'cacert' ) ) {
							curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, true );
							curl_setopt( $ch, CURLOPT_CAINFO, BackWPup::get_plugin_data( 'cacert' ) );
							curl_setopt( $ch, CURLOPT_CAPATH, dirname( BackWPup::get_plugin_data( 'cacert' ) ) );
						} else {
							curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
						}
						$access_token = json_decode( $this->client->getAccessToken() );
						$end_pos      = $job_object->substeps_done + strlen( $data_chunk ) - 1;
						curl_setopt(
							$ch,
							CURLOPT_HTTPHEADER,
							array(
								'Authorization: Bearer' . $access_token->access_token,
								'Content-Length: ' . strlen( $data_chunk ),
								'Content-Range: bytes ' . $job_object->substeps_done . '-' . $end_pos . '/' . $job_object->backup_filesize,
							)
						);
						$response    = curl_exec( $ch );
						$curlgetinfo = curl_getinfo( $ch );
						curl_close( $ch );
						$chunk_upload_time = microtime( true ) - $chunk_upload_start;
						if ( $curlgetinfo['http_code'] == 200 || $curlgetinfo['http_code'] == 201 || $curlgetinfo['http_code'] == 308 ) {
							$created_file              = json_decode( $response );
							$job_object->substeps_done = $end_pos + 1;
							if ( $curlgetinfo['http_code'] == 308 ) {
								$time_remaining = $job_object->do_restart_time();
								//calc next chunk
								if ( $time_remaining < $chunk_upload_time ) {
									$chunk_size = floor( $chunk_size / $chunk_upload_time * ( $time_remaining - 3 ) );
									if ( $chunk_size < 0 ) {
										$chunk_size = 1024;
									}
									if ( $chunk_size > 4194304 ) {
										$chunk_size = 4194304;
									}
								}
							}
							$job_object->update_working_data();
						} else {
							$job_object->log(
								sprintf(
									__( 'Error transfering file chunks to %s.', 'backwpup' ),
									__( 'Google Drive', 'backwpup' )
								),
								E_USER_WARNING
							);

							return false;
						}
					}

				}
				fclose( $file_handel );
			} else {
				$job_object->log( __( 'Can not open source file for transfer.', 'backwpup' ), E_USER_ERROR );

				return false;
			}

			if ( is_object(
				     $created_file
			     )
			     && isset( $created_file->id )
			     && $created_file->fileSize == $job_object->backup_filesize ) {
				if ( ! empty( $job_object->job['jobid'] ) ) {
					BackWPup_Option::update(
						$job_object->job['jobid'],
						'lastbackupdownloadurl',
						str_replace( '&gd=true', '', $created_file->downloadUrl )
					);
				}
				$job_object->substeps_done = 1 + $job_object->backup_filesize;
				$job_object->log(
					sprintf( __( 'Backup transferred to %s', 'backwpup' ), $created_file->alternateLink ),
					E_USER_NOTICE
				);
			} else {
				if ( $created_file->fileSize != $job_object->backup_filesize ) {
					$job_object->log(
						__( 'Uploaded file size and local file size don\'t match.', 'backwpup' ),
						E_USER_ERROR
					);
				} else {
					$job_object->log(
						sprintf(
							__( 'Error transfering backup to %s.', 'backwpup' ),
							__( 'Google Drive', 'backwpup' )
						),
						E_USER_ERROR
					);
				}

				return false;
			}

			$this->file_update_list( $job_object, true );
		} catch ( Exception $e ) {
			$job_object->log(
				E_USER_ERROR,
				sprintf( __( 'Google Drive API: %s', 'backwpup' ), $e->getMessage() ),
				$e->getFile(),
				$e->getLine()
			);

			return false;
		}
		$job_object->substeps_done ++;

		return true;
	}

	/**
	 * Returns folder id of path and creates it if it not exists
	 *
	 * @param $path
	 *
	 * @return string
	 */
	private function get_folder_id( $path ) {

		$folder_id = 'root';
		if ( $path != '/' ) {
			$current_path = '';
			$folder_names = explode( '/', trim( $path, '/' ) );
			foreach ( $folder_names as $folder_name ) {
				$create_folder = true;
				$parent_path   = $current_path;
				$current_path  .= '/' . $folder_name;
				if ( isset( $this->gdrive_folders_cache[ $current_path ] ) ) {
					$folder_id = $this->gdrive_folders_cache[ $current_path ];
					continue;
				}
				$g_sub_folders = $this->search_files(
					"'$folder_id' in parents and mimeType = 'application/vnd.google-apps.folder'"
				);
				foreach ( $g_sub_folders AS $g_sub_folder ) {
					$this->gdrive_folders_cache[ $parent_path . '/' . (string) $g_sub_folder->title ] = $g_sub_folder->id;
					if ( (string) $g_sub_folder->title === $folder_name ) {
						$folder_id     = $g_sub_folder->id;
						$create_folder = false;
					}
				}
				//create not existing folder
				if ( $create_folder ) {
					$file = new Google_Service_Drive_DriveFile();
					$file->setTitle( $folder_name );
					$file->setMimeType( 'application/vnd.google-apps.folder' );
					if ( $folder_id != 'root' ) {
						$parent_reference = new Google_Service_Drive_ParentReference();
						$parent_reference->setId( $folder_id );
						$file->setParents( array( $parent_reference ) );
					}
					$created_folder                              = $this->service->files->insert(
						$file,
						array( 'mimeType' => 'application/vnd.google-apps.folder' )
					);
					$folder_id                                   = $created_folder->id;
					$this->gdrive_folders_cache[ $current_path ] = $created_folder->id;
				}
			}
		}

		return $folder_id;
	}

	/**
	 * Search Files
	 *
	 * @link https://developers.google.com/drive/search-parameters
	 *
	 * @param string $query The query search string.
	 *
	 * @return array The results list
	 */
	private function search_files( $query ) {

		$result    = array();
		$pageToken = null;
		//exclude trashed from query
		if ( ! empty( $query ) ) {
			$query .= ' and trashed != true';
		} else {
			$query = 'trashed != true';
		}

		do {
			$parameters = array( 'q' => $query, 'maxResults' => 1000 );
			if ( $pageToken ) {
				$parameters['pageToken'] = $pageToken;
			}
			$files     = $this->service->files->listFiles( $parameters );
			$result    = array_merge( $result, $files->items );
			$pageToken = ! empty( $files->getNextPageToken ) ? $files->getNextPageToken : '';
		} while ( $pageToken );

		return $result;
	}

	/**
	 * @param $job_settings
	 *
	 * @return bool
	 */
	public function can_run( array $job_settings ) {

		if ( ! empty( $job_settings['gdriveaccesstoken'] ) ) {
			$access_token = BackWPup_Encryption::decrypt( $job_settings['gdriveaccesstoken'] );
			$access_token = json_decode( $access_token );
			if ( $access_token->refresh_token ) {
				BackWPup_Option::update(
					$job_settings['jobid'],
					'gdriverefreshtoken',
					BackWPup_Encryption::encrypt( $access_token->refresh_token )
				);
				$job_settings['gdriverefreshtoken'] = BackWPup_Encryption::encrypt( $access_token->refresh_token );
			}
			BackWPup_Option::delete( $job_settings['jobid'], 'gdriveaccesstoken' );
		}

		if ( empty( $job_settings['gdriverefreshtoken'] ) ) {
			return false;
		}

		return true;
	}

	public function can_sync() {

		return true;
	}

	public function job_run_sync( BackWPup_Job $job_object ) {

		$job_object->substeps_todo = $job_object->count_folder + count( $job_object->additional_files_to_backup );
		$job_object->substeps_done = 0;
		if ( $job_object->steps_data[ $job_object->step_working ]['SAVE_STEP_TRY'] != $job_object->steps_data[ $job_object->step_working ]['STEP_TRY'] ) {
			$job_object->log(
				sprintf(
					__( '%d. Try to sync files to Google Drive&#160;&hellip;', 'backwpup' ),
					$job_object->steps_data[ $job_object->step_working ]['STEP_TRY']
				),
				E_USER_NOTICE
			);
		}

		try {
			$this->get_gdrive( $job_object->job['jobid'] );

			$backup_root_folder_id = $this->folder_id;

			//Sync files
			if ( $job_object->steps_data[ $job_object->step_working ]['SAVE_STEP_TRY'] != $job_object->steps_data[ $job_object->step_working ]['STEP_TRY'] ) {
				$job_object->log( __( 'Syncing changed files to Google Drive', 'backwpup' ) );
			}

			foreach ( $job_object->get_folders_to_backup() as $folder_to_backup_key => $folder_to_backup ) {
				//generate dest folder name
				$dest_folder_name = $job_object->job['gdrivedir'] . '/' . trim(
						$job_object->get_destination_path_replacement( $folder_to_backup ),
						'/'
					);
				//get google folder id
				$folder_id = $this->get_folder_id( $dest_folder_name );
				//jump over if not the actual folder
				if ( ! empty( $job_object->steps_data[ $job_object->step_working ]['on_folder_id'] ) && $job_object->steps_data[ $job_object->step_working ]['on_folder_id'] > $folder_to_backup_key ) {
					$job_object->substeps_done ++;
					continue;
				}
				//get files in folder
				$gdrive_files = $this->search_files(
					"'" . $folder_id . "' in parents and mimeType != 'application/vnd.google-apps.folder'"
				);
				// get local files
				$files_in_folder = $job_object->get_files_in_folder( $folder_to_backup );
				foreach ( $files_in_folder as $file_in_folder ) {
					$dest_file_name = $job_object->job['gdrivedir'] . '/' . trim(
							$job_object->get_destination_path_replacement( $file_in_folder ),
							'/'
						);
					foreach ( $gdrive_files as $gdrive_file_key => $gdrive_file ) {
						//file exists on gdrive
						if ( (string) $gdrive_file->title === basename( $file_in_folder ) ) {
							//Upload file again if filesize not the same
							if ( (int) $gdrive_file->fileSize !== filesize( $file_in_folder ) ) {
								$this->service->files->update(
									$gdrive_file->id,
									$gdrive_file,
									array(
										'data'       => file_get_contents( $file_in_folder ),
										'convert'    => false,
										'uploadType' => 'multipart',
										'mimeType'   => MimeTypeExtractor::fromFilePath( $file_in_folder ),
									)
								);
								$job_object->log(
									sprintf( __( 'File %s updated on Google Drive', 'backwpup' ), $dest_file_name )
								);
								$job_object->do_restart_time();
							}
							//remove found file from array
							unset( $gdrive_files[ $gdrive_file_key ] );
							continue 2;
						}
					}
					// if file not on gdrive upload it
					$file = new Google_Service_Drive_DriveFile();
					$file->setTitle( basename( $file_in_folder ) );
					$file->setMimeType( MimeTypeExtractor::fromFilePath( $file_in_folder ) );
					$parent = new Google_Service_Drive_ParentReference();
					$parent->setId( $folder_id );
					$file->setParents( array( $parent ) );
					$this->service->files->insert(
						$file,
						array(
							'data'       => file_get_contents( $file_in_folder ),
							'convert'    => false,
							'uploadType' => 'multipart',
							'mimeType'   => MimeTypeExtractor::fromFilePath( $file_in_folder ),
						)
					);
					$job_object->log(
						sprintf( __( 'File %s uploaded to Google Drive', 'backwpup' ), $dest_file_name )
					);
					$job_object->do_restart_time();
				}
				//remove extra files from file list so that the file can updated and will not deleted
				if ( $backup_root_folder_id === $folder_id && ! empty( $job_object->additional_files_to_backup ) ) {
					foreach ( $job_object->additional_files_to_backup as $additional_file ) {
						foreach ( $gdrive_files as $gdrive_file_key => $gdrive_file ) {
							if ( (string) $gdrive_file->title === basename( $additional_file ) ) {
								unset( $gdrive_files[ $gdrive_file_key ] );
							}
						}
					}
				}
				//delete files/folder that not longer exists
				if ( ! $job_object->job['gdrivesyncnodelete'] ) {
					foreach ( $gdrive_files as $gdrive_file ) {
						if ( empty( $gdrive_file->id ) ) {
							continue;
						}
						if ( $job_object->job['gdriveusetrash'] ) {
							$this->service->files->trash( $gdrive_file->id );
							$job_object->log(
								sprintf(
									__( 'File %s moved to trash in Google Drive', 'backwpup' ),
									$dest_folder_name . $gdrive_file->title
								)
							);
						} else {
							$this->service->files->delete( $gdrive_file->id );
							$job_object->log(
								sprintf(
									__( 'File %s deleted permanently in Google Drive', 'backwpup' ),
									$dest_folder_name . $gdrive_file->title
								)
							);
						}
						$job_object->do_restart_time();
					}
					//delete folder not work with special WP_* phates
					//$gdrive_folders = $this->search_files( "'". $folder_id ."' in parents and mimeType = 'application/vnd.google-apps.folder'" );
					//foreach( $gdrive_folders as $gdrive_folder ) {
					//	$folder_dir = trailingslashit( $folder_to_backup ) . (string) $gdrive_folder->title;
					//	$job_object->log( $folder_dir );
					//	if ( is_dir( $folder_dir ) ) {
					//		continue;
					//	}
					//	if ( $job_object->job[ 'gdriveusetrash' ] ) {
					//		$this->service->files->trash( $gdrive_folder->id );
					//		$job_object->log( sprintf( __( 'Folder %s moved to trash in Google Drive', 'backwpup' ), $dest_folder_name . $gdrive_folder->title ) );
					//	} else {
					//		$this->service->files->delete( $gdrive_folder->id );
					//		$job_object->log( sprintf( __( 'Folder %s deleted permanently in Google Drive', 'backwpup' ), $dest_folder_name . $gdrive_folder->title ) );
					//	}
					//}
				}
				$job_object->steps_data[ $job_object->step_working ]['on_folder_id'] = $folder_to_backup_key;
				$job_object->substeps_done ++;
				$job_object->do_restart_time();
				$job_object->update_working_data();
			}

			//sync extra files
			if ( empty( $job_object->steps_data[ $job_object->step_working ]['on_file'] ) ) {
				$job_object->steps_data[ $job_object->step_working ]['on_file'] = 0;
			}
			if ( ! empty( $job_object->additional_files_to_backup ) ) {
				$gdrive_files = $this->search_files(
					"'$backup_root_folder_id' in parents and mimeType != 'application/vnd.google-apps.folder'"
				);
				for (
					$i = $job_object->steps_data[ $job_object->step_working ]['on_file']; $i < count(
					$job_object->additional_files_to_backup
				); $i ++
				) {
					$additional_file = $job_object->additional_files_to_backup[ $i ];
					foreach ( $gdrive_files as $gdrive_file ) {
						//file exists on gdrive
						if ( (string) $gdrive_file->title === basename( $additional_file ) ) {
							//Update exciting file
							$responce = $this->service->files->update(
								$gdrive_file->id,
								$gdrive_file,
								array(
									'data'       => file_get_contents( $additional_file ),
									'convert'    => false,
									'uploadType' => 'multipart',
									'mimeType'   => MimeTypeExtractor::fromFilePath( $additional_file ),
								)
							);
							if ( $responce->fileSize == filesize( $additional_file ) ) {
								$job_object->log(
									sprintf(
										__( 'Extra file %s updated on Google Drive', 'backwpup' ),
										$job_object->job['gdrivedir'] . '/' . basename( $additional_file )
									)
								);
							}
							$job_object->substeps_done ++;
							$job_object->steps_data[ $job_object->step_working ]['on_file'] = $i + 1;
							$job_object->do_restart_time();
							$job_object->update_working_data();
							continue 2;
						}
					}
					$file = new Google_Service_Drive_DriveFile();
					$file->setTitle( basename( $additional_file ) );
					$file->setMimeType( MimeTypeExtractor::fromFilePath( $additional_file ) );
					$parent = new Google_Service_Drive_ParentReference();
					$parent->setId( $backup_root_folder_id );
					$file->setParents( array( $parent ) );
					$responce = $this->service->files->insert(
						$file,
						array(
							'data'       => file_get_contents( $additional_file ),
							'convert'    => false,
							'uploadType' => 'multipart',
							'mimeType'   => MimeTypeExtractor::fromFilePath( $additional_file ),
						)
					);
					if ( $responce->fileSize == filesize( $additional_file ) ) {
						$job_object->log(
							sprintf(
								__( 'Extra file %s uploaded to Google Drive', 'backwpup' ),
								$job_object->job['gdrivedir'] . '/' . basename( $additional_file )
							)
						);
					}
					$job_object->substeps_done ++;
					$job_object->steps_data[ $job_object->step_working ]['on_file'] = $i + 1;
					$job_object->do_restart_time();
					$job_object->update_working_data();
				}
			}

		} catch ( Exception $e ) {
			$job_object->log(
				E_USER_ERROR,
				sprintf( __( 'Google Drive API: %s', 'backwpup' ), $e->getMessage() ),
				$e->getFile(),
				$e->getLine()
			);

			return false;
		}

		return true;
	}

	/**
	 * Redirect to a given URL and then exit. Passed data array is added as URL query string.
	 *
	 * If not URL is given fallbacks to network admin URL.
	 * If a nonce action is passed, a nonce is generated and passed as query string.
	 *
	 * @param array  $data
	 * @param string $url
	 * @param string $nonce_action
	 */
	public function redirect( array $data = array(), $url = '', $nonce_action = '' ) {

		$safe = false;
		if ( ! $url ) {
			$safe = true;
			$url  = network_admin_url( 'admin.php' );
		}

		$url = filter_var( $url, FILTER_SANITIZE_URL );

		if ( $nonce_action ) {
			$data['_wpnonce'] = wp_create_nonce( $nonce_action );
		}

		if ( $data ) {
			$url = add_query_arg( $data, $url );
		}

		if ( $safe ) {
			wp_safe_redirect( $url, 302 );
		} else {
			wp_redirect( $url, 302 );
		}

		exit();
	}

	protected function get_gdrive( $jobid ) {

		$client = new Google_Client();
		if ( BackWPup::get_plugin_data( 'cacert' ) ) {
			$client->getIo()
			       ->setOptions( array( CURLOPT_CAINFO => BackWPup::get_plugin_data( 'cacert' ) ) );
		} else {
			$client->getIo()
			       ->setOptions( array( CURLOPT_SSL_VERIFYPEER => false ) );
		}
		$client->setApplicationName( BackWPup::get_plugin_data( 'name' ) );
		$client->setClientId( get_site_option( BackWPup_Pro_Settings_APIKeys::OPTION_GOOGLE_CLIENT_ID ) );
		$client->setClientSecret(
			BackWPup_Encryption::decrypt( get_site_option( BackWPup_Pro_Settings_APIKeys::OPTION_GOOGLE_CLIENT_SECRET ) )
		);
		$client->setScopes( array( 'https://www.googleapis.com/auth/drive' ) );
		$client->setAccessType( 'offline' );
		$refresh_token = BackWPup_Encryption::decrypt( BackWPup_Option::get( $jobid, 'gdriverefreshtoken' ) );
		$client->refreshToken( $refresh_token );
		$this->service = new Google_Service_Drive( $client );
		$this->client  = $client;

		$this->folder_id = $this->get_folder_id(
			BackWPup_Option::get( $jobid, 'gdrivedir' )
		);
	}
}
