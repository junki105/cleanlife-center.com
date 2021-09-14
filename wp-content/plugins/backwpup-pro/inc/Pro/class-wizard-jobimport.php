<?php

/**
 *
 */
class BackWPup_Pro_Wizard_JobImport extends BackWPup_Pro_Wizards {

	const ID = 'JOBIMPORT';
	const CAPABILITY = 'backwpup_jobs_edit';

	public function __construct( $version ) {

		$this->info = array(
			'ID' => self::ID,
			'name' => esc_html__( 'XML job import', 'backwpup' ),
			'description' => esc_html__( 'Wizard for importing BackWPup jobs from an XML file', 'backwpup' ),
			'URI' => esc_html__( 'http://backwpup.com', 'backwpup' ),
			'author' => 'Inpsyde GmbH',
			'authorURI' => esc_html__( 'http://inpsyde.com', 'backwpup' ),
			'version' => $version,
			'cap' => self::CAPABILITY,
		);
	}

	public function get_last_button_name( array $wizard_settings ) {

		return __( 'Import', 'backwpup' );
	}

	public function get_steps( array $wizard_settings ) {

		$steps = array();
		$steps[0] = array(
			'id' => 'FILE',
			'name' => __( 'Import File', 'backwpup' ),
			'description' => __( 'Upload XML job file for import', 'backwpup' ),
		);
		$steps[1] = array(
			'id' => 'SELECT',
			'name' => __( 'Select items to import', 'backwpup' ),
			'description' => __( 'Select which job should be imported or overwritten.', 'backwpup' ),
		);

		return $steps;
	}

	public function page( array $wizard_settings ) {

		$import_xml = null;

		if ( $wizard_settings['wizard']['step'] === 'FILE' ) {
			$bytes = apply_filters( 'import_upload_size_limit', wp_max_upload_size() );
			$size = size_format( $bytes );
			?>
			<table class="form-table">
				<tr>
					<td>
						<p><?php esc_html_e( 'Please upload your BackWPup job XML export file and we&#8217;ll import the jobs into BackWPup.',
								'backwpup' ); ?></p>
						<p>
							<label for="upload"><?php esc_html_e( 'Choose a file from your computer:',
									'backwpup' ); ?></label> (<?php printf( __( 'Maximum size: %s', 'backwpup' ),
								$size ); ?>)
							<input type="file" id="upload" name="import"/>
							<input type="hidden" name="max_file_size" value="<?php echo esc_attr( $bytes ); ?>"/>
						</p>
					</td>
				</tr>
			</table>
			<?php
		}

		if ( $wizard_settings['wizard']['step'] === 'SELECT' ) {

			?>
			<table class="form-table">
				<tr>
					<td>
						<?php

						if ( ! empty( $wizard_settings['file']['file'] ) ) {
							$import_xml = simplexml_load_file( $wizard_settings['file']['file'] );
						}

						if ( is_object( $import_xml ) && ! empty( $import_xml->job ) ) {
							echo '<h3>' . __( 'Import Jobs', 'backwpup' ) . '</h3>';
							$jobids = BackWPup_Option::get_job_ids();
							foreach ( $import_xml->job as $job ) {
								echo "<select name=\"importtype[" . esc_attr( $job->jobid ) . "]\" title=\"" . esc_attr__( 'Import Type',
										'backwpup' ) . "\"><option value=\"not\">" . esc_html__( 'No Import',
										'backwpup' ) . "</option>";
								if ( in_array( $job->jobid, $jobids, true ) ) {
									echo "<option value=\"over\">" . esc_html__( 'Overwrite',
											'backwpup' ) . "</option><option value=\"append\">" . esc_html__( 'Append',
											'backwpup' ) . "</option>";
								} else {
									echo "<option value=\"over\">" . esc_html__( 'Import', 'backwpup' ) . "</option>";
								}
								echo "</select>";
								echo '&nbsp;<span class="description">' . esc_html( $job->jobid ) . ". " . esc_html( $job->name ) . '</span><br />';
							}
						}

						if ( is_object( $import_xml ) && ! empty( $import_xml->config ) ) {
						?>
					</td>
				</tr>
				<tr>
					<td>
						<h3><?php esc_html_e( 'Import Config', 'backwpup' ); ?></h3>
						<p>
							<input type="checkbox" value="1" name="import_config" id="import-config"/>
							<label for="import-config"><?php esc_html_e( 'Import BackWPup configuration',
									'backwpup' ); ?></label>
						</p>
						<?php
						}
						?>
					</td>
				</tr>
			</table>
			<?php
		}

	}

	public function save( array $wizard_settings ) {

		if ( isset( $wizard_settings['wizard']['step'] ) && $wizard_settings['wizard']['step'] === 'FILE' ) {

			if ( empty( $_FILES['import'] ) ) {
				BackWPup_Admin::message( __( 'File is empty. Please upload something more substantial. This error could also caused by uploads being disabled in your php.ini or by post_max_size being defined as smaller than upload_max_filesize in php.ini.',
					'backwpup' ),
					true );

				return $wizard_settings;
			}

			$overrides = array( 'test_form' => false, 'test_type' => false );
			$wizard_settings['file'] = wp_handle_upload( $_FILES['import'], $overrides );

			if ( isset( $wizard_settings['file']['error'] ) ) {
				BackWPup_Admin::message( esc_html( $wizard_settings['file']['error'] ) );

				return $wizard_settings;
			} elseif ( ! is_readable( $wizard_settings['file']['file'] ) ) {
				BackWPup_Admin::message( __( 'The export file could not be found at <code>%s</code>. This is likely due to an issue with permissions.',
					'backwpup' ),
					esc_html( $wizard_settings['file']['file'] ),
					true );

				return $wizard_settings;
			}

			$import_xml = simplexml_load_file( $wizard_settings['file']['file'] );
			if ( ! is_object( $import_xml ) ) {
				BackWPup_Admin::message( __( 'Sorry, there has been a phrase error.', 'backwpup' ), true );

				return $wizard_settings;
			}


			if ( version_compare( $import_xml['version'], '3.0', '<=' ) ) {
				BackWPup_Admin::message( sprintf( __( 'This Export file (version %s) may not be supported by this version of the importer.',
					'backwpup' ),
					esc_html( $import_xml['version'] ) ),
					true );

				return $wizard_settings;
			}

			if ( ! isset( $import_xml['plugin'] ) || $import_xml['plugin'] != 'BackWPup' ) {
				BackWPup_Admin::message( sprintf( __( 'This is not a BackWPup XML file', 'backwpup' ),
					esc_html( $import_xml['version'] ) ),
					true );

				return $wizard_settings;
			}
		}

		if ( isset( $wizard_settings['wizard']['step'] ) && $wizard_settings['wizard']['step'] === 'SELECT' ) {

			$wizard_settings['select']['import_config'] = ! empty( $_POST['import_config'] );

			if ( is_array( $_POST['importtype'] ) ) {
				$wizard_settings['select']['importtype'] = $_POST['importtype'];
			}
		}

		return $wizard_settings;
	}

	public function execute( array $wizard_settings ) {

		if ( ! empty( $wizard_settings['file']['file'] ) ) {
			$import_xml = simplexml_load_file( $wizard_settings['file']['file'] );
		}

		if ( ! $import_xml ) {
			return;
		}

		foreach ( $wizard_settings['select']['importtype'] as $id => $type ) {
			if ( $type === 'not' || empty( $type ) ) {
				continue;
			}

			foreach ( $import_xml->job as $job ) {
				if ( (int) $job->jobid !== $id ) {
					continue;
				}
				foreach ( $job as $key => $option ) {
					$import[ $id ][ $key ] = maybe_unserialize( (string) $option );
				}
				break;
			}

			if ( $type === 'append' ) {
				$newjobid = BackWPup_Option::get_job_ids();
				sort( $newjobid );
				$import[ $id ]['jobid'] = end( $newjobid ) + 1;
			}

			$import[ $id ]['activetype'] = '';
			if ( isset( $import[ $id ]['archivename'] ) ) {
				$import[ $id ]['archivename'] = BackWPup_Option::normalize_archive_name(
					$import[ $id ]['archivename'],
					$import[ $id ]['jobid'],
					false
				);
			}

			unset(
				$import[ $id ]['cronnextrun'],
				$import[ $id ]['starttime'],
				$import[ $id ]['logfile'],
				$import[ $id ]['lastrun'],
				$import[ $id ]['lastruntime'],
				$import[ $id ]['lastbackupdownloadurl']
			);

			if ( isset( $import[ $id ]['archiveformat'] ) && $import[ $id ]['archiveformat'] === '.tar.bz2' ) {
				$import[ $id ]['archiveformat'] = '.tar.gz';
			}
			if ( isset( $import[ $id ]['pluginlistfilecompression'] ) && $import[ $id ]['pluginlistfilecompression'] === '.bz2' ) {
				$import[ $id ]['pluginlistfilecompression'] = '.gz';
			}
			if ( isset( $import[ $id ]['wpexportfilecompression'] ) && $import[ $id ]['wpexportfilecompression'] === '.bz2' ) {
				$import[ $id ]['wpexportfilecompression'] = '.gz';
			}

			foreach ( $import[ $id ] as $jobname => $jobvalue ) {
				BackWPup_Option::update( $import[ $id ]['jobid'], $jobname, $jobvalue );
			}

			if ( is_file( $wizard_settings['file']['file'] ) ) {
				unlink( $wizard_settings['file']['file'] );
			}

			printf(
				'<div class="updated below-h2"><p>%s</p></div>',
				esc_html(
					sprintf(
						__( 'Job %1$s with id %2$d imported', 'backwpup' ),
						$import[ $id ]['name'],
						$import[ $id ]['jobid']
					)
				)
			);
		}

		if ( $wizard_settings['select']['import_config'] ) {
			foreach ( (array) $import_xml->config as $key => $option ) {
				update_site_option( 'backwpup_cfg_' . $key, maybe_unserialize( (string) $option ) );
			}

			printf(
				'<div class="updated below-h2"><p>%s</p></div>',
				esc_html__( 'BackWPup config imported', 'backwpup' )
			);
		}
	}

	public function cancel( array $wizard_settings ) {

		//delete xml file
		if ( isset( $wizard_settings['file']['file'] ) && is_file( $wizard_settings['file']['file'] ) ) {
			unlink( $wizard_settings['file']['file'] );
		}
	}
}
