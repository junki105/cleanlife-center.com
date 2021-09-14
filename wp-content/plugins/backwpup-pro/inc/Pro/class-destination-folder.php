<?php

use Inpsyde\BackWPup\Pro\Restore;

/**
 *
 */
class BackWPup_Pro_Destination_Folder extends BackWPup_Destination_Folder {

	/**
	 * @param $job_settings
	 */
	public function wizard_page( array $job_settings ) {

		?>
		<table class="form-table">
			<tr>
				<td>
					<fieldset>
						<label for="backupdir">
							<?php esc_html_e( 'Absolute path to folder for backup files:', 'backwpup' ); ?>
						</label>
						<br />
						<input
							name="backupdir"
							id="backupdir"
							type="text"
							value="<?php echo esc_attr( $job_settings['backupdir'] ); ?>"
							class="large-text"
						/>
						<br />

						<?php if ( $job_settings['backuptype'] === 'archive' ) { ?>
							<label for="idmaxbackups">
								<input
									name="maxbackups"
									id="idmaxbackups"
									type="number"
									min="0"
									step="1"
									value="<?php echo esc_attr( $job_settings['maxbackups'] ); ?>"
									class="small-text"
									title="<?php esc_attr_e( 'Oldest files will be deleted first.', 'backwpup' ); ?>"
								/>
								<?php esc_html_e( 'Number of files to keep in folder.', 'backwpup' ); ?></label>
							<br />
						<?php } else { ?>
							<label for="idbackupsyncnodelete"><input class="checkbox" value="1"
									id="idbackupsyncnodelete"
									type="checkbox" <?php checked( $job_settings['backupsyncnodelete'], true ); ?>
									name="backupsyncnodelete" /> <?php esc_html_e(
									'Do not delete files while syncing to destination!',
									'backwpup'
								); ?>
							</label>
							<br />
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

		$_POST['backupdir']        = trailingslashit(
			str_replace(
				array(
					'//',
					'\\',
				),
				'/',
				trim( sanitize_text_field( $_POST['backupdir'] ) )
			)
		);
		$job_settings['backupdir'] = sanitize_text_field( $_POST['backupdir'] );
		if ( isset( $_POST['maxbackups'] ) ) {
			$job_settings['maxbackups'] = ! empty( $_POST['maxbackups'] ) ? absint( $_POST['maxbackups'] ) : 0;
		}
		if ( isset( $_POST['backupsyncnodelete'] ) ) {
			$job_settings['backupsyncnodelete'] = ! empty( $_POST['backupsyncnodelete'] );
		}

		return $job_settings;
	}

	/**
	 * @param BackWPup_Job $job_object
	 *
	 * @return bool
	 */
	public function job_run_sync( BackWPup_Job $job_object ) {

		global $files_in_sync_folder;

		$job_object->substeps_todo = $job_object->count_folder + count( $job_object->additional_files_to_backup ) + 1;
		$job_object->substeps_done = 0;

		if ( $job_object->steps_data[ $job_object->step_working ]['SAVE_STEP_TRY'] != $job_object->steps_data[ $job_object->step_working ]['STEP_TRY'] ) {
			$job_object->log(
				sprintf(
					__( '%d. Try to sync files to folder&#160;&hellip;', 'backwpup' ),
					$job_object->steps_data[ $job_object->step_working ]['STEP_TRY']
				),
				E_USER_NOTICE
			);
		}

		//make a list of files#
		if ( $job_object->steps_data[ $job_object->step_working ]['SAVE_STEP_TRY'] != $job_object->steps_data[ $job_object->step_working ]['STEP_TRY'] ) {
			$job_object->log( __( 'Retrieving file list from folder', 'backwpup' ), E_USER_NOTICE );
		}

		// Ensure backupdir is an absolute path
		$job_object->job['backupdir'] = BackWPup_File::get_absolute_path( $job_object->job['backupdir'] );
		if ( ! wp_mkdir_p( $job_object->job['backupdir'] ) ) {
		    $job_object->log(
		            sprintf( __( 'Backup folder %s can not created', 'backwpup' ), $job_object->job['backupdir'] ),
					E_USER_ERROR
            );
        }

		$this->files_in_sync_folder( $job_object->job['backupdir'] );

		//Sync files
		//go folder by folder
		if ( $job_object->steps_data[ $job_object->step_working ]['SAVE_STEP_TRY'] != $job_object->steps_data[ $job_object->step_working ]['STEP_TRY'] ) {
			$job_object->log( __( 'Copy changed files to folder', 'backwpup' ), E_USER_NOTICE );
		}

		foreach ( $job_object->get_folders_to_backup() as $folder_to_backup ) {
			$files_in_folder = $job_object->get_files_in_folder( $folder_to_backup );
			foreach ( $files_in_folder as $file_in_folder ) {
				//create file name on destination
				$dest_file_name = $job_object->job['backupdir'] . ltrim(
						$job_object->get_destination_path_replacement( $file_in_folder ),
						'/'
					);
				//Upload file is not exits or the same
				if ( ! isset( $files_in_sync_folder[ utf8_encode( $dest_file_name ) ] )
					|| (
						isset( $files_in_sync_folder[ utf8_encode( $dest_file_name ) ] )
						&& $files_in_sync_folder[ utf8_encode( $dest_file_name ) ] != filesize( $file_in_folder )
					)
				) {
					// Make dir if needed.
					if ( ! is_dir( dirname( $dest_file_name ) ) ) {
						wp_mkdir_p( dirname( $dest_file_name ) );
					}
					// Copy file.
					copy( $file_in_folder, $dest_file_name );
					$job_object->log( sprintf( __( 'File %s copied', 'backwpup' ), $dest_file_name ), E_USER_NOTICE );
					$job_object->do_restart_time();
				}
				// Remove from array.
				if ( isset( $files_in_sync_folder[ utf8_encode( $dest_file_name ) ] ) ) {
					unset( $files_in_sync_folder[ utf8_encode( $dest_file_name ) ] );
				}
			}
			$job_object->substeps_done++;
			$job_object->do_restart_time();
		}

		// Sync extra files.
		if ( ! empty( $job_object->additional_files_to_backup ) ) {
			$job_object->log( __( 'Delete not existing files from folder', 'backwpup' ), E_USER_NOTICE );
			foreach ( $job_object->additional_files_to_backup as $file ) {
				if ( isset( $files_in_sync_folder[ utf8_encode( $job_object->job['backupdir'] . basename( $file ) ) ] )
					&& filesize( $file ) == $files_in_sync_folder[ utf8_encode(
						$job_object->job['backupdir'] . basename( $file )
					) ]
				) {
					unset( $files_in_sync_folder[ utf8_encode( $job_object->job['backupdir'] . basename( $file ) ) ] );
					$job_object->substeps_done++;
					continue;
				}
				copy( $file, $job_object->job['backupdir'] . basename( $file ) );
				$job_object->log(
					sprintf( __( 'Extra file %s copied', 'backwpup' ), basename( $file ) ),
					E_USER_NOTICE
				);
				if ( isset(
					$files_in_sync_folder[ utf8_encode(
						$job_object->job['backupdir'] . basename( $file )
					) ]
				) ) {
					unset( $files_in_sync_folder[ utf8_encode( $job_object->job['backupdir'] . basename( $file ) ) ] );
				}
				$job_object->substeps_done++;
				$job_object->do_restart_time();
			}
		}

		//delete rest files
		if ( ! $job_object->job['backupsyncnodelete'] ) {
			$dest_files = array_keys( $files_in_sync_folder );
			foreach ( $dest_files as $dest_file ) {
				if ( basename( $dest_file ) == '.donotbackup' ) {
					continue;
				}
				unlink( utf8_decode( $dest_file ) );
				$job_object->log(
					sprintf( __( 'File %s deleted from folder', 'backwpup' ), utf8_decode( $dest_file ) ),
					E_USER_NOTICE
				);
				$job_object->do_restart_time();
			}
			//delete empty folder
			$this->delete_empty_folder_in_sync_folder( $job_object->job['backupdir'], $job_object );
		}
		$job_object->substeps_done++;

		return true;
	}

	/**
	 * @inheritdoc
	 */
	public function prepare_restore( $job_id, $file_name ) {

		$backup_dir = esc_attr( BackWPup_Option::get( (int) $job_id, 'backupdir' ) );
		$backup_dir = BackWPup_File::get_absolute_path( $backup_dir );

		$get_file = realpath( trailingslashit( $backup_dir ) . basename( $file_name ) );

		return (string) $get_file;
	}

	/**
	 * Get File List
	 *
	 * @param string $jobdest Job destination path.
	 *
	 * @return array
	 */
	public function file_get_list( $jobdest ) {

		list( $jobid, $dest ) = explode( '_', $jobdest, 2 );

		$registry = Restore\Functions\restore_registry();
		$files = parent::file_get_list( $jobdest );

		// Include the Restore link.
		foreach ( $files as $index => &$file ) {
			$file['restoreurl'] = add_query_arg(
				array(
					'page'         => 'backwpuprestore',
					'action'       => 'restore-destination_folder',
					'file' => $files[ $index ]['file'],
					'restore_file' => $registry->uploads_folder . '/' . basename( $files[ $index ]['filename'] ),
					'jobid'        => $jobid,
					'service'      => 'folder'
				),
				network_admin_url( 'admin.php' )
			);
		}

		return $files;
	}

	/**
	 * Helper method to get all files already in the folder
	 *
	 * @param $folder string Folder name
	 *
	 * @return void
	 */
	private function files_in_sync_folder( $folder ) {

		global $files_in_sync_folder;

		$dir = new BackWPup_Directory( $folder );
		foreach ( $dir as $file ) {
			if ( $file->isDot() ) {
				continue;
			}
			if ( $file->isDir() ) {
				$this->files_in_sync_folder( trailingslashit( $file->getPathname() ) );
			} elseif ( $file->isReadable() && ! $file->isLink() ) {
				$files_in_sync_folder[ utf8_encode( $file->getPathname() ) ] = $file->getSize();
			}
		}
	}

	/**
	 * Helper method to delete empty folder
	 *
	 * @param $folder     string Folder name
	 * @param $job_object BackWPup_job
	 *
	 * @return bool the folder is deleted
	 */
	private function delete_empty_folder_in_sync_folder( $folder, $job_object ) {

		$entry_count = 0;
		$dir         = new BackWPup_Directory( $folder );
		foreach ( $dir as $file ) {
			if ( $file->isDot() ) {
				continue;
			}
			if ( $file->isDir() ) {
				$deleted = $this->delete_empty_folder_in_sync_folder(
					trailingslashit( $file->getPathname() ),
					$job_object
				);
				if ( $deleted ) {
					$entry_count--;
				}
			}
			$entry_count++;
		}
		if ( $entry_count <= 0 ) {
			rmdir( untrailingslashit( $folder ) );
			$job_object->log(
				sprintf( __( 'Empty folder %s deleted', 'backwpup' ), untrailingslashit( $folder ) ),
				E_USER_NOTICE
			);

			return true;
		}

		return false;
	}
}
