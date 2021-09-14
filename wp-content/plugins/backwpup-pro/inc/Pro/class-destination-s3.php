<?php
/**
 * S3 Destination
 *
 * Documentation: http://docs.amazonwebservices.com/aws-sdk-php-2/latest/class-Aws.S3.S3Client.html
 *
 * @package \Inpsyde\BackWPup\Pro
 */

use \Inpsyde\BackWPup\Pro\Restore;
use Inpsyde\BackWPupShared\File\MimeTypeExtractor;

/**
 * Class BackWPup_Pro_Destination_S3
 *
 * @package \Inpsyde\BackWPup\Pro
 */
class BackWPup_Pro_Destination_S3 extends BackWPup_Destination_S3 {

	/**
	 * Wizard Page
	 *
	 * @param array $job_settings Job Settings
	 */
	public function wizard_page( array $job_settings ) {
		?>
		<table class="form-table">
			<tr>
				<td>
					<fieldset>
						<label for="s3region"><?php esc_html_e( 'Select a S3 service:', 'backwpup' ); ?><br />
							<select name="s3region" id="s3region" title="<?php esc_html_e(
								'Amazon S3 Region',
								'backwpup'
							); ?>">
                                <?php
                                foreach(BackWPup_S3_Destination::options() as $id => $option) {
                                    echo '<option value="' . $id . '" ' . selected( $id, $job_settings['s3region'], false ) .
                                         '>' . esc_html__($option['label']) . '</option>';
                                }
                                ?>
							</select></label><br />
						<label for="s3accesskey"><strong><?php esc_html_e( 'Access Key:', 'backwpup' ); ?></strong>
							<input id="s3accesskey" name="s3accesskey" type="text"
								value="<?php echo esc_attr(
									$job_settings['s3accesskey']
								); ?>" class="large-text" autocomplete="off" /></label><br />
						<label for="s3secretkey"><strong><?php esc_html_e(
									'Secret Key:',
									'backwpup'
								); ?></strong><br />
							<input id="s3secretkey" name="s3secretkey" type="password"
								value="<?php echo esc_attr(
									BackWPup_Encryption::decrypt( $job_settings['s3secretkey'] )
								); ?>" class="large-text" autocomplete="off" /></label><br />
						<label for="s3bucketselected"><strong><?php esc_html_e(
									'Bucket:',
									'backwpup'
								); ?></strong><br />
							<input id="s3bucketselected" name="s3bucketselected" type="hidden" value="<?php echo esc_attr(
								$job_settings['s3bucket']
							); ?>" />
							<?php if ( $job_settings['s3accesskey'] && $job_settings['s3secretkey'] ) {
								$this->edit_ajax(
									array(
										's3accesskey'      => $job_settings['s3accesskey'],
										's3secretkey'      => $job_settings['s3secretkey'],
										's3bucketselected' => $job_settings['s3bucket'],
										's3region'         => $job_settings['s3region'],
									)
								);
							} ?></label>

						&nbsp;&nbsp;&nbsp;<label for="s3newbucket"><?php esc_html_e( 'New Bucket:', 'backwpup' ); ?>
							<input id="s3newbucket" name="s3newbucket" type="text" value="" class="small-text" autocomplete="off" /></label><br />
						<br />
						<label for="ids3dir"><strong><?php esc_html_e(
									'Folder in bucket:',
									'backwpup'
								); ?></strong><br />
							<input name="s3dir" id="ids3dir" type="text" value="<?php echo esc_attr(
								$job_settings['s3dir']
							); ?>" class="large-text" /></label><br />

						<?php
						if ( $job_settings['backuptype'] === 'archive' ) {
							?>
							<label id="ids3maxbackups">
								<input
									name="s3maxbackups"
									id="ids3maxbackups"
									type="number"
									min="0"
									step="1"
									value="<?php echo esc_attr( $job_settings['s3maxbackups'] ); ?>"
									class="small-text"
								/>
								<?php esc_html_e( 'Number of files to keep in folder.', 'backwpup' ); ?></label>
							<br />
						<?php } else { ?>
							<label for="ids3syncnodelete"><input class="checkbox" value="1"
									type="checkbox" <?php checked( $job_settings['s3syncnodelete'], true ); ?>
									name="s3syncnodelete" id="ids3syncnodelete" /> <?php esc_html_e(
									'Do not delete files while syncing to destination!',
									'backwpup'
								); ?></label>
							<br />
						<?php } ?>
					</fieldset>
				</td>
			</tr>
		</table>
		<?php
	}

	/**
	 * Save Wizard Options
	 *
	 * @param array $job_settings Job Settings
	 *
	 * @return array Job Settings
	 */
	public function wizard_save( array $job_settings ) {

		$job_settings['s3ssencrypt']    = '';
		$job_settings['s3storageclass'] = '';
		$job_settings['s3accesskey'] = isset( $_POST['s3accesskey'] ) ? sanitize_text_field( $_POST['s3accesskey'] )
			: '';
		$job_settings['s3secretkey'] = isset( $_POST['s3secretkey'] ) ? BackWPup_Encryption::encrypt(
			(string) $_POST['s3secretkey']
		) : '';
		$job_settings['s3region']    = isset( $_POST['s3region'] ) ? sanitize_text_field( $_POST['s3region'] ) : '';
		$job_settings['s3bucket']    = isset( $_POST['s3bucket'] ) ? sanitize_text_field( $_POST['s3bucket'] ) : '';

		$_POST['s3dir'] = trailingslashit(
			str_replace( '//', '/', str_replace( '\\', '/', trim( sanitize_text_field( $_POST['s3dir'] ) ) ) )
		);
		if (strpos($_POST['s3dir'], '/') === 0) {
			$_POST['s3dir'] = substr( $_POST['s3dir'], 1 );
		}
		if ( $_POST['s3dir'] === '/' ) {
			$_POST['s3dir'] = '';
		}
		$job_settings['s3dir'] = $_POST['s3dir'];

		if ( isset( $_POST['s3maxbackups'] ) ) {
			$job_settings['s3maxbackups'] = isset( $_POST['s3maxbackups'] ) ? absint( $_POST['s3maxbackups'] ) : 0;
		}
		if ( isset( $_POST['s3syncnodelete'] ) ) {
			$job_settings['s3syncnodelete'] = ! empty( $_POST['s3syncnodelete'] );
		}

		//create new bucket
		if ( ! empty( $_POST['s3newbucket'] ) ) {
			try {
			    $aws_destination = BackWPup_S3_Destination::fromOption($job_settings['s3region']);
			    $s3 = $aws_destination->client(
                    $job_settings['s3accesskey'],
                    $job_settings['s3secretkey']
                );
                $s3->createBucket(
                    array(
                        'Bucket'             => sanitize_text_field( $_POST['s3newbucket'] ),
                        'PathStyle'          => $aws_destination->onlyPathStyleBucket(),
                        'LocationConstraint' => $aws_destination->region(),
                    )
                );
                BackWPup_Admin::message(
                    sprintf(
                        __( 'Bucket %1$s created in %2$s.', 'backwpup' ),
                        sanitize_text_field( $_POST['s3newbucket'] )
                    )
                );
			} catch ( Aws\S3\Exception\S3Exception $e ) {
				BackWPup_Admin::message( $e->getMessage(), true );
			}
			$job_settings['s3bucket'] = sanitize_text_field( $_POST['s3newbucket'] );
		}

		return $job_settings;
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
					'action'       => 'restore-destination_s3',
					'file'         => $files[ $index ]['file'],
					'restore_file' => $registry->uploads_folder . '/' . basename( $files[ $index ]['filename'] ),
					'jobid'        => $jobid,
					'service'      => 'S3',
				),
				network_admin_url( 'admin.php' )
			);
		}

		return $files;
	}

	/**
	 * Run Sync
	 *
	 * @param BackWPup_Job $job_object The instance of the job to run.
	 *
	 * @return bool True on success, false on error.
	 */
	public function job_run_sync( BackWPup_Job $job_object ) {

		$job_object->substeps_todo = $job_object->count_folder + count( $job_object->additional_files_to_backup ) + 2;

		if ( $job_object->steps_data[ $job_object->step_working ]['SAVE_STEP_TRY'] !== $job_object->steps_data[ $job_object->step_working ]['STEP_TRY'] ) {
			$job_object->log(
				sprintf(
					__( '%d. Trying to sync files to S3 Service&#160;&hellip;', 'backwpup' ),
					$job_object->steps_data[ $job_object->step_working ]['STEP_TRY']
				)
			);
			$job_object->steps_data[ $job_object->step_working ]['on_sub_step'] = 'get_files';
			$job_object->steps_data[ $job_object->step_working ]['on_file']     = 0;
			$job_object->steps_data[ $job_object->step_working ]['on_folder']   = 0;
			$job_object->steps_data[ $job_object->step_working ]['dest_files']  = array();
		}

		try {
		    if ($job_object->job['s3base_url']) {
                $job_object->job['s3region'] = $job_object->job['s3base_url'];
            }
            $aws_destination = BackWPup_S3_Destination::fromOption($job_object->job['s3region']);
            $s3 = $aws_destination->client(
                $job_object->job['s3accesskey'],
                $job_object->job['s3secretkey']
            );

			if ( $job_object->steps_data[ $job_object->step_working ]['SAVE_STEP_TRY'] !== $job_object->steps_data[ $job_object->step_working ]['STEP_TRY'] ) {

				if ( $s3->doesBucketExist( $job_object->job['s3bucket'] ) ) {
					$bucketregion = $s3->getBucketLocation( array( 'Bucket' => $job_object->job['s3bucket'] ) );
					$job_object->log(
						sprintf(
							__( 'Connected to S3 Bucket "%1$s" in %2$s', 'backwpup' ),
							$job_object->job['s3bucket'],
							$bucketregion->get( 'LocationConstraint' )
						)
					);
				} else {
					$job_object->log(
						sprintf( __( 'S3 Bucket "%s" does not exist!', 'backwpup' ), $job_object->job['s3bucket'] ),
						E_USER_ERROR
					);

					return true;
				}

			}

			// get files from S3
			if ( $job_object->steps_data[ $job_object->step_working ]['on_sub_step'] === 'get_files' ) {

				if ( ! isset( $job_object->steps_data[ $job_object->step_working ]['file_list_results'] ) || $job_object->steps_data[ $job_object->step_working ]['file_list_results'] === 1000 ) {
					if ( ! isset( $job_object->steps_data[ $job_object->step_working ]['file_list_results'] ) ) {
						$job_object->log( __( 'Retrieving file list from S3.', 'backwpup' ) );
						$job_object->steps_data[ $job_object->step_working ]['file_list_results'] = 0;
						$args                                                                     = array(
							'Bucket'  => $job_object->job['s3bucket'],
							'Prefix'  => (string) $job_object->job['s3dir'],
							'MaxKeys' => 1000,
						);

						$objects = $s3->getIterator( 'ListObjects', $args );
						if ( is_object( $objects ) ) {
							foreach ( $objects as $object ) {
								$job_object->steps_data[ $job_object->step_working ]['dest_files'][ $object['Key'] ] = (int)$object['Size'];
								$job_object->steps_data[ $job_object->step_working ]['file_list_marker'] = $object['Key'];
								$job_object->steps_data[ $job_object->step_working ]['file_list_results']++;
							}
						}
						$job_object->do_restart_time();
					}

					while ( $job_object->steps_data[ $job_object->step_working ]['file_list_results'] === 1000 ) {
						$job_object->steps_data[ $job_object->step_working ]['file_list_results'] = 0;

						$args = array(
							'Bucket'  => $job_object->job['s3bucket'],
							'Prefix'  => (string) $job_object->job['s3dir'],
							'Marker'  => $job_object->steps_data[ $job_object->step_working ]['file_list_marker'],
							'MaxKeys' => 1000,
						);

						$objects = $s3->getIterator( 'ListObjects', $args );
						if ( is_object( $objects ) ) {
							foreach ( $objects as $object ) {
								$job_object->steps_data[ $job_object->step_working ]['dest_files'][ $object['Key'] ] = (int)$object['Size'];
								$job_object->steps_data[ $job_object->step_working ]['file_list_marker'] = $object['Key'];
								$job_object->steps_data[ $job_object->step_working ]['file_list_results']++;
							}
						}
						$job_object->do_restart_time();
					}
				}
				$job_object->substeps_done++;
				$job_object->steps_data[ $job_object->step_working ]['on_sub_step'] = 'sync_new_changed';
			}

			//create Parameter
			$create_args           = array();
			$create_args['Bucket'] = $job_object->job['s3bucket'];
			$create_args['ACL']    = 'private';
			//encryption
			if ( ! empty( $job_object->job['s3ssencrypt'] ) ) {
				$create_args['ServerSideEncryption'] = $job_object->job['s3ssencrypt'];
			}
			//Storage class
			if ( ! empty( $job_object->job['s3storageclass'] ) ) {
				$create_args['StorageClass'] = $job_object->job['s3storageclass'];
			}
			$create_args['Metadata'] = array( 'BackupTime' => date( 'Y-m-d H:i:s', $job_object->start_time ) );

			if ( $job_object->steps_data[ $job_object->step_working ]['on_sub_step'] === 'sync_new_changed' ) {
				//Sync files
				//go folder by folder
				if ( $job_object->steps_data[ $job_object->step_working ]['SAVE_STEP_TRY'] != $job_object->steps_data[ $job_object->step_working ]['STEP_TRY'] ) {
					$job_object->log( __( 'Upload changed files to S3.', 'backwpup' ) );
				}

				$folders_to_backup = $job_object->get_folders_to_backup();
				$count_folders_to_backup = count($folders_to_backup);
				for (;
				    $job_object->steps_data[ $job_object->step_working ]['on_folder'] < $count_folders_to_backup;
				    $job_object->steps_data[ $job_object->step_working ]['on_folder']++
				) {
					$files_in_folder = $job_object->get_files_in_folder(
						$folders_to_backup[ $job_object->steps_data[ $job_object->step_working ]['on_folder'] ]
					);
					$count_files_in_folder = count($files_in_folder);
					for (;
					    $job_object->steps_data[ $job_object->step_working ]['on_file'] < $count_files_in_folder;
					    $job_object->steps_data[ $job_object->step_working ]['on_file']++
					) {
						$job_object->do_restart_time();
						//crate file name on destination
						$dest_file_name = $job_object->job['s3dir'] . ltrim(
								$job_object->get_destination_path_replacement(
									$files_in_folder[ $job_object->steps_data[ $job_object->step_working ]['on_file'] ]
								),
								'/'
							);
						//Upload file is not exits or the same
                        $source_file_size = filesize( $files_in_folder[ $job_object->steps_data[ $job_object->step_working ]['on_file'] ] );
						if ( ! isset($job_object->steps_data[ $job_object->step_working ]['dest_files'][ $dest_file_name ]) ||
							 $job_object->steps_data[ $job_object->step_working ]['dest_files'][ $dest_file_name ] !== $source_file_size
                        ) {
							$create_args['Body']        = fopen(
								$files_in_folder[ $job_object->steps_data[ $job_object->step_working ]['on_file'] ],
								'rb'
							);
							$create_args['Key']         = $dest_file_name;
							$create_args['ContentType'] = MimeTypeExtractor::fromFilePath(
								$files_in_folder[ $job_object->steps_data[ $job_object->step_working ]['on_file'] ]
							);
							$s3->putObject( $create_args );
							$job_object->log( sprintf( __( 'File %s uploaded to S3.', 'backwpup' ), $dest_file_name ) );
						}
						//remove from array
						if ( isset(
							$job_object->steps_data[ $job_object->step_working ]['dest_files'][ $dest_file_name ]
						) ) {
							unset(
								$job_object->steps_data[ $job_object->step_working ]['dest_files'][ $dest_file_name ]
							);
						}
					}
					$job_object->substeps_done++;
					$job_object->steps_data[ $job_object->step_working ]['on_file'] = 0;
				}
				$job_object->steps_data[ $job_object->step_working ]['on_sub_step'] = 'sync_extra';
				$job_object->steps_data[ $job_object->step_working ]['on_file']     = 0;
			}

			if ( $job_object->steps_data[ $job_object->step_working ]['on_sub_step'] === 'sync_extra' ) {
				//sync extra files
				if ( ! empty( $job_object->additional_files_to_backup ) ) {
					for (
					; $job_object->steps_data[ $job_object->step_working ]['on_file'] < count(
						$job_object->additional_files_to_backup
					); $job_object->steps_data[ $job_object->step_working ]['on_file']++
					) {
						$job_object->do_restart_time();
						$file = $job_object->additional_files_to_backup[ $job_object->steps_data[ $job_object->step_working ]['on_file'] ];
						if ( isset(
								$job_object->steps_data[ $job_object->step_working ]['dest_files'][ $job_object->job['s3dir'] . basename( $file ) ]
							)
							&& filesize(
								$file
							) === $job_object->steps_data[ $job_object->step_working ]['dest_files'][ $job_object->job['s3dir'] . basename( $file ) ] ) {
							unset(
								$job_object->steps_data[ $job_object->step_working ]['dest_files'][ $job_object->job['s3dir'] . basename( $file ) ]
							);
							$job_object->substeps_done++;
							continue;
						}
						$create_args['Body']        = fopen( $file, 'rb' );
						$create_args['Key']         = $job_object->job['s3dir'] . basename( $file );
						$create_args['ContentType'] = MimeTypeExtractor::fromFilePath( $file );
						$s3->putObject( $create_args );
						$job_object->log(
							sprintf( __( 'Extra file %s uploaded to S3.', 'backwpup' ), basename( $file ) )
						);
						if ( isset(
							$job_object->steps_data[ $job_object->step_working ]['dest_files'][ $job_object->job['s3dir'] . basename( $file ) ]
						) ) {
							unset(
								$job_object->steps_data[ $job_object->step_working ]['dest_files'][ $job_object->job['s3dir'] . basename( $file ) ]
							);
						}
						$job_object->substeps_done++;
					}
				}
				$job_object->steps_data[ $job_object->step_working ]['on_file']     = 0;
				$job_object->steps_data[ $job_object->step_working ]['on_sub_step'] = 'sync_delete';
			}

			//delete rest files
			if ( $job_object->steps_data[ $job_object->step_working ]['on_sub_step'] === 'sync_delete' && ! $job_object->job['s3syncnodelete'] ) {
				if ( $job_object->steps_data[ $job_object->step_working ]['SAVE_STEP_TRY'] !== $job_object->steps_data[ $job_object->step_working ]['STEP_TRY'] ) {
					$job_object->log( __( 'Delete nonexistent files on S3', 'backwpup' ) );
				}
				foreach (
					array_keys(
						$job_object->steps_data[ $job_object->step_working ]['dest_files']
					) as $dest_file
				) {
					$args = array(
						'Bucket' => $job_object->job['s3bucket'],
						'Key'    => $dest_file,
					);
					$s3->deleteObject( $args );
					$job_object->log(
						sprintf( __( 'File %s deleted from S3.', 'backwpup' ), $dest_file )
					);
					unset( $job_object->steps_data[ $job_object->step_working ]['dest_files'][ $dest_file ] );
					$job_object->do_restart_time();
				}
			}
			$job_object->substeps_done++;

		} catch ( Exception $e ) {
		    $errorMessage = $e->getMessage();
            if ( $e instanceof Aws\Exception\AwsException ) {
               $errorMessage = $e->getAwsErrorMessage();
            }
			$job_object->log(
				E_USER_ERROR,
				sprintf( __( 'S3 Service API: %s', 'backwpup' ), $errorMessage ),
				$e->getFile(),
				$e->getLine()
			);

			return false;
		}

		return true;
	}

	/**
	 *
	 */
	public function wizard_inline_js() {

		?>
		<script type="text/javascript">
			jQuery( document ).ready( function ( $ ) {
				function awsgetbucket() {
					var data = {
						action          : 'backwpup_dest_s3',
						s3accesskey     : $( 'input[name="s3accesskey"]' ).val(),
						s3secretkey     : $( 'input[name="s3secretkey"]' ).val(),
						s3bucketselected: $( 'input[name="s3bucketselected"]' ).val(),
						s3region        : $( '#s3region' ).val(),
						_ajax_nonce     : $( '#backwpupajaxnonce' ).val()
					};
					$.post( ajaxurl, data, function ( response ) {
						$( '#s3bucketerror' ).remove();
						$( '#s3bucket' ).remove();
						$( '#s3bucketselected' ).after( response );
					} );
				}

				$( 'input[name="s3accesskey"]' ).change( function () {
					awsgetbucket();
				} );
				$( 'input[name="s3secretkey"]' ).change( function () {
					awsgetbucket();
				} );
				$( '#s3region' ).change( function () {
					awsgetbucket();
				} );
			} );
		</script>
		<?php
	}
}
