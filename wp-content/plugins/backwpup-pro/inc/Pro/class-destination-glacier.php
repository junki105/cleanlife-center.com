<?php
// Amazon Glacier SDK v3.93.7
// http://aws.amazon.com/de/sdkforphp2/
// https://github.com/aws/aws-sdk-php
// http://http://docs.aws.amazon.com/general/latest/gr/rande.html#glacier_region

/**
 * Documentation: http://docs.amazonwebservices.com/aws-sdk-php-2/latest/class-Aws.S3.S3Client.html
 */
class BackWPup_Pro_Destination_Glacier extends BackWPup_Destinations {

	/**
	 * @return array
	 */
	public function option_defaults() {

		return array( 'glacieraccesskey' => '', 'glaciersecretkey' => '', 'glaciervault' => '', 'glacierregion' => 'us-east-1',  'glaciermaxbackups' => 100 );
	}


	/**
	 * @param $jobid
	 */
	public function edit_tab( $jobid ) {
		?>
		<h3 class="title"><?php esc_html_e( 'Amazon Glacier', 'backwpup' ) ?></h3>
		<p></p>
		<table class="form-table">
			<tr>
				<th scope="row"><label for="glacierregion"><?php esc_html_e( 'Select a region:', 'backwpup' ) ?></label></th>
				<td>
					<select name="glacierregion" id="glacierregion" title="<?php esc_html_e( 'Amazon Glacier Region', 'backwpup' ); ?>">
                        <?php foreach ( BackWPup_Pro_Glacier_Destination::options() as $id => $option ) : ?>
							<option value="<?php echo esc_attr( $id ); ?>"
								<?php selected( $id, BackWPup_Option::get( $jobid, 'glacierregion' ) ); ?>
							>
								<?php echo esc_html( $option['label'] ); ?>
							</option>
						<?php endforeach; ?>
					</select>
				</td>
			</tr>
		</table>

		<h3 class="title"><?php esc_html_e( 'Amazon Access Keys', 'backwpup' ); ?></h3>
		<p></p>
		<table class="form-table">
			<tr>
				<th scope="row"><label for="glacieraccesskey"><?php esc_html_e( 'Access Key', 'backwpup' ); ?></label></th>
				<td>
					<input id="glacieraccesskey" name="glacieraccesskey" type="text"
						   value="<?php echo esc_attr( BackWPup_Option::get( $jobid, 'glacieraccesskey' ) );?>" class="regular-text" autocomplete="off" />
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="glaciersecretkey"><?php esc_html_e( 'Secret Key', 'backwpup' ); ?></label></th>
				<td>
					<input id="glaciersecretkey" name="glaciersecretkey" type="password"
						   value="<?php echo esc_attr( BackWPup_Encryption::decrypt( BackWPup_Option::get( $jobid, 'glaciersecretkey' ) ) ); ?>" class="regular-text" autocomplete="off" />
				</td>
			</tr>
		</table>

		<h3 class="title"><?php esc_html_e( 'Vault', 'backwpup' ); ?></h3>
		<p></p>
		<table class="form-table">
			<tr>
				<th scope="row"><label for="vaultselected"><?php esc_html_e( 'Vault selection', 'backwpup' ); ?></label></th>
				<td>
					<input id="vaultselected" name="vaultselected" type="hidden" value="<?php echo esc_attr( BackWPup_Option::get( $jobid, 'glaciervault' ) ); ?>" />
					<?php if ( BackWPup_Option::get( $jobid, 'glacieraccesskey' ) && BackWPup_Option::get( $jobid, 'glaciersecretkey' ) ) $this->edit_ajax( array(
																																					   'glacieraccesskey'  => BackWPup_Option::get( $jobid, 'glacieraccesskey' ),
																																					   'glaciersecretkey'  => BackWPup_Encryption::decrypt(BackWPup_Option::get( $jobid, 'glaciersecretkey' ) ),
																																					   'vaultselected'   => BackWPup_Option::get( $jobid, 'glaciervault' ),
																																					   'glacierregion' 	=> BackWPup_Option::get( $jobid, 'glacierregion' )
																																				  ) ); ?>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="newvault"><?php esc_html_e( 'Create a new vault', 'backwpup' ); ?></label></th>
				<td>
					<input id="newvault" name="newvault" type="text" value="" class="small-text" autocomplete="off" />
				</td>
			</tr>
		</table>

		<h3 class="title"><?php esc_html_e( 'Glacier Backup settings', 'backwpup' ); ?></h3>
		<p></p>
		<table class="form-table">
			<tr>
				<th scope="row"><?php esc_html_e( 'File deletion', 'backwpup' ); ?></th>
				<td>
					<label for="glaciermaxbackups">
						<input
							id="glaciermaxbackups"
							name="glaciermaxbackups"
							type="number"
							step="1"
							min="0"
							value="<?php echo esc_attr( BackWPup_Option::get( $jobid, 'glaciermaxbackups' ) ); ?>"
							class="small-text"
						/>&nbsp;
					<?php  esc_html_e( 'Number of files to keep in folder. (Archives deleted before 3 months after they have been stored may cause extra costs when deleted.)', 'backwpup' ); ?></label>
					<p><?php _e( '<strong>Warning</strong>: Files belonging to this job are now tracked. Old backup archives which are untracked will not be automatically deleted.', 'backwpup' ) ?></p>
				</td>
			</tr>
		</table>

		<?php
	}

	/**
	 * @param array $args
	 */
	public function edit_ajax( $args = array() ) {

		$error = '';
		$vaults_list = array();
		$ajax = FALSE;

		if ( isset($_POST[ 'glacieraccesskey' ]) || isset($_POST[ 'glaciersecretkey' ]) ) {
			if ( ! current_user_can( 'backwpup_jobs_edit' ) ) {
				wp_die( -1 );
			}
			check_ajax_referer( 'backwpup_ajax_nonce' );
			$args[ 'glacieraccesskey' ]  	= sanitize_text_field( $_POST[ 'glacieraccesskey' ] );
			$args[ 'glaciersecretkey' ]  	= sanitize_text_field( $_POST[ 'glaciersecretkey' ] );
			$args[ 'vaultselected' ]		= sanitize_text_field( $_POST[ 'vaultselected' ] );
			$args[ 'glacierregion' ]  	 	= sanitize_text_field( $_POST[ 'glacierregion' ] );
			$ajax         					= TRUE;
		}
		echo '<span id="glacierbucketerror" class="bwu-message-error">';

		if ( ! empty( $args[ 'glacieraccesskey' ] ) && ! empty( $args[ 'glaciersecretkey' ] ) ) {
		    $aws_destination = BackWPup_Pro_Glacier_Destination::fromOption($args['glacierregion']);
			try {
				$glacier = $aws_destination->client( $args[ 'glacieraccesskey' ],$args[ 'glaciersecretkey' ]);
				$vaults = $glacier->listVaults();
				if ( ! empty( $vaults['VaultList'] ) ) {
					$vaults_list = $vaults['VaultList'];
				}
				while ( ! empty( $vaults['Marker'] ) ) {
					$vaults = $glacier->listVaults( array( 'marker' => $vaults['Marker'] ) );
					if ( ! empty( $vaults['VaultList'] ) ) {
						$vaults_list = array_merge( $vaults_list, $vaults['VaultList'] );
					}
				}
			}
			catch ( Exception $e ) {
				$error = $e->getMessage();
			}
		}

		if ( empty( $args[ 'glacieraccesskey' ] ) )
			esc_html_e( 'Missing access key!', 'backwpup' );
		elseif ( empty( $args[ 'glaciersecretkey' ] ) )
			esc_html_e( 'Missing secret access key!', 'backwpup' );
		elseif ( ! empty( $error ) )
			echo esc_html( $error );
		elseif ( ! isset( $vaults ) || count( $vaults['VaultList']  ) < 1 )
			esc_html_e( 'No vault found!', 'backwpup' );
		echo '</span>';

		if ( $vaults_list ) {
			echo '<select name="glaciervault" id="glaciervault">';
			foreach ( $vaults_list as $vault ) {
				echo "<option " . selected( $args[ 'vaultselected' ], esc_attr( $vault['VaultName'] ), FALSE ) . ">" . esc_attr( $vault['VaultName'] ) . "</option>";
			}
			echo '</select>';
		}

		if ( $ajax )
			die();
	}

	/**
	 * @param $jobid
	 * @return string
	 */
	public function edit_form_post_save( $jobid ) {
		$message = '';
		BackWPup_Option::update( $jobid, 'glacieraccesskey', isset( $_POST[ 'glacieraccesskey' ] ) ? sanitize_text_field( $_POST[ 'glacieraccesskey' ] ) : '' );
		BackWPup_Option::update( $jobid, 'glaciersecretkey', isset( $_POST[ 'glaciersecretkey' ] ) ? BackWPup_Encryption::encrypt( $_POST[ 'glaciersecretkey' ] ) : '' );
		BackWPup_Option::update( $jobid, 'glacierregion', isset( $_POST[ 'glacierregion' ] ) ? sanitize_text_field( $_POST[ 'glacierregion' ] ) : '' );
		BackWPup_Option::update( $jobid, 'glaciervault', isset( $_POST[ 'glaciervault' ] ) ? sanitize_text_field( $_POST[ 'glaciervault' ] ) : '' );
		BackWPup_Option::update( $jobid, 'glaciermaxbackups', isset( $_POST[ 'glaciermaxbackups' ] ) ? absint( $_POST[ 'glaciermaxbackups' ] ) : 0 );

		//create new bucket
		if ( !empty( $_POST[ 'newvault' ] ) ) {
			try {
			    $aws_destination = BackWPup_Pro_Glacier_Destination::fromOption(BackWPup_Option::get( $jobid, 'glacierregion' ));
				$glacier = $aws_destination->client(
				        BackWPup_Option::get( $jobid, 'glacieraccesskey' ),
                        BackWPup_Option::get( $jobid, 'glaciersecretkey' )
                );

				$vault = $glacier->createVault( array( 'vaultName' => sanitize_text_field( $_POST[ 'newvault' ] ) ) );
				if ( $vault->get( 'LocationConstraint' ) ) {
					$message .= sprintf( __( 'Vault %1$s created.','backwpup'), sanitize_text_field( $_POST[ 'newvault' ] ) ) . '<br />';
				} else {
					$message .= sprintf( __( 'Vault %s could not be created.','backwpup'), sanitize_text_field( $_POST[ 'newvault' ] ) ) . '<br />';
                }
			}
			catch ( Aws\S3\Exception\S3Exception $e ) {
				$message .= $e->getMessage();
			}
			BackWPup_Option::update( $jobid, 'glaciervault', sanitize_text_field( $_POST[ 'newvault' ] ) );
		}

		return $message;
	}

	/**
	 * @param $job_settings
	 */
	public function wizard_page( array $job_settings ) {
		?>
		<table class="form-table">
			<tr>
				<td>
					<fieldset>
						<label for="glacierregion"><?php esc_html_e( 'Select an Amazon Glacier region:', 'backwpup' ); ?><br />
							<select name="glacierregion" id="glacierregion" title="<?php esc_html_e( 'Amazon Glacier Region', 'backwpup' ); ?>">
								<?php foreach (BackWPup_Pro_Glacier_Destination::options() as $id => $option ) : ?>
                                    <option value="<?php echo esc_attr( $id ); ?>"
                                        <?php selected( $id, $job_settings[ 'glacierregion' ] ); ?>
                                    >
                                        <?php echo esc_html( $option['label'] ); ?>
                                    </option>
                                <?php endforeach; ?>
							</select></label><br/>
						<label for="glacieraccesskey"><strong><?php esc_html_e( 'Access Key:', 'backwpup' ); ?></strong>
							<input id="glacieraccesskey" name="glacieraccesskey" type="text" value="<?php echo esc_attr( $job_settings[ 'glacieraccesskey' ] );?>" class="large-text" autocomplete="off" /></label><br/>
						<label for="glaciersecretkey"><strong><?php esc_html_e( 'Secret Key:', 'backwpup' ); ?></strong><br/>
							<input id="glaciersecretkey" name="glaciersecretkey" type="password" value="<?php echo esc_attr( BackWPup_Encryption::decrypt( $job_settings[ 'glaciersecretkey' ] ) );?>" class="large-text" autocomplete="off" /></label><br/>
						<label for="glaciervault"><strong><?php esc_html_e( 'Vault:', 'backwpup' ); ?></strong><br/>
							<input id="vaultselected" name="vaultselected" type="hidden" value="<?php echo esc_attr( $job_settings[ 'vaultselected' ] ); ?>" />
							<?php if ( $job_settings[ 'glacieraccesskey' ] && $job_settings[ 'glaciersecretkey' ] ) $this->edit_ajax( array(
																																 'glacieraccesskey'  	=> $job_settings[  'glacieraccesskey' ],
																																 'glaciersecretkey'  	=> $job_settings[ 'glaciersecretkey' ],
																																 'vaultselected'   		=> $job_settings[ 'glaciervault' ],
																																 'glacierregion' 		=> $job_settings[ 'glacierregion' ]
																															) ); ?></label>

						&nbsp;&nbsp;&nbsp;<label for="newvault"><?php esc_html_e('New Vault:', 'backwpup'); ?><input id="newvault" name="newvault" type="text" value="" class="small-text" autocomplete="off" /></label><br/>
						<br/>
						<label id="glaciermaxbackups"><input name="glaciermaxbackups" id="glaciermaxbackups" type="number" step="1" min="0" value="<?php echo esc_attr( $job_settings[ 'glaciermaxbackups' ] );?>" class="small-text" />
							<?php  esc_html_e( 'Number of files to keep in folder. (Archives deleted before 3 months after they have been stored may cause extra costs when deleted.)', 'backwpup' ); ?></label>
						<br/>
					</fieldset>
				</td>
			</tr>
		</table>
		<?php
	}

	/**
	 * @param $job_settings
	 *
	 * @return array
	 */
	public function wizard_save( array $job_settings ) {

		$job_settings[ 'glacieraccesskey' ] = isset( $_POST[ 'glacieraccesskey' ] ) ? sanitize_text_field( $_POST[ 'glacieraccesskey' ] ) : '';
		$job_settings[ 'glaciersecretkey' ] = isset( $_POST[ 'glaciersecretkey' ] ) ? BackWPup_Encryption::encrypt( (string) $_POST[ 'glaciersecretkey' ] ) : '';
		$job_settings[ 'glacierregion' ] = isset( $_POST[ 'glacierregion' ] ) ? sanitize_text_field( $_POST[ 'glacierregion' ] ) : '';
		$job_settings[ 'glaciervault' ] = isset( $_POST[ 'glaciervault' ] ) ? sanitize_text_field( $_POST[ 'glaciervault' ] ) : '';
		$job_settings[ 'glaciermaxbackups' ] = !empty( $_POST[ 'glaciermaxbackups' ] ) ? absint($_POST[ 'glaciermaxbackups' ]) : 0;

		//create new bucket
		if ( !empty( $_POST[ 'newvault' ] ) ) {
			try {
			    $aws_destination = BackWPup_Pro_Glacier_Destination::fromOption($job_settings[ 'glacierregion' ]);
				$glacier = $aws_destination->client(
				        $job_settings[ 'glacieraccesskey' ],
                        $job_settings[ 'glaciersecretkey' ]
                );

				$vault = $glacier->createVault( array( 'vaultName' => sanitize_text_field( $_POST[ 'newvault' ] ) ) );
				if ( $vault->get( 'LocationConstraint' ) ) {
					BackWPup_Admin::message( sprintf( __( 'Vault %1$s created.','backwpup'), sanitize_text_field( $_POST[ 'newvault' ] ) ) );
				} else {
					BackWPup_Admin::message( sprintf( __( 'Vault %s could not be created.','backwpup'), sanitize_text_field( $_POST[ 'newvault' ] ) ) );
                }
			}
			catch ( Exception $e ) {
				BackWPup_Admin::message( $e->getMessage() );
			}
			$job_settings[ 'newvault' ] = sanitize_text_field( $_POST[ 'newvault' ] );
		}

		return $job_settings;
	}

	/**
	 * @param $jobdest
	 * @param $backupfile
	 */
	public function file_delete( $jobdest, $backupfile ) {

		$files =  get_option( 'backwpup_' . strtolower( $jobdest ), array() );
		list( $jobid, $dest ) = explode( '_', $jobdest );

		$accessKey = BackWPup_Option::get( $jobid, 'glacieraccesskey' );
        $secretKey = BackWPup_Option::get( $jobid, 'glaciersecretkey' );
        $vault = BackWPup_Option::get( $jobid, 'glaciervault' );
        $region = BackWPup_Option::get( $jobid, 'glacierregion' );

		if ( $accessKey && $secretKey && $vault ) {
			try {
			    $aws_destination = BackWPup_Pro_Glacier_Destination::fromOption($region);
				$glacier = $aws_destination->client(
				     $accessKey,
                     $secretKey
                );

				$glacier->deleteArchive(
				        array(
                             'vaultName' => $vault,
                             'archiveId' => $backupfile
                        )
                );
				foreach ( $files as $key => $file ) {
					if ( is_array( $file ) && $file[ 'file' ] === $backupfile ) {
						unset( $files[ $key ] );
					}
				}
			}
			catch ( Exception $e ) {
				BackWPup_Admin::message( sprintf( __('AWS API: %s','backwpup'), $e->getMessage() ) );
			}
		}
		update_option( 'backwpup_'. strtolower( $jobdest ), $files );
	}

	/**
	 * @inheritdoc
	 */
	public function file_get_list( $jobdest ) {

		$list = (array) get_site_option( 'backwpup_' . strtolower( $jobdest ) );
		$list = array_filter( $list );

		return $list;
	}

	/**
	 * @param $job_object
	 * @return bool
	 */
	public function job_run_archive( BackWPup_Job $job_object ) {

		$job_object->substeps_todo = 2 + $job_object->backup_filesize;
		if ( $job_object->steps_data[ $job_object->step_working ][ 'SAVE_STEP_TRY' ] != $job_object->steps_data[ $job_object->step_working ][ 'STEP_TRY' ] )
			$job_object->log( sprintf( __( '%d. Trying to send backup file to Amazon Glacier&#160;&hellip;', 'backwpup' ), $job_object->steps_data[ $job_object->step_working ][ 'STEP_TRY' ] ), E_USER_NOTICE );

		try {
		    $aws_destination = BackWPup_Pro_Glacier_Destination::fromOption($job_object->job[ 'glacierregion' ]);
            $glacier = $aws_destination->client(
                    $job_object->job[ 'glacieraccesskey' ],
                    $job_object->job[ 'glaciersecretkey' ]
            );

			$vault = $glacier->describeVault( array( 'vaultName' => $job_object->job[ 'glaciervault' ] ) );
			if ( $job_object->steps_data[ $job_object->step_working ]['SAVE_STEP_TRY'] != $job_object->steps_data[ $job_object->step_working ][ 'STEP_TRY' ] && $job_object->substeps_done < $job_object->backup_filesize ) {

				if ( $vault->get( 'VaultName' ) ) {
					$job_object->log( sprintf( __( 'Connected to Glacier vault "%1$s" with %2$d archives and size of %3$d', 'backwpup' ), $vault->get( 'VaultName' ), $vault->get( 'NumberOfArchives' ), size_format( $vault->get( 'SizeInBytes' ), 2 ) ) );
				} else {
					$job_object->log( sprintf( __( 'Glacier vault "%s" does not exist!', 'backwpup' ), $job_object->job[ 'glaciervault' ] ), E_USER_ERROR );

					return TRUE;
				}

				//transfer file to Glacier
				$job_object->log( __( 'Starting upload to Amazon Glacier&#160;&hellip;', 'backwpup' ) );
			}

			//Prepare Upload
			$job_object->steps_data[ $job_object->step_working ][ 'partSize' ] 	= 4194304; //4MB
            $filesize = filesize($job_object->backup_folder . $job_object->backup_file);
			//UploadPartGenerator closes $file_handel
			if ( $file_handel = fopen( $job_object->backup_folder . $job_object->backup_file, 'rb' ) ) {

				try {

					if ( empty ( $job_object->steps_data[ $job_object->step_working ][ 'uploadId' ] ) ) {
						$upload = $glacier->initiateMultipartUpload( array(	'vaultName' => $job_object->job[ 'glaciervault' ],
																			'partSize' => $job_object->steps_data[ $job_object->step_working ][ 'partSize' ] ) );

						$job_object->steps_data[ $job_object->step_working ][ 'uploadId' ] = $upload->get( 'uploadId' );
						$job_object->steps_data[ $job_object->step_working ][ 'part' ] = 1;
					}

					fseek( $file_handel, $job_object->steps_data[ $job_object->step_working ][ 'partSize' ] * ($job_object->steps_data[ $job_object->step_working ][ 'part' ] - 1));

					while ( ! feof( $file_handel ) ) {
						$chunk_upload_start = microtime( TRUE );
						$first_byte = ($job_object->steps_data[ $job_object->step_working ][ 'part' ] - 1) * $job_object->steps_data[ $job_object->step_working ][ 'partSize' ];
                        $last_byte = min(($job_object->steps_data[ $job_object->step_working ][ 'part' ] * $job_object->steps_data[ $job_object->step_working ][ 'partSize' ]) - 1, $filesize - 1);
						$glacier->uploadMultipartPart(
						        array(
						                'vaultName' => $job_object->job[ 'glaciervault' ],
                                        'uploadId' => $job_object->steps_data[ $job_object->step_working ][ 'uploadId' ],
                                        'range' =>  'bytes '.$first_byte.'-'.$last_byte.'/*',
                                        'body' => fread( $file_handel, $job_object->steps_data[ $job_object->step_working ][ 'partSize' ] ),
                                )
                        );
						$chunk_upload_time = microtime( TRUE ) - $chunk_upload_start;
						$job_object->substeps_done = $job_object->substeps_done + $job_object->steps_data[ $job_object->step_working ][ 'partSize' ];
						$job_object->steps_data[ $job_object->step_working ][ 'part' ] ++;
						$time_remaining = $job_object->do_restart_time();
						if ( $time_remaining < $chunk_upload_time )
							$job_object->do_restart_time( TRUE );
						$job_object->update_working_data();
					}
					fclose($file_handel);

					$file_handel = fopen($job_object->backup_folder . $job_object->backup_file, 'rb');
                    $th = new \Aws\Glacier\TreeHash();
                    while ( ! feof( $file_handel ) ) {
                        $th->update(fread($file_handel, $job_object->steps_data[ $job_object->step_working ][ 'partSize' ]));
                    }
                    fclose($file_handel);
                    $hash = $th->complete();
                    $hash = bin2hex($hash);

					$result = $glacier->completeMultipartUpload(
					    array(
					            'vaultName' => $job_object->job[ 'glaciervault' ],
                                'uploadId' => $job_object->steps_data[ $job_object->step_working ][ 'uploadId' ],
                                'archiveSize' => $filesize,
                                'checksum' => $hash
                        )
                    );

					if ( $result->get( 'archiveId' ) ) {
						$job_object->substeps_done = 1 + $job_object->backup_filesize;
						//sore file data
						$backup_files = get_site_option( 'backwpup_' . $job_object->job[ 'jobid' ] . '_glacier', array() );
						$backup_files[] = array( 	'folder' 	=> '/',
													'file' 	 	=> $result->get( 'archiveId' ),
													'filename' 	=> $job_object->backup_file,
													'info'		=> sprintf( __( 'Archive ID: %s', 'backwpup' ), $result->get( 'archiveId' ) ),
													'downloadurl' => '',
													'filesize'  => $job_object->backup_filesize,
													'time' 		=> current_time( 'timestamp', TRUE ) );
						update_site_option( 'backwpup_' . $job_object->job[ 'jobid' ] . '_glacier' , $backup_files );
						$job_object->substeps_done = 1 + $job_object->backup_filesize;
						$job_object->log( sprintf( __( 'Backup transferred to %s.', 'backwpup' ), $result->get( 'location' ) ) );
					} else {
						$job_object->log(
										sprintf(
											__( 'Error transfering backup to %s.', 'backwpup' ),
											__( 'Glacier', 'backwpup' )
										),
										E_USER_ERROR
										);
					}

				} catch ( Exception $e ) {
					$job_object->log( E_USER_ERROR, sprintf( __( 'AWS API: %s', 'backwpup' ), $e->getMessage() ), $e->getFile(), $e->getLine() );
					if ( ! empty( $job_object->steps_data[ $job_object->step_working ][ 'uploadId' ] ) )
						$glacier->abortMultipartUpload( array(	'vaultName' => $job_object->job[ 'glaciervault' ],
															    'uploadId' => $job_object->steps_data[ $job_object->step_working ][ 'uploadId' ] ) );
					unset( $job_object->steps_data[ $job_object->step_working ][ 'uploadId' ] );
					unset( $job_object->steps_data[ $job_object->step_working ][ 'part' ] );
					$job_object->substeps_done = 0;
					return FALSE;
				}
			} else {
				$job_object->log( __( 'Can not open source file for transfer.', 'backwpup' ), E_USER_ERROR );
				return FALSE;
			}


		}
		catch ( Exception $e ) {
			$job_object->log( E_USER_ERROR, sprintf( __( 'AWS API: %s', 'backwpup' ), $e->getMessage() ), $e->getFile(), $e->getLine() );

			return FALSE;
		}

		try {
			$backupfilelist = array();

			foreach ( $backup_files as $file ) {
				if ( $this->is_backup_archive( $file[ 'filename' ] ) === true ) {
					$backupfilelist[ $file[ 'time' ] ] = $file[ 'file' ];
				}
			}

			if ( $job_object->job[ 's3maxbackups' ] > 0 && is_object( $glacier ) ) { //Delete old backups
				if ( count( $backupfilelist ) > $job_object->job[ 'glaciermaxbackups' ] ) {
					ksort( $backupfilelist );
					$numdeltefiles = 0;
					while ( $file = array_shift( $backupfilelist ) ) {
						if ( count( $backupfilelist ) < $job_object->job[ 'glaciermaxbackups' ] ) {
							break;
						}
						$args = array(
							'vaultName' => $job_object->job[ 'glaciervault' ],
							'archiveId' => $file
						);
						if ( $glacier->deleteArchive( $args ) ) {
							foreach ( $backup_files as $key => $filedata ) {
								if ( $filedata[ 'file' ] === $file ) {
									unset( $backup_files[ $key ] );
								}
							}
							$numdeltefiles ++;
						} else {
							$job_object->log( sprintf( __( 'Cannot delete archive from %s.', 'backwpup' ), $job_object->job[ 'glaciervault' ] ), E_USER_ERROR );
						}
					}
					if ( $numdeltefiles > 0 )
						$job_object->log( sprintf( _n( 'One file deleted on vault.', '%d files deleted on vault', $numdeltefiles, 'backwpup' ), $numdeltefiles ), E_USER_NOTICE );
				}
			}
			update_site_option( 'backwpup_' . $job_object->job[ 'jobid' ] . '_glacier', $backup_files );
		}
		catch ( Exception $e ) {
			$job_object->log( E_USER_ERROR, sprintf( __( 'AWS API: %s', 'backwpup' ), $e->getMessage() ), $e->getFile(), $e->getLine() );

			return FALSE;
		}
		$job_object->substeps_done = 2 + $job_object->backup_filesize;

		return TRUE;
	}

	/**
	 * @param $job_settings
	 * @return bool
	 */
	public function can_run( array $job_settings ) {

		if ( empty( $job_settings[ 'glacieraccesskey' ] ) )
			return FALSE;

		if ( empty( $job_settings[ 'glaciersecretkey' ] ) )
			return FALSE;

		if ( empty( $job_settings[ 'glaciervault' ] ) )
			return FALSE;

		return TRUE;
	}

	/**
	 *
	 */
	public function wizard_inline_js() {

		$this->edit_inline_js();
	}

	/**
	 *
	 */
	public function edit_inline_js() {
		?>
		<script type="text/javascript">
			jQuery(document).ready(function ($) {
				function awsgetvault() {
					var data = {
						action: 'backwpup_dest_glacier',
						glacieraccesskey: $('input[name="glacieraccesskey"]').val(),
						glaciersecretkey: $('input[name="glaciersecretkey"]').val(),
						vaultselected: $('input[name="vaultselected"]').val(),
						glacierregion: $('#glacierregion').val(),
						_ajax_nonce: $('#backwpupajaxnonce').val()
					};
					$.post(ajaxurl, data, function (response) {
						$('#glacierbucketerror').remove();
						$('#glaciervault').remove();
						$('#vaultselected').after(response);
					});
				}

				$('input[name="glacieraccesskey"]').backwpupDelayKeyup(function () {
					awsgetvault();
				});
				$('input[name="glaciersecretkey"]').backwpupDelayKeyup(function () {
					awsgetvault();
				});
				$('#glacierregion').change(function () {
					awsgetvault();
				});
			});
			</script>
		<?php
	}
}
