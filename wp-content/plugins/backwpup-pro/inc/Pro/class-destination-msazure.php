<?php

use Inpsyde\BackWPup\MsAzureDestinationConfiguration;
use Inpsyde\BackWPup\Pro\Restore;
use MicrosoftAzure\Storage\Blob\Models\CreateContainerOptions;
use MicrosoftAzure\Storage\Blob\Models\PublicAccessType;

/**
 * Documentation: http://www.windowsazure.com/en-us/develop/php/how-to-guides/blob-service/
 */
class BackWPup_Pro_Destination_MSAzure extends BackWPup_Destination_MSAzure {

    /**
	 * @param $job_settings
	 */
	public function wizard_page( array $job_settings ) {
		?>
		<table class="form-table">
			<tr>
				<td>
					<fieldset>
						<label for="msazureaccname"><?php esc_html_e( 'Account Name:', 'backwpup' ); ?><br/>
						<input id="msazureaccname" name="msazureaccname" type="text" value="<?php echo esc_attr( $job_settings[ MsAzureDestinationConfiguration::MSAZURE_ACCNAME ] );?>" class="large-text" autocomplete="off" /></label><br/>
						<label for="msazurekey"><?php esc_html_e( 'Access Key:', 'backwpup' ); ?><br/>
						<input id="msazurekey" name="msazurekey" type="password" value="<?php echo esc_attr( BackWPup_Encryption::decrypt( $job_settings[ MsAzureDestinationConfiguration::MSAZURE_KEY ] ) );?>" class="large-text" autocomplete="off" /></label><br/>
						<label for="msazurecontainerselected"><?php esc_html_e( 'Container:', 'backwpup' ); ?><br/>
						<input id="msazurecontainerselected" name="msazurecontainerselected" type="hidden" value="<?php echo esc_attr( $job_settings[ MsAzureDestinationConfiguration::MSAZURE_CONTAINER ] );?>" /></label>
						<?php if ( $job_settings[ MsAzureDestinationConfiguration::MSAZURE_ACCNAME ] && $job_settings[ MsAzureDestinationConfiguration::MSAZURE_KEY ] ) $this->edit_ajax( array(
																															   MsAzureDestinationConfiguration::MSAZURE_ACCNAME  => $job_settings[ MsAzureDestinationConfiguration::MSAZURE_ACCNAME ],
																															   MsAzureDestinationConfiguration::MSAZURE_KEY      => BackWPup_Encryption::decrypt( $job_settings[ MsAzureDestinationConfiguration::MSAZURE_KEY ] ),
																															   'msazureselected' => $job_settings[ MsAzureDestinationConfiguration::MSAZURE_CONTAINER ]
																														  ) ); ?>
						&nbsp;&nbsp;&nbsp;<label><?php esc_html_e( 'Create container:', 'backwpup' ); ?>
						<input name="newmsazurecontainer" type="text" value="" class="text" /></label><br/>
						<label for="idmsazuredir"><?php esc_html_e( 'Folder in container:', 'backwpup' ); ?><br/>
						<input name="msazuredir" id="idmsazuredir" type="text" value="<?php echo esc_attr( $job_settings[self::MSAZUREDIR] );?>" class="large-text" /></label><br/>
						<?php
							if ( $job_settings[ 'backuptype' ] === 'archive' ) {
								?>
							<label for="idmsazuremaxbackups">
								<input
									name="msazuremaxbackups"
									id="idmsazuremaxbackups"
									type="number"
									min="0"
									step="1"
									value="<?php echo  esc_attr( $job_settings[self::MSAZUREMAXBACKUPS] );?>"
									class="small-text"
								/>
							<?php  esc_html_e( 'Number of files to keep in folder.', 'backwpup' ); ?></label>
							<br/>
							<?php } else { ?>
							<label for="idmsazuresyncnodelete">
								<input
									class="checkbox"
									value="1"
								    type="checkbox"
									<?php checked(  $job_settings[self::MSAZURESYNCNODELETE], TRUE ); ?>
								    name="msazuresyncnodelete"
									id="idmsazuresyncnodelete" />
								<?php esc_html_e( 'Do not delete files while syncing to destination!', 'backwpup' ); ?>
							</label>
							<br/>
							<?php } ?>
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

        try {
            $msazureConfiguration = $this->msazureConfiguration();
        } catch (\UnexpectedValueException $exception) {
            BackWPup_Admin::message(__('Microsoft Azure Configuration: ', 'backwpup') . $exception->getMessage(), true);
            return $job_settings;
        }

        $job_settings[MsAzureDestinationConfiguration::MSAZURE_ACCNAME] = sanitize_text_field($msazureConfiguration->msazureaccname());
        $job_settings[MsAzureDestinationConfiguration::MSAZURE_KEY] = BackWPup_Encryption::encrypt($msazureConfiguration->msazurekey());
        $job_settings[MsAzureDestinationConfiguration::MSAZURE_CONTAINER] = sanitize_text_field($msazureConfiguration->msazurecontainer());

        $msazureDir = $this->msazureDir();

        $job_settings[self::MSAZUREDIR] = $msazureDir;

        $job_settings[self::MSAZUREMAXBACKUPS] = filter_input(
            INPUT_POST,
            self::MSAZUREMAXBACKUPS,
            FILTER_SANITIZE_NUMBER_INT
        ) ?: 0;

        $job_settings[self::MSAZURESYNCNODELETE] = filter_input(
            INPUT_POST,
            self::MSAZURESYNCNODELETE,
            FILTER_SANITIZE_STRING
        ) ?: '';

        $newmsazurecontainer = filter_input(
            INPUT_POST,
            self::NEWMSAZURECONTAINER,
            FILTER_SANITIZE_STRING
        );

        if ($newmsazurecontainer) {
            try {
                $this->createContainer(
                    $newmsazurecontainer,
                    $msazureConfiguration
                );

                BackWPup_Admin::message(
                    sprintf(
                        __('MS Azure container "%s" created.', 'backwpup'),
                        esc_html(sanitize_text_field($newmsazurecontainer))
                    )
                );
			} catch ( Exception $e ) {
				BackWPup_Admin::message( sprintf( __( 'MS Azure container create: %s', 'backwpup' ), $e->getMessage() ), TRUE );
                return $job_settings;
            }

            $job_settings[MsAzureDestinationConfiguration::MSAZURE_CONTAINER] = sanitize_text_field(
                $newmsazurecontainer
            );
        }

		return $job_settings;
	}

    /**
     * Returns file list array including restore_url property in it.
     * @param string $jobdest Job destination path.
     * @return array The files list. Empty array if not possible to retrieve the job ID or the files.
     */
    public function file_get_list( $jobdest ) {

        $jobid = $this->extractJobIdFromDestination($jobdest);
        $registry = Restore\Functions\restore_registry();
        $files    = parent::file_get_list( $jobdest );

        // Include the Restore link.
        foreach ( $files as $index => &$file ) {
            $indexFile = isset($files[$index]['file']) ? $files[$index]['file'] : '';

            $file['restoreurl'] = add_query_arg(
                array(
                    'page'         => 'backwpuprestore',
                    'action'       => 'restore-destination_msazure',
                    'file' => $indexFile,
                    'restore_file' => $registry->uploads_folder . '/' . basename($indexFile),
                    'jobid'        => $jobid,
                    'service'      => 'MSAzure',
                ),
                network_admin_url( 'admin.php' )
            );
        }

        return $files;
    }

	/**
	 * @param $job_object
	 * @return bool
	 */
	public function job_run_sync( BackWPup_Job $job_object ) {

		$job_object->substeps_todo = $job_object->count_folder + count( $job_object->additional_files_to_backup ) + 2;

		if ( $job_object->steps_data[ $job_object->step_working ]['SAVE_STEP_TRY'] != $job_object->steps_data[ $job_object->step_working ][ 'STEP_TRY' ] ) {
			$job_object->log( sprintf( __( '%d. Trying to sync files with Microsoft Azure (Blob) &hellip;', 'backwpup' ), $job_object->steps_data[ $job_object->step_working ][ 'STEP_TRY' ] ), E_USER_NOTICE );
			$job_object->steps_data[ $job_object->step_working ][ 'on_sub_step' ] = 'get_files';
			$job_object->steps_data[ $job_object->step_working ][ 'on_file' ] = 0;
			$job_object->steps_data[ $job_object->step_working ][ 'on_folder' ] = 0;
			$job_object->steps_data[ $job_object->step_working ][ 'dest_files' ] = array();
		}

		try {
            $blobRestProxy = $this->createBlobClient(
                $job_object->job[MsAzureDestinationConfiguration::MSAZURE_ACCNAME],
                BackWPup_Encryption::decrypt($job_object->job[MsAzureDestinationConfiguration::MSAZURE_KEY])
            );

			if ( $job_object->steps_data[ $job_object->step_working ]['SAVE_STEP_TRY'] != $job_object->steps_data[ $job_object->step_working ][ 'STEP_TRY' ] ) {
				//test for existing container
                $containers = $this->getContainers($blobRestProxy);

				$container_url = '';
				foreach( $containers as $container ) {
					if ( $container->getName() == $job_object->job[ MsAzureDestinationConfiguration::MSAZURE_CONTAINER ] ) {
						$container_url = $container->getUrl();
						break;
					}
				}

				if ( empty( $container_url ) ) {
					$job_object->log( sprintf( __( 'MS Azure container "%s" does not exist!', 'backwpup'), $job_object->job[ MsAzureDestinationConfiguration::MSAZURE_CONTAINER ] ), E_USER_ERROR );

					return TRUE;
				} else {
					$job_object->log( sprintf( __( 'Connected to MS Azure container "%s".', 'backwpup'), $job_object->job[ MsAzureDestinationConfiguration::MSAZURE_CONTAINER ] ), E_USER_NOTICE );
				}
			}

			// get files from Azure
			if ( $job_object->steps_data[ $job_object->step_working ][ 'on_sub_step' ] == 'get_files' ) {
				$job_object->log( __( 'Retrieving file list from MS Azure.', 'backwpup'  ), E_USER_NOTICE );

				$blob_options = $this->createListBlobsOptions();
				$blob_options->setPrefix( $job_object->job[self::MSAZUREDIR] );

                $blobs = $this->getBlobs(
                    $blobRestProxy,
                    $job_object->job[MsAzureDestinationConfiguration::MSAZURE_CONTAINER],
                    $blob_options
                );

				if ( is_array( $blobs ) ) {
					foreach ( $blobs as $blob ) {
						$job_object->steps_data[ $job_object->step_working ][ 'dest_files' ][ utf8_encode( $blob->getName() ) ] = $blob->getProperties()->getContentLength();
					}
				}
				$job_object->substeps_done ++;
				$job_object->steps_data[ $job_object->step_working ][ 'on_sub_step' ] = 'sync_new_changed';
			}

			//Sync files
			if ( $job_object->steps_data[ $job_object->step_working ][ 'on_sub_step' ] == 'sync_new_changed' ) {
				if ( $job_object->steps_data[ $job_object->step_working ]['SAVE_STEP_TRY'] != $job_object->steps_data[ $job_object->step_working ][ 'STEP_TRY' ] )
					$job_object->log( __( 'Upload changed files to MS Azure.', 'backwpup'  ) );

				$folders_to_backup = $job_object->get_folders_to_backup();
				for ( ; $job_object->steps_data[ $job_object->step_working ][ 'on_folder' ] < count( $folders_to_backup ); $job_object->steps_data[ $job_object->step_working ][ 'on_folder' ]++ ) {
					$files_in_folder = $job_object->get_files_in_folder( $folders_to_backup[ $job_object->steps_data[ $job_object->step_working ][ 'on_folder' ] ] );
					for( ; $job_object->steps_data[ $job_object->step_working ][ 'on_file' ] < count( $files_in_folder ); $job_object->steps_data[ $job_object->step_working ][ 'on_file' ]++ ) {
						$job_object->do_restart_time();
						//crate file name on destination
						$dest_file_name =  $job_object->job[self::MSAZUREDIR] . ltrim( $job_object->get_destination_path_replacement( $files_in_folder[ $job_object->steps_data[ $job_object->step_working ][ 'on_file' ] ] ), '/' );
						//Upload file is not exits or the same
						if ( ! isset( $job_object->steps_data[ $job_object->step_working ][ 'dest_files' ][ utf8_encode( $dest_file_name ) ] ) || ( isset( $job_object->steps_data[ $job_object->step_working ][ 'dest_files' ][ utf8_encode( $dest_file_name ) ] ) && $job_object->steps_data[ $job_object->step_working ][ 'dest_files' ][ utf8_encode( $dest_file_name ) ] != filesize( $files_in_folder[ $job_object->steps_data[ $job_object->step_working ][ 'on_file' ] ] ) ) ) {
							$blobRestProxy->createBlockBlob( $job_object->job[ MsAzureDestinationConfiguration::MSAZURE_CONTAINER ], $dest_file_name, fopen( $files_in_folder[ $job_object->steps_data[ $job_object->step_working ][ 'on_file' ] ], 'rb' ) );
							$job_object->log( sprintf( __( 'File %s uploaded to MS Azure.', 'backwpup' ), $dest_file_name ) );
							$job_object->steps_data[ $job_object->step_working ][ 'dest_files' ][ utf8_encode( $dest_file_name ) ] = filesize( $files_in_folder[ $job_object->steps_data[ $job_object->step_working ][ 'on_file' ] ] );
						}
						//remove from array
						if ( isset( $job_object->steps_data[ $job_object->step_working ][ 'dest_files' ][ utf8_encode( $dest_file_name ) ] ) )
							unset( $job_object->steps_data[ $job_object->step_working ][ 'dest_files' ][ utf8_encode( $dest_file_name ) ] );
					}
					$job_object->substeps_done ++;
					$job_object->steps_data[ $job_object->step_working ][ 'on_file' ] = 0;
				}
				$job_object->steps_data[ $job_object->step_working ][ 'on_sub_step' ] = 'sync_extra';
				$job_object->steps_data[ $job_object->step_working ][ 'on_file' ] = 0;
			}

			//sync extra files
			if ( $job_object->steps_data[ $job_object->step_working ][ 'on_sub_step' ] == 'sync_extra' ) {
				if ( ! empty( $job_object->additional_files_to_backup ) ) {
					for ( ; $job_object->steps_data[ $job_object->step_working ][ 'on_file' ] < count( $job_object->additional_files_to_backup ); $job_object->steps_data[ $job_object->step_working ][ 'on_file' ]++ ) {
						$job_object->do_restart_time();
						$file = $job_object->additional_files_to_backup[ $job_object->steps_data[ $job_object->step_working ][ 'on_file' ] ];
						if ( isset( $job_object->steps_data[ $job_object->step_working ][ 'dest_files' ][ utf8_encode( $job_object->job[self::MSAZUREDIR] . basename( $file ) ) ] ) && filesize( $file ) ==  $job_object->steps_data[ $job_object->step_working ][ 'dest_files' ][ utf8_encode( $job_object->job[self::MSAZUREDIR] . basename( $file ) ) ] ) {
							unset( $job_object->steps_data[ $job_object->step_working ][ 'dest_files' ][ utf8_encode( $job_object->job[self::MSAZUREDIR] . basename( $file ) ) ]);
							$job_object->substeps_done ++;
							continue;
						}
						$blobRestProxy->createBlockBlob( $job_object->job[ MsAzureDestinationConfiguration::MSAZURE_CONTAINER ], $job_object->job[self::MSAZUREDIR] . basename( $file ), fopen( $file, 'rb' ) );
						$job_object->log( sprintf( __( 'Extra file %s uploaded to MS Azure.', 'backwpup' ), basename( $file ) ) );
						if ( isset( $job_object->steps_data[ $job_object->step_working ][ 'dest_files' ][ utf8_encode( $job_object->job[self::MSAZUREDIR] . basename( $file ) ) ] ) )
							unset( $job_object->steps_data[ $job_object->step_working ][ 'dest_files' ][ utf8_encode( $job_object->job[self::MSAZUREDIR] . basename( $file ) ) ]);
						$job_object->substeps_done ++;
					}
				}
				$job_object->steps_data[ $job_object->step_working ][ 'on_file' ] = 0;
				$job_object->steps_data[ $job_object->step_working ][ 'on_sub_step' ] = 'sync_delete';
			}

			//delete rest files
			if ( $job_object->steps_data[ $job_object->step_working ][ 'on_sub_step' ] == 'sync_delete' &&  ! $job_object->job[self::MSAZURESYNCNODELETE] ) {
				if ( $job_object->steps_data[ $job_object->step_working ]['SAVE_STEP_TRY'] != $job_object->steps_data[ $job_object->step_working ][ 'STEP_TRY' ] )
					$job_object->log( __( 'Delete nonexistent files on MS Azure.', 'backwpup'  ), E_USER_NOTICE );
				foreach( $job_object->steps_data[ $job_object->step_working ][ 'dest_files' ] as $dest_file => $dest_file_size ) {
                    $this->deleteBlob(
                        $blobRestProxy,
                        $job_object->job[MsAzureDestinationConfiguration::MSAZURE_CONTAINER],
                        utf8_decode($dest_file)
                    );

					$job_object->log( sprintf( __( 'File %s deleted from MS Azure.', 'backwpup' ), utf8_decode( $dest_file ) ) );
					unset( $job_object->steps_data[ $job_object->step_working ][ 'dest_files' ][ $dest_file ] );
					$job_object->do_restart_time();
				}
			}
			$job_object->substeps_done ++;

		}
		catch ( Exception $e ) {
			$job_object->log( E_USER_ERROR, sprintf( __( 'Microsoft Azure API: %s', 'backwpup' ), $e->getMessage() ), $e->getFile(), $e->getLine() );

			return FALSE;
		}

		return TRUE;
	}

	/**
	 *
	 */
	public function wizard_inline_js() {

		$this->edit_inline_js();
	}
}
