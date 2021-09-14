<?php
/**
 *
 */
class BackWPup_Pro_Destination_RSC extends BackWPup_Destination_RSC {

	/**
	 * @return array
	 */
	public function option_defaults() {
		return array( 'rscusername' => '', 'rscapikey' => '', 'rsccontainer' => '', 'rscregion' => 'DFW', 'rscdir' => trailingslashit( sanitize_file_name( get_bloginfo( 'name' ) ) ), 'rscmaxbackups' => 15, 'rscsyncnodelete' => TRUE );
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
						<label for="rscusername"><?php esc_html_e( 'Username:', 'backwpup' ); ?><br/>
						<input id="rscusername" name="rscusername" type="text"
							   value="<?php echo esc_attr( $job_settings[ 'rscusername' ] ); ?>" class="large-text" /></label><br/>
						<?php esc_html_e( 'API Key:', 'backwpup' ); ?><br/>
						<label for="rscapikey"><input id="rscapikey" name="rscapikey" type="text"
							   value="<?php echo esc_attr( BackWPup_Encryption::decrypt( $job_settings[ 'rscapikey' ] ) );?>" class="large-text" /></label><br/>
						<label for="rscregion"><?php esc_html_e( 'Select region:', 'backwpup' ); ?><br />
						<select name="rscregion" id="rscregion" title="<?php esc_html_e( 'Rackspace Cloud Files Region', 'backwpup' ); ?>">
							<option value="DFW" <?php selected( 'DFW', $job_settings[ 'rscregion' ], TRUE ) ?>><?php esc_html_e( 'Dallas (DFW)', 'backwpup' ); ?></option>
							<option value="ORD" <?php selected( 'ORD', $job_settings[ 'rscregion' ], TRUE ) ?>><?php esc_html_e( 'Chicago (ORD)', 'backwpup' ); ?></option>
							<option value="SYD" <?php selected( 'SYD', $job_settings[ 'rscregion' ], TRUE ) ?>><?php esc_html_e( 'Sydney (SYD)', 'backwpup' ); ?></option>
							<option value="LON" <?php selected( 'LON', $job_settings[ 'rscregion' ], TRUE ) ?>><?php esc_html_e( 'London (LON)', 'backwpup' ); ?></option>
							<option value="IAD" <?php selected( 'IAD', $job_settings[ 'rscregion' ], TRUE ) ?>><?php esc_html_e( 'Northern Virginia (IAD)', 'backwpup' ); ?></option>
							<option value="HKG" <?php selected( 'HKG', $job_settings[ 'rscregion' ], TRUE ) ?>><?php esc_html_e( 'Hong Kong (HKG)', 'backwpup' ); ?></option>
						</select></label><br/>
						<label for="rsccontainerselected"><?php esc_html_e( 'Container:', 'backwpup' ); ?><br/>
						<input id="rsccontainerselected" name="rsccontainerselected" type="hidden"
							   value="<?php echo esc_attr( $job_settings[ 'rsccontainer' ] );?>" /></label>
						<?php if ( $job_settings[ 'rscusername' ] && $job_settings[ 'rscapikey' ] ) $this->edit_ajax( array(
																															'rscusername' => $job_settings[ 'rscusername' ],
																															'rscregion' => $job_settings[ 'rscregion' ],
																															'rscapikey'   => BackWPup_Encryption::decrypt( $job_settings[ 'rscapikey' ] ),
																															'rscselected' => $job_settings[ 'rsccontainer' ]
																													   ) ); ?>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label><?php esc_html_e( 'Create container:', 'backwpup' ); ?>
						<input name="newrsccontainer" type="text" value="" class="text" /></label><br/>
						<label for="idrscdir"><?php esc_html_e( 'Folder in container:', 'backwpup' ); ?><br/>
						<input name="rscdir" id="idrscdir" type="text" value="<?php echo esc_attr( $job_settings[ 'rscdir' ] );?>" class="large-text" /></label><br/>

						<?php
							if ( $job_settings[ 'backuptype' ] === 'archive' ) {
								?>
							<label for="idrscmaxbackups">
								<input
									name="rscmaxbackups"
									id="idrscmaxbackups"
									type="number"
									min="0"
									step="1"
									value="<?php echo esc_attr( $job_settings[ 'rscmaxbackups' ] );?>"
									class="small-text"
								/>
							<?php  esc_html_e( 'Number of files to keep in folder.', 'backwpup' ); ?></label>
							<br/>
						<?php } else { ?>
							<label for="idrscsyncnodelete"><input class="checkbox" value="1"
								   type="checkbox" <?php checked(  $job_settings[ 'rscsyncnodelete' ], TRUE ); ?>
								   name="rscsyncnodelete" id="idrscsyncnodelete" /> <?php esc_html_e( 'Do not delete files while syncing to destination!', 'backwpup' ); ?></label>
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

		$job_settings[ 'rscusername' ] = isset( $_POST[ 'rscusername' ] ) ? sanitize_text_field( $_POST[ 'rscusername' ] ) : '';
		$job_settings[ 'rscapikey' ] = isset( $_POST[ 'rscapikey' ] ) ? BackWPup_Encryption::encrypt( (string) $_POST[ 'rscapikey' ] ) : '';
		$job_settings[ 'rsccontainer' ] = isset( $_POST[ 'rsccontainer' ] ) ? sanitize_text_field( $_POST[ 'rsccontainer' ] ) : '';
		$job_settings[ 'rscregion' ] = ! empty( $_POST[ 'rscregion' ] ) ? sanitize_text_field( $_POST[ 'rscregion' ] ) : 'DFW';

		$_POST[ 'rscdir' ] = trailingslashit( str_replace( '//', '/', str_replace( '\\', '/', trim( sanitize_text_field( $_POST[ 'rscdir' ] ) ) ) ) );
		if ( substr( $_POST[ 'rscdir' ], 0, 1 ) == '/' ) {
			$_POST[ 'rscdir' ] = substr( $_POST[ 'rscdir' ], 1 );
		}
		if ( $_POST[ 'rscdir' ] === '/' ) {
			$_POST[ 'rscdir' ] = '';
		}
		$job_settings[ 'rscdir' ] = $_POST[ 'rscdir' ];

		$job_settings[ 'rscmaxbackups' ] = isset( $_POST[ 'rscmaxbackups' ] ) ? absint( $_POST[ 'rscmaxbackups' ] ) : 0;
		$job_settings[ 'rscsyncnodelete' ] = ! empty( $_POST[ 'rscsyncnodelete' ] );

		if ( ! empty( $_POST[ 'rscusername' ] ) && ! empty( $_POST[ 'rscapikey' ] ) && ! empty( $_POST[ 'newrsccontainer' ] ) ) {
			try {
				$conn = new OpenCloud\Rackspace(
					self::get_auth_url_by_region( sanitize_text_field( $_POST[ 'rscregion' ] ) ),
					array(
						 'username' =>  sanitize_text_field( $_POST[ 'rscusername' ] ) ,
						 'apiKey' => sanitize_text_field( $_POST[ 'rscapikey' ] )
					),
                    array(
					     'ssl.certificate_authority' => BackWPup::get_plugin_data('cacert')
                    )
                );
				$ostore = $conn->objectStoreService( 'cloudFiles', sanitize_text_field( $_POST[ 'rscregion' ] ), 'publicURL');

				$ostore->createContainer( sanitize_text_field( $_POST[ 'newrsccontainer' ] ) );
				$job_settings[ 'rsccontainer' ] = sanitize_text_field( $_POST[ 'newrsccontainer' ] );
				BackWPup_Admin::message( sprintf( __( 'Rackspace Cloud container "%s" created.', 'backwpup' ), sanitize_text_field( $_POST[ 'newrsccontainer' ] ) ) );

			}
			catch ( Exception $e ) {
				BackWPup_Admin::message( sprintf( __( 'Rackspace Cloud API: %s', 'backwpup' ), $e->getMessage() ), TRUE );
			}
		}

		return $job_settings;
	}



	/**
	 * @inheritdoc
	 */
	public function file_get_list( $jobdest ) {

		$list = (array) get_site_transient( 'backwpup_' . strtolower( $jobdest ) );
		$list = array_filter( $list );

		return $list;
	}

	/**
	 * @param $job_object
	 * @return bool
	 */
	public function job_run_sync( BackWPup_Job $job_object ) {

		$job_object->substeps_todo = $job_object->count_folder + count( $job_object->additional_files_to_backup ) + 2;

		if ( $job_object->steps_data[ $job_object->step_working ]['SAVE_STEP_TRY'] != $job_object->steps_data[ $job_object->step_working ][ 'STEP_TRY' ] ) {
			$job_object->log( sprintf( __( '%d. Trying to sync files to Rackspace cloud&#160;&hellip;', 'backwpup' ), $job_object->steps_data[ $job_object->step_working ][ 'STEP_TRY' ] ) );
			$job_object->steps_data[ $job_object->step_working ][ 'on_sub_step' ] = 'get_files';
			$job_object->steps_data[ $job_object->step_working ][ 'on_file' ] = 0;
			$job_object->steps_data[ $job_object->step_working ][ 'on_folder' ] = 0;
			$job_object->steps_data[ $job_object->step_working ][ 'dest_files' ] = array();
		}

		try {

			$conn = new OpenCloud\Rackspace(
				self::get_auth_url_by_region( $job_object->job[ 'rscregion' ] ),
				array(
					 'username' => $job_object->job[ 'rscusername' ],
					 'apiKey' => BackWPup_Encryption::decrypt( $job_object->job[ 'rscapikey' ] )
				),
                array(
					 'ssl.certificate_authority' => BackWPup::get_plugin_data('cacert')
                )
            );
			//connect to cloud files
			$ostore = $conn->objectStoreService( 'cloudFiles' , $job_object->job[ 'rscregion' ], 'publicURL');

			$container = $ostore->getContainer( $job_object->job[ 'rsccontainer' ] );
			if ( $job_object->steps_data[ $job_object->step_working ]['SAVE_STEP_TRY'] != $job_object->steps_data[ $job_object->step_working ][ 'STEP_TRY' ] )
				$job_object->log( sprintf(__( 'Connected to Rackspace cloud files container %s.', 'backwpup' ), $job_object->job[ 'rsccontainer' ] ) );
		}
		catch ( Exception $e ) {
			$job_object->log( E_USER_ERROR, sprintf( __( 'Rackspace Cloud API: %s', 'backwpup' ), $e->getMessage() ), $e->getFile(), $e->getLine() );

			return FALSE;
		}

		//get files from storage
		try {
			// get files from RSC
			if ( $job_object->steps_data[ $job_object->step_working ][ 'on_sub_step' ] == 'get_files' ) {
				if ( empty( $job_object->steps_data[ $job_object->step_working ][ 'file_list_results' ] ) || $job_object->steps_data[ $job_object->step_working ][ 'file_list_results' ] == 10000 ) {
					if ( empty( $job_object->steps_data[ $job_object->step_working ][ 'file_list_results' ] ) ) {
						$job_object->log( __( 'Retrieving files list from Rackspace Cloud.', 'backwpup'  ), E_USER_NOTICE );
						$job_object->steps_data[ $job_object->step_working ][ 'file_list_results' ] = 0;
						$objlist    = $container->objectList( array( 'prefix' => $job_object->job[ 'rscdir' ] ) );
						while ( $object = $objlist->next() ) {
							$file_name = trim( (string) $object->getName() );
							if ( ! empty( $file_name ) )
								$job_object->steps_data[ $job_object->step_working ][ 'dest_files' ][ $file_name ] = $object->getContentLength();
							$job_object->steps_data[ $job_object->step_working ][ 'file_list_results' ] ++;
						}
						$job_object->do_restart_time();
					}
					while ( $job_object->steps_data[ $job_object->step_working ][ 'file_list_results' ] == 10000 ) {
						$job_object->steps_data[ $job_object->step_working ][ 'file_list_results' ] = 0;
						$objlist        = $container->objectList( array( 'prefix' => $job_object->job[ 'rscdir' ], 'marker' => $job_object->steps_data[ $job_object->step_working ][ 'file_list_marker' ] ) );
						while ( $object = $objlist->next() ) {
							$file_name = trim( (string) $object->getName() );
							if ( ! empty( $file_name ) )
								$job_object->steps_data[ $job_object->step_working ][ 'dest_files' ][ $file_name ] = $object->getContentLength();
							$job_object->steps_data[ $job_object->step_working ][ 'file_list_results' ] ++;
						}
						$job_object->do_restart_time();
					}
				}
				$job_object->substeps_done ++;
				$job_object->steps_data[ $job_object->step_working ][ 'on_sub_step' ] = 'sync_new_changed';
			}

			//Sync files
			if ( $job_object->steps_data[ $job_object->step_working ][ 'on_sub_step' ] == 'sync_new_changed' ) {
				//go folder by folder
				if ( $job_object->steps_data[ $job_object->step_working ]['SAVE_STEP_TRY'] != $job_object->steps_data[ $job_object->step_working ][ 'STEP_TRY' ] )
					$job_object->log( __( 'Upload changed files to Rackspace Cloud.', 'backwpup'  ) );
				$folders_to_backup = $job_object->get_folders_to_backup();
				for ( ; $job_object->steps_data[ $job_object->step_working ][ 'on_folder' ] < count( $folders_to_backup ); $job_object->steps_data[ $job_object->step_working ][ 'on_folder' ]++ ) {
					$files_in_folder = $job_object->get_files_in_folder( $folders_to_backup[ $job_object->steps_data[ $job_object->step_working ][ 'on_folder' ] ] );
					for( ; $job_object->steps_data[ $job_object->step_working ][ 'on_file' ] < count( $files_in_folder ); $job_object->steps_data[ $job_object->step_working ][ 'on_file' ]++ ) {
						$job_object->do_restart_time();
						//crate file name on destination
						$dest_file_name = $job_object->job[ 'rscdir' ] . ltrim( $job_object->get_destination_path_replacement( $files_in_folder[ $job_object->steps_data[ $job_object->step_working ][ 'on_file' ] ] ), '/' );
						//Upload file is not exits or the same
						if ( ! isset( $job_object->steps_data[ $job_object->step_working ][ 'dest_files' ][ utf8_encode( $dest_file_name ) ] ) || $job_object->steps_data[ $job_object->step_working ][ 'dest_files' ][ utf8_encode( $dest_file_name ) ] != filesize( $files_in_folder[ $job_object->steps_data[ $job_object->step_working ][ 'on_file' ] ] ) ) {
							if ( $handle = fopen( $files_in_folder[ $job_object->steps_data[ $job_object->step_working ][ 'on_file' ] ], 'rb' ) ) {
								$uploded = $container->uploadObject( $dest_file_name, $handle );
								fclose( $handle );
							} else {
								$job_object->log( __( 'Can not open source file for transfer.', 'backwpup' ), E_USER_ERROR );
								return FALSE;
							}
							if ( $uploded ) {
								$job_object->log( sprintf( __( 'File %s uploaded to Rackspace Cloud.', 'backwpup' ), $dest_file_name ) );
							}
						}

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
						if ( isset( $job_object->steps_data[ $job_object->step_working ][ 'dest_files' ][ utf8_encode( $job_object->job[ 'rscdir' ] . basename( $file ) ) ] ) && filesize( $file ) == $job_object->steps_data[ $job_object->step_working ][ 'dest_files' ][ utf8_encode(  $job_object->job[ 'rscdir' ] . basename( $file ) ) ] ) {
							unset( $job_object->steps_data[ $job_object->step_working ][ 'dest_files' ][ utf8_encode( $job_object->job[ 'rscdir' ] . basename( $file ) ) ]);
							$job_object->substeps_done ++;
							continue;
						}
						if ( $handle = fopen( $file, 'rb' ) ) {
							$uploded = $container->uploadObject( $job_object->job[ 'rscdir' ] . basename( $file ), $handle );
							fclose( $handle );
						} else {
							$job_object->log( __( 'Can not open source file for transfer.', 'backwpup' ), E_USER_ERROR );
							return FALSE;
						}
						if ( $uploded ) {
							$job_object->log( sprintf( __( 'Extra file %s uploaded to Rackspace Cloud.', 'backwpup' ), basename( $file ) ) );
							if ( isset( $job_object->steps_data[ $job_object->step_working ][ 'dest_files' ][ utf8_encode( $job_object->job[ 'rscdir' ] . basename( $file ) ) ] ) )
								unset($job_object->steps_data[ $job_object->step_working ][ 'dest_files' ][ utf8_encode( $job_object->job[ 'rscdir' ] . basename( $file ) ) ] );
						}
						$job_object->substeps_done ++;
					}
				}
				$job_object->steps_data[ $job_object->step_working ][ 'on_file' ] = 0;
				$job_object->steps_data[ $job_object->step_working ][ 'on_sub_step' ] = 'sync_delete';
			}

			//delete rest files
			if ( $job_object->steps_data[ $job_object->step_working ][ 'on_sub_step' ] == 'sync_delete' && ! $job_object->job[ 'rscsyncnodelete' ] ) {
				$job_object->log( __( 'Delete nonexistent files on Rackspace Cloud.', 'backwpup'  ), E_USER_NOTICE );
				foreach( array_keys( $job_object->steps_data[ $job_object->step_working ][ 'dest_files' ] ) as $dest_file ) {
					$fileobject = $container->getObject( utf8_decode( $dest_file ) );
					if ( $fileobject->delete() ) {
						$job_object->log( sprintf( __( 'File %s deleted from Rackspace Cloud.', 'backwpup' ), utf8_decode( $dest_file ) ) );
						unset( $job_object->steps_data[ $job_object->step_working ][ 'dest_files' ][ utf8_encode( $dest_file ) ] );
						$job_object->do_restart_time();
					}
				}
			}
			$job_object->substeps_done ++;

		}
		catch ( Exception $e ) {
			$job_object->log( E_USER_ERROR, sprintf( __( 'Rackspace Cloud API: %s', 'backwpup' ), $e->getMessage() ), $e->getFile(), $e->getLine() );

			return FALSE;
		}


		return TRUE;
	}


	/**
	 *
	 */
	public function wizard_inline_js() {
		?>
		<script type="text/javascript">
			jQuery(document).ready(function ($) {
				function rscgetcontainer() {
					var data = {
						action: 'backwpup_dest_rsc',
						rscusername: $('#rscusername').val(),
						rscapikey: $('#rscapikey').val(),
						rscregion: $('#rscregion').val(),
						rscselected: $('#rsccontainerselected').val(),
						_ajax_nonce: $('#backwpupajaxnonce').val()
					};
					$.post(ajaxurl, data, function (response) {
						$('#rsccontainererror').remove();
						$('#rsccontainer').remove();
						$('#rsccontainerselected').after(response);
					});
				}

				$('#rscregion').change(function () {
					rscgetcontainer();
				});
				$('#rscusername').backwpupDelayKeyup(function () {
					rscgetcontainer();
				});
				$('#rscapikey').backwpupDelayKeyup(function () {
					rscgetcontainer();
				});
			});
		</script>
	<?php
	}
}
