<?php
/**
 * Dropbox Destination
 *
 * Documentation: https://www.dropbox.com/developers/reference/api
 */

use Inpsyde\BackWPup\Pro\Restore;

/**
 * Class BackWPup_Pro_Destination_Dropbox
 *
 * @package \Inpsyde\BackWPup
 */
class BackWPup_Pro_Destination_Dropbox extends BackWPup_Destination_Dropbox {

	/**
	 * Wizard Page
	 *
	 * @throws BackWPup_Destination_Dropbox_API_Exception If Api instance cannot be created.
	 *
	 * @param array $job_settings
	 *
	 * @return void
	 */
	public function wizard_page( array $job_settings ) {

		$dropbox          = new BackWPup_Destination_Dropbox_API( 'sandbox' );
		$sandbox_auth_url = $dropbox->oAuthAuthorize();

		// Display if not automatized.
		?>
		<table class="form-table">
			<tr>
				<td>
					<fieldset>
						<?php
						if ( empty( $job_settings['dropboxtoken'] ) ) {
							?>
							<label for="sandbox_code_id"><strong><?php esc_html_e(
										'Auth Code:',
										'backwpup'
									); ?></strong>&nbsp;
								<input name="sandbox_code" id="sandbox_code_id" type="text" value="" class="code large-text" /></label>
							<br />
							&nbsp;<br />
							<a class="button secondary" href="<?php echo esc_url(
								$sandbox_auth_url
							); ?>" target="_blank"><?php esc_html_e( 'Get auth code', 'backwpup' ); ?></a><br />
							&nbsp;<br />
							<a href="http://db.tt/8irM1vQ0"><?php esc_html_e( 'Create Account', 'backwpup' ); ?></a>
							<br />
							<?php
						} else {
							?>
							<strong><?php esc_html_e( 'Login:', 'backwpup' ); ?></strong>&nbsp;
							<span class="bwu-message-success"><?php esc_html_e( 'Authenticated!', 'backwpup' ); ?></span>
							<br />
						<?php } ?>
						<label for="iddropboxdir"><strong><?php esc_html_e( 'Folder:', 'backwpup' ); ?></strong><br />
							<input name="dropboxdir" id="iddropboxdir" type="text" value="<?php echo esc_attr(
								$job_settings['dropboxdir']
							); ?>" class="user large-text" /></label><br />
						<?php
						if ( $job_settings['backuptype'] == 'archive' ) { ?>
							<label for="iddropboxmaxbackups">
								<input
									name="dropboxmaxbackups"
									id="iddropboxmaxbackups"
									type="number"
									min="0"
									step="1"
									value="<?php echo esc_attr( $job_settings['dropboxmaxbackups'] ); ?>"
									class="small-text"
								/>
								<?php esc_html_e( 'Number of files to keep in folder.', 'backwpup' ); ?>
							</label>
						<?php } else { ?>
							<label for="iddropboxsyncnodelete"><input class="checkbox" value="1" type="checkbox" <?php checked(
									$job_settings['dropboxsyncnodelete'],
									true
								); ?> name="dropboxsyncnodelete" id="iddropboxsyncnodelete" /> <?php esc_html_e(
									'Do not delete files while syncing to destination!',
									'backwpup'
								); ?></label>
							<?php
						}
						?>
					</fieldset>
				</td>
			</tr>
		</table>
		<?php

	}

	/**
	 * Wizard Save
	 *
	 * @param array $job_settings
	 *
	 * @return mixed
	 */
	public function wizard_save( array $job_settings ) {

		$job_settings['dropboxroot'] = 'sandbox';
		if ( ! empty( $_POST['sandbox_code'] ) ) {
			try {
				$dropbox                      = new BackWPup_Destination_Dropbox_API( 'sandbox' );
				$job_settings['dropboxtoken'] = $dropbox->oAuthToken( sanitize_text_field( $_POST['sandbox_code'] ) );
			} catch ( Exception $e ) {
				BackWPup_Admin::message( 'DROPBOX: ' . $e->getMessage(), true );
			}
		}

		if ( isset( $_POST['dropboxdir'] ) ) {
			$_POST['dropboxdir'] = trailingslashit(
				str_replace( '//', '/', str_replace( '\\', '/', trim( sanitize_text_field( $_POST['dropboxdir'] ) ) ) )
			);
			if ( $_POST['dropboxdir'] === '/' ) {
				$_POST['dropboxdir'] = '';
			}
			$job_settings['dropboxdir'] = $_POST['dropboxdir'];
		}

		$job_settings['dropboxsyncnodelete'] = ! empty( $_POST['dropboxsyncnodelete'] );

		if ( isset( $_POST['dropboxmaxbackups'] ) ) {
			$job_settings['dropboxmaxbackups'] = absint( $_POST['dropboxmaxbackups'] );
		}

		return $job_settings;
	}

	/**
	 * Get File List
	 *
	 * @param string $jobdest Job destination path.
	 *
	 * @return array The files list. Empty array if not possible to retrieve the job ID or the files.
	 */
	public function file_get_list( $jobdest ) {

		$jobid = intval( substr( $jobdest, 0, strpos( $jobdest, '_', 1 ) ) );

		if ( ! $jobid ) {
			return array();
		}

		$registry = Restore\Functions\restore_registry();
		$files    = parent::file_get_list( $jobdest );

		// Include the Restore link.
		foreach ( $files as $index => &$file ) {
			$file['restoreurl'] = add_query_arg(
				array(
					'page'         => 'backwpuprestore',
					'action'       => 'restore-destination_dropbox',
					'file'         => $files[ $index ]['file'],
					'restore_file' => $registry->uploads_folder . '/' . basename( $files[ $index ]['file'] ),
					'jobid'        => $jobid,
					'service'      => 'Dropbox',
				),
				network_admin_url( 'admin.php' )
			);
		}

		return $files;
	}

	/**
	 * Run Job
	 *
	 * @param \BackWPup_Job $job_object
	 *
	 * @return bool
	 */
	public function job_run_sync( BackWPup_Job $job_object ) {

		global $folder_on_dropbox, $files_on_dropbox;

		$job_object->substeps_todo = $job_object->count_folder + $job_object->count_folder + count(
				$job_object->additional_files_to_backup
			) + 1;
		$job_object->substeps_done = 0;

		if ( $job_object->steps_data[ $job_object->step_working ]['SAVE_STEP_TRY'] != $job_object->steps_data[ $job_object->step_working ]['STEP_TRY'] ) {
			$job_object->log(
				sprintf(
					__( '%d. Try to sync files to Dropbox&#160;&hellip;', 'backwpup' ),
					$job_object->steps_data[ $job_object->step_working ]['STEP_TRY']
				)
			);
		}

		try {
			$dropbox = new BackWPup_Destination_Dropbox_API( $job_object->job['dropboxroot'], $job_object );
			$dropbox->setOAuthTokens( $job_object->job['dropboxtoken'] );

			//get account info
			if ( $job_object->steps_data[ $job_object->step_working ]['SAVE_STEP_TRY'] != $job_object->steps_data[ $job_object->step_working ]['STEP_TRY'] ) {
				$info = $dropbox->usersGetCurrentAccount();
				if ( ! empty( $info['account_id'] ) ) {
					if ( $job_object->is_debug() ) {
						$user = $info['name']['display_name'] . ' (' . $info['email'] . ')';
					} else {
						$user = $info['name']['display_name'];
					}
					$job_object->log( sprintf( __( 'Authenticated with Dropbox of user: %s', 'backwpup' ), $user ) );

					//Quota
					if ( $job_object->is_debug() ) {
						$quota            = $dropbox->usersGetSpaceUsage();
						$dropboxfreespase = $quota['allocation']['allocated'] - $quota['used'];
						$job_object->log(
							sprintf(
								__( '%s available on your Dropbox', 'backwpup' ),
								size_format( $dropboxfreespase, 2 )
							)
						);
					}
				} else {
					$job_object->log( __( 'Not Authenticated with Dropbox!', 'backwpup' ), E_USER_ERROR );

					return false;
				}
			}

			//get files from dest
			$files_on_dropbox_save  = $files_on_dropbox = $job_object->data_storage( 'dropbox_files', array() );
			$folder_on_dropbox_save = $folder_on_dropbox = $job_object->data_storage( 'dropbox_folder', array() );
			if ( empty( $job_object->steps_data[ $job_object->step_working ]['key_dropbox_folder'] ) ) {
				$job_object->steps_data[ $job_object->step_working ]['key_dropbox_folder'] = 0;
				$job_object->log( __( 'Retrieving file list from Dropbox', 'backwpup' ), E_USER_NOTICE );
				$this->get_files_on_dropbox( $job_object->job['dropboxdir'], $job_object, $dropbox );
				$job_object->steps_data[ $job_object->step_working ]['key_dropbox_folder'] = 0;
			}
			while ( $job_object->steps_data[ $job_object->step_working ]['key_dropbox_folder'] < count(
					$folder_on_dropbox
				) ) {
				$next_folder = $folder_on_dropbox[ $job_object->steps_data[ $job_object->step_working ]['key_dropbox_folder'] ];
				$this->get_files_on_dropbox( $next_folder, $job_object, $dropbox );
				$job_object->substeps_done = $job_object->steps_data[ $job_object->step_working ]['key_dropbox_folder'];
			}
			$job_object->substeps_done = $job_object->count_folder;

			//Sync files
			//go folder by folder
			if ( $job_object->steps_data[ $job_object->step_working ]['SAVE_STEP_TRY'] != $job_object->steps_data[ $job_object->step_working ]['STEP_TRY'] ) {
				$job_object->log( __( 'Upload changed files to Dropbox', 'backwpup' ), E_USER_NOTICE );
			}
			foreach ( $job_object->get_folders_to_backup() as $folder_to_backup ) {
				$files_in_folder = $job_object->get_files_in_folder( $folder_to_backup );
				//remove upload folder from list
				$dest_folder_name = strtolower(
					untrailingslashit(
						trailingslashit( $job_object->job['dropboxdir'] ) . trim(
							$job_object->get_destination_path_replacement( $folder_to_backup ),
							'/'
						)
					)
				);
				foreach ( $folder_on_dropbox as $key => $dest_folder ) {
					if ( ! empty( $dest_folder_name ) && strstr( $dest_folder, $dest_folder_name ) ) {
						unset( $folder_on_dropbox[ $key ] );
					}
				}
				foreach ( $files_in_folder as $file_in_folder ) {
					//crate file name on destination
					$dest_file_name = trailingslashit( $job_object->job['dropboxdir'] ) . ltrim(
							$job_object->get_destination_path_replacement( $file_in_folder ),
							'/'
						);
					//Upload file is not exits or the same
					if ( ! isset(
							$files_on_dropbox[ utf8_encode(
								strtolower( $dest_file_name )
							) ]
						)
						|| ( isset(
								$files_on_dropbox[ utf8_encode(
									strtolower( $dest_file_name )
								) ]
							)
							&& $files_on_dropbox[ utf8_encode( strtolower( $dest_file_name ) ) ] != filesize(
								$file_in_folder
							) ) ) {
						$response = $dropbox->upload( $file_in_folder, $dest_file_name );
						if ( $response['size'] === filesize( $file_in_folder ) ) {
							$files_on_dropbox_save[ utf8_encode( strtolower( $dest_file_name ) ) ] = filesize(
								$file_in_folder
							);
							$job_object->data_storage( 'dropbox_files', $files_on_dropbox_save );
							if ( ! in_array(
								strtolower( dirname( $dest_file_name ) ),
								$folder_on_dropbox_save,
								true
							) ) {
								$folder_on_dropbox_save[] = strtolower( dirname( $dest_file_name ) );
								$job_object->data_storage( 'dropbox_folder', $folder_on_dropbox_save );
							}
							$job_object->log(
								sprintf( __( 'File %s uploaded to Dropbox', 'backwpup' ), $dest_file_name ),
								E_USER_NOTICE
							);
							$job_object->do_restart_time();
						}
					}

					//remove from array
					if ( isset( $files_on_dropbox[ utf8_encode( strtolower( $dest_file_name ) ) ] ) ) {
						unset( $files_on_dropbox[ utf8_encode( strtolower( $dest_file_name ) ) ] );
					}
				}
				$job_object->substeps_done++;
				$job_object->do_restart_time();
			}

			//sync extra files
			if ( ! empty( $job_object->additional_files_to_backup ) ) {
				foreach ( $job_object->additional_files_to_backup as $file ) {
					$extra_filename = utf8_encode(
						trailingslashit( $job_object->job['dropboxdir'] ) . basename( $file )
					);
					if ( isset( $files_on_dropbox[ strtolower( $extra_filename ) ] )
						&& filesize(
							$file
						) == $files_on_dropbox[ strtolower( $extra_filename ) ] ) {
						unset( $files_on_dropbox[ strtolower( $extra_filename ) ] );
						$job_object->substeps_done++;
						continue;
					}
					$response = $dropbox->upload( $file, $extra_filename );
					if ( $response['size'] == filesize( $file ) ) {
						if ( isset( $files_on_dropbox[ strtolower( $extra_filename ) ] ) ) {
							unset( $files_on_dropbox[ strtolower( $extra_filename ) ] );
						}
						$files_on_dropbox_save[ $extra_filename ] = filesize( $file );
						$job_object->data_storage( 'dropbox_files', $files_on_dropbox_save );
						$job_object->substeps_done++;
						$job_object->log(
							sprintf( __( 'Extra file %s uploaded to Dropbox', 'backwpup' ), $extra_filename ),
							E_USER_NOTICE
						);
					}
					$job_object->do_restart_time();
				}
			}

			//delete rest files
			if ( ! $job_object->job['dropboxsyncnodelete'] ) {
				if ( $job_object->steps_data[ $job_object->step_working ]['SAVE_STEP_TRY'] != $job_object->steps_data[ $job_object->step_working ]['STEP_TRY'] ) {
					$job_object->log(
						__(
						/** @lang text */
							'Delete not existing files from Dropbox',
							'backwpup'
						),
						E_USER_NOTICE
					);
				}
				//delete folders with files
				foreach ( $folder_on_dropbox as $dest_folder_name ) {
					$response = $dropbox->filesDelete( array( 'path' => $dest_folder_name ) ); //delete folder on Cloud
					unset ( $files_on_dropbox_save[ array_search( $dest_folder_name, $files_on_dropbox_save ) ] );
					$job_object->data_storage( 'dropbox_files', $files_on_dropbox_save );
					$job_object->log(
						sprintf( __( 'Folder %s deleted from Dropbox', 'backwpup' ), $response['path_display'] ),
						E_USER_NOTICE
					);
					//remove deleted files from lists
					foreach ( $files_on_dropbox as $dest_file => $dest_file_size ) {
						if ( strstr( utf8_decode( $dest_file ), $dest_folder_name ) ) {
							unset( $files_on_dropbox[ $dest_file ] );
						}
					}
					foreach ( $files_on_dropbox_save as $dest_file => $dest_file_size ) {
						if ( strstr( utf8_decode( $dest_file ), $dest_folder_name ) ) {
							unset( $files_on_dropbox_save[ $dest_file ] );
						}
					}
					$job_object->data_storage( 'dropbox_files', $files_on_dropbox_save );
				}
				$job_object->do_restart_time();

                //delete files
                foreach ( $files_on_dropbox as $dest_file => $dest_file_size ) {
                    $response = $dropbox->filesDelete(
                        array( 'path' => utf8_decode( $dest_file ) )
                    ); //delete files on Cloud
                    $job_object->log(
                        sprintf( __( 'File %s deleted from Dropbox', 'backwpup' ), $response['path_display'] ),
                        E_USER_NOTICE
                    );
                    unset ( $files_on_dropbox_save[ $dest_file ] );
                    $job_object->data_storage( 'dropbox_files', $files_on_dropbox_save );

                    $job_object->do_restart_time();
                }
			}
			$job_object->substeps_done++;

		} catch ( Exception $e ) {
			$job_object->log(
				E_USER_ERROR,
				sprintf( __( 'Dropbox API: %s', 'backwpup' ), $e->getMessage() ),
				$e->getFile(),
				$e->getLine()
			);

			return false;
		}

		return true;
	}

	/**
	 * Get files from Dropbox
	 *
	 * Helper method to get file list recursively from Dropbox
	 *
	 * @param $folder
	 * @param $job_object BackWPup_Job
	 * @param $dropbox    BackWPup_Destination_Dropbox_API
	 */
	private function get_files_on_dropbox( $folder, $job_object, $dropbox ) {

		global $folder_on_dropbox, $files_on_dropbox;

		$filesList  = $dropbox->listFolder( $folder );
		$folder_key = count( $folder_on_dropbox );

		foreach ( $filesList as $data ) {
			if ( $data['.tag'] == 'file' ) {
				$files_on_dropbox[ utf8_encode( $data['path_lower'] ) ] = $data['size'];
			} else {
				if ( ! in_array( $data['path_lower'], $folder_on_dropbox, true ) ) {
					$folder_on_dropbox[ $folder_key ] = $data['path_lower'];
					$folder_key++;
				}
			}
		}

		$job_object->data_storage( 'dropbox_files', $files_on_dropbox );
		$job_object->data_storage( 'dropbox_folder', $folder_on_dropbox );
		$job_object->steps_data[ $job_object->step_working ]['key_dropbox_folder']++;

		$job_object->do_restart_time();
		$job_object->update_working_data();
	}
}
