<?php

class BackWPup_Pro_Wizard_Job extends BackWPup_Pro_Wizards {

	const ID = 'JOB';
	const CAPABILITY = 'backwpup_jobs_edit';

	private $destinations = array();

	public function __construct( $version, $destinations ) {

		$this->destinations = $destinations;
		$this->info = array(
			'ID' => self::ID,
			'name' => esc_html__( 'Create a job', 'backwpup' ),
			'description' => esc_html__( 'Choose a job', 'backwpup' ),
			'URI' => esc_html__( 'http://backwpup.com', 'backwpup' ),
			'author' => 'Inpsyde GmbH',
			'authorURI' => esc_html__( 'http://inpsyde.com', 'backwpup' ),
			'version' => $version,
			'cap' => self::CAPABILITY,
		);
	}

	public function get_steps( array $wizard_settings ) {

		$job_types = BackWPup::get_job_types();

		//generate steps
		$steps = array();
		if ( ! in_array( 'JOBTYPES', $wizard_settings['wizard']['pre_config']['hide_steps'], true ) ) {
			$steps[] = array(
				'id' => 'JOBTYPES',
				'name' => esc_html__( 'Job Types', 'backwpup' ),
				'description' => esc_html__( 'Select a task for your job.', 'backwpup' ),
			);
		}
		// generate job type steps
		$job_crates_file = false;
		foreach ( $job_types as $id => $type ) {
			if ( in_array( $id, $wizard_settings['job_settings']['type'], true ) ) {
				if ( ! in_array( 'JOBTYPE-' . $id, $wizard_settings['wizard']['pre_config']['hide_steps'], true ) ) {
					$steps[] = array(
						'id' => 'JOBTYPE-' . $id,
						'name' => $type->info['name'],
						'description' => $type->info['description'],
					);
				}
				if ( ! $job_crates_file ) {
					$job_crates_file = $type->creates_file();
				}
			}
		}
		//steps when job creates files
		if ( $job_crates_file ) {
			if ( ! in_array( 'ARCHIVE', $wizard_settings['wizard']['pre_config']['hide_steps'], true ) ) {
				$steps[] = array(
					'id' => 'ARCHIVE',
					'name' => esc_html__( 'Archive Settings', 'backwpup' ),
					'description' => esc_html__( 'Settings for the Backup Archive', 'backwpup' ),
				);
			}
			if ( ! in_array( 'DESTINATIONS', $wizard_settings['wizard']['pre_config']['hide_steps'], true ) ) {
				$steps[] = array(
					'id' => 'DESTINATIONS',
					'name' => esc_html__( 'Destinations', 'backwpup' ),
					'description' => esc_html__( 'Where would you like to store the backup file?',
						'backwpup' ),
					'create' => false,
				);
			}
			// generate destinations
			if ( ! empty( $wizard_settings['job_settings']['destinations'] ) ) {
				foreach ( $this->destinations as $id => $dest ) {
					if ( in_array( $id,
							$wizard_settings['job_settings']['destinations'],
							true ) && ! in_array( 'DEST-' . $id,
							$wizard_settings['wizard']['pre_config']['hide_steps'],
							true ) ) {
						$steps[] = array(
							'id' => 'DEST-' . $id,
							'name' => $dest['info']['name'],
							'description' => $dest['info']['description'],
						);
					}
				}
			}
		}
		if ( ! in_array( 'SCHEDULE', $wizard_settings['wizard']['pre_config']['hide_steps'], true ) ) {
			$steps[] = array(
				'id' => 'SCHEDULE',
				'name' => esc_html__( 'Scheduling', 'backwpup' ),
				'description' => esc_html__( 'When would you like to start the job?', 'backwpup' ),
			);
		}

		return $steps;
	}

	public function initiate( array $wizard_settings ) {

		// get job settings if a existing job opened
		if ( empty( $wizard_settings['job_settings'] ) ) {
			$wizard_settings['job_settings'] = BackWPup_Option::defaults_job();
			if ( empty( $wizard_settings['wizard']['pre_config'] ) ) {
				$wizard_settings['wizard']['pre_config'] = $this->get_pre_configurations( 'all' );
			}
			$wizard_settings['job_settings'] = array_merge( $wizard_settings['job_settings'],
				$wizard_settings['wizard']['pre_config']['job_settings'] );
		}

		return $wizard_settings;
	}

	public function admin_print_styles( array $wizard_settings ) {

		//add css for all other steps
		if ( strstr( $wizard_settings['wizard']['step'], 'DEST-' ) ) {
			$dests_object = BackWPup::get_destination( str_replace( 'DEST-', '', $wizard_settings['wizard']['step'] ) );
			$dests_object->wizard_admin_print_styles();
		} elseif ( strstr( $wizard_settings['wizard']['step'], 'JOBTYPE-' ) ) {
			$job_type = BackWPup::get_job_types();
			$id = strtoupper( str_replace( 'JOBTYPE-', '', $wizard_settings['wizard']['step'] ) );
			$job_type[ $id ]->wizard_admin_print_styles();
		}

	}

	public function admin_print_scripts( array $wizard_settings ) {

		//add js for the first step
		if ( $wizard_settings['wizard']['step'] == 'JOB' ) {
			if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
				wp_enqueue_script( 'backwpuptabjob',
					BackWPup::get_plugin_data( 'URL' ) . '/assets/js/page_edit_tab_job.js',
					array( 'jquery' ),
					time(),
					true );
			} else {
				wp_enqueue_script( 'backwpuptabjob',
					BackWPup::get_plugin_data( 'URL' ) . '/assets/js/page_edit_tab_job.min.js',
					array( 'jquery' ),
					BackWPup::get_plugin_data( 'Version' ),
					true );
			}
		} //add js for all other steps
		elseif ( strstr( $wizard_settings['wizard']['step'], 'DEST-' ) ) {
			$dests_object = BackWPup::get_destination( str_replace( 'DEST-', '', $wizard_settings['wizard']['step'] ) );
			$dests_object->wizard_admin_print_scripts();
		} elseif ( strstr( $wizard_settings['wizard']['step'], 'JOBTYPE-' ) ) {
			$job_type = BackWPup::get_job_types();
			$id = strtoupper( str_replace( 'JOBTYPE-', '', $wizard_settings['wizard']['step'] ) );
			$job_type[ $id ]->wizard_admin_print_scripts();
		}
	}

	public function inline_js( array $wizard_settings ) {

		if ( $wizard_settings['wizard']['step'] == 'ARCHIVE' ) {
			$this->inline_js_archive();
		}
		if ( $wizard_settings['wizard']['step'] == 'SCHEDULE' ) {
			$this->inline_js_schedule();
		}

		// add inline js
		if ( strstr( $wizard_settings['wizard']['step'], 'DEST-' ) ) {
			$dests_object = BackWPup::get_destination( str_replace( 'DEST-', '', $wizard_settings['wizard']['step'] ) );
			$dests_object->wizard_inline_js();
		}
		if ( strstr( $wizard_settings['wizard']['step'], 'JOBTYPE-' ) ) {
			$job_types = BackWPup::get_job_types();
			$id = strtoupper( str_replace( 'JOBTYPE-', '', $wizard_settings['wizard']['step'] ) );
			$job_types[ $id ]->wizard_inline_js();
		}
	}

	public function save( array $wizard_settings ) {

		//call default wizard saves
		if ( $wizard_settings['wizard']['step'] == 'JOBTYPES' ) {
			$wizard_settings['job_settings'] = $this->save_jobtypes( $wizard_settings['job_settings'] );
		} elseif ( $wizard_settings['wizard']['step'] == 'SCHEDULE' ) {
			$wizard_settings['job_settings'] = $this->save_schedule( $wizard_settings['job_settings'] );
		} elseif ( $wizard_settings['wizard']['step'] == 'DESTINATIONS' ) {
			$wizard_settings['job_settings'] = $this->save_destinations( $wizard_settings['job_settings'] );
		} elseif ( $wizard_settings['wizard']['step'] == 'ARCHIVE' ) {
			$wizard_settings['job_settings'] = $this->save_archive( $wizard_settings['job_settings'] );
		} //call wizard saves for destination or jobtypes
		elseif ( strstr( $wizard_settings['wizard']['step'], 'DEST-' ) ) {
			$dests_object = BackWPup::get_destination( str_replace( 'DEST-', '', $wizard_settings['wizard']['step'] ) );
			$wizard_settings['job_settings'] = $dests_object->wizard_save( $wizard_settings['job_settings'] );
		} elseif ( strstr( $wizard_settings['wizard']['step'], 'JOBTYPE-' ) ) {
			$job_types = BackWPup::get_job_types();
			$id = strtoupper( str_replace( 'JOBTYPE-', '', $wizard_settings['wizard']['step'] ) );
			$wizard_settings['job_settings'] = $job_types[ $id ]->wizard_save( $wizard_settings['job_settings'] );
		}

		return $wizard_settings;
	}

	public function page( array $wizard_settings ) {

		//call default wizard pages
		if ( $wizard_settings['wizard']['step'] == 'JOBTYPES' ) {
			$this->page_jobtypes( $wizard_settings['job_settings'] );
		} elseif ( $wizard_settings['wizard']['step'] == 'SCHEDULE' ) {
			$this->page_schedule( $wizard_settings['job_settings'] );
		} elseif ( $wizard_settings['wizard']['step'] == 'DESTINATIONS' ) {
			$this->page_destinations( $wizard_settings['job_settings'] );
		} elseif ( $wizard_settings['wizard']['step'] == 'ARCHIVE' ) {
			$this->page_archive( $wizard_settings['job_settings'] );
		} //call wizard pages for destination or jobtypes
		elseif ( strstr( $wizard_settings['wizard']['step'], 'DEST-' ) ) {
			$dests_object = BackWPup::get_destination( str_replace( 'DEST-', '', $wizard_settings['wizard']['step'] ) );
			$dests_object->wizard_page( $wizard_settings['job_settings'] );
		} elseif ( strstr( $wizard_settings['wizard']['step'], 'JOBTYPE-' ) ) {
			$job_types = BackWPup::get_job_types();
			$id = strtoupper( str_replace( 'JOBTYPE-', '', $wizard_settings['wizard']['step'] ) );
			$job_types[ $id ]->wizard_page( $wizard_settings['job_settings'] );
		}
	}

	public function page_jobtypes( array $job_settings ) {

		$job_types = BackWPup::get_job_types();
		?>
		<table class="form-table">
			<tr>
				<td>
					<h3 class="title"><?php esc_html_e( 'This job is a&#160;&hellip;', 'backwpup' ) ?></h3>
					<p><?php esc_html_e( 'Select one or more tasks for your backup job.', 'backwpup' ); ?></p>
					<fieldset>
						<legend class="screen-reader-text"><span><?php esc_html_e( 'Job tasks', 'backwpup' ) ?></span>
						</legend>
						<?php
						foreach ( $job_types as $id => $typeclass ) {
							$addclass = '';
							if ( $typeclass->creates_file() ) {
								$addclass = ' filetype';
							}
							echo '<label for="jobtype-select-' . strtolower( $id ) . '"><input class="jobtype-select checkbox' . esc_attr( $addclass ) . '" id="jobtype-select-' . strtolower( $id ) . '" type="checkbox"' . checked( true,
									in_array( $id, $job_settings['type'], true ),
									false ) . ' name="type[]" value="' . $id . '" /> ' . esc_html( $typeclass->info['description'] ) . '</label><br />';
						}
						?>
					</fieldset>
				</td>
			</tr>
		</table>
		<?php
	}

	public function page_schedule( array $job_settings ) {

		list( $cronstr['minutes'], $cronstr['hours'], $cronstr['mday'], $cronstr['mon'], $cronstr['wday'] ) = explode( ' ',
			$job_settings['cron'],
			5 );
		if ( strstr( $cronstr['minutes'], '*/' ) ) {
			$minutes = explode( '/', $cronstr['minutes'] );
		} else {
			$minutes = explode( ',', $cronstr['minutes'] );
		}
		if ( strstr( $cronstr['hours'], '*/' ) ) {
			$hours = explode( '/', $cronstr['hours'] );
		} else {
			$hours = explode( ',', $cronstr['hours'] );
		}
		if ( strstr( $cronstr['mday'], '*/' ) ) {
			$mday = explode( '/', $cronstr['mday'] );
		} else {
			$mday = explode( ',', $cronstr['mday'] );
		}
		if ( strstr( $cronstr['mon'], '*/' ) ) {
			$mon = explode( '/', $cronstr['mon'] );
		} else {
			$mon = explode( ',', $cronstr['mon'] );
		}
		if ( strstr( $cronstr['wday'], '*/' ) ) {
			$wday = explode( '/', $cronstr['wday'] );
		} else {
			$wday = explode( ',', $cronstr['wday'] );
		}

		?>
		<table class="form-table">
			<tr>
				<td>
					<h3 class="title"><?php esc_html_e( 'Scheduling', 'backwpup' ); ?></h3>
					<label for="activetype"><input type="checkbox"
					                               id="activetype" <?php checked( $job_settings['activetype'],
							'wpcron',
							true ); ?> name="activetype" value="wpcron"/> <?php esc_html_e( 'Activate scheduling',
							'backwpup' ) ?></label><br/>
				</td>
			</tr>
			<tr>
				<td class="table_planung">
					<h3 class="title hasdests scheduler"><?php esc_html_e( 'Scheduler', 'backwpup' ); ?></h3>
					<table id="wpcronbasic" class="scheduler">
						<tr>
							<th>
								<?php esc_html_e( 'Type', 'backwpup' ); ?>
							</th>
							<th>
								&nbsp;
							</th>
							<th>
								<?php esc_html_e( 'Hour', 'backwpup' ); ?>
							</th>
							<th>
								<?php esc_html_e( 'Minute', 'backwpup' ); ?>
							</th>
						</tr>
						<tr>
							<td><label
									for="idcronbtype-mon"><?php echo '<input class="radio" type="radio"' . checked( true,
											is_numeric( $mday[0] ),
											false ) . ' name="cronbtype" id="idcronbtype-mon" value="mon" /> ' . esc_html__( 'monthly',
											'backwpup' ); ?></label></td>
							<td><select name="moncronmday"><?php for ( $i = 1; $i <= 31; $i ++ ) {
										echo '<option ' . selected( in_array( "$i", $mday, true ),
												true,
												false ) . '  value="' . $i . '" />' . esc_html__( 'on',
												'backwpup' ) . ' ' . $i . '</option>';
									} ?></select></td>
							<td><select name="moncronhours"><?php for ( $i = 0; $i < 24; $i ++ ) {
										echo '<option ' . selected( in_array( "$i", $hours, true ),
												true,
												false ) . '  value="' . $i . '" />' . $i . '</option>';
									} ?></select></td>
							<td><select name="moncronminutes"><?php for ( $i = 0; $i < 60; $i = $i + 10 ) {
										echo '<option ' . selected( in_array( "$i", $minutes, true ),
												true,
												false ) . '  value="' . $i . '" />' . $i . '</option>';
									} ?></select></td>
						</tr>
						<tr>
							<td><label
									for="idcronbtype-week"><?php echo '<input class="radio" type="radio"' . checked( true,
											is_numeric( $wday[0] ),
											false ) . ' name="cronbtype" id="idcronbtype-week" value="week" /> ' . esc_html__( 'weekly',
											'backwpup' ); ?></label></td>
							<td><select name="weekcronwday">
									<?php echo '<option ' . selected( in_array( "0", $wday, true ),
											true,
											false ) . '  value="0" />' . esc_html__( 'Sunday',
											'backwpup' ) . '</option>';
									echo '<option ' . selected( in_array( "1", $wday, true ),
											true,
											false ) . '  value="1" />' . esc_html__( 'Monday',
											'backwpup' ) . '</option>';
									echo '<option ' . selected( in_array( "2", $wday, true ),
											true,
											false ) . '  value="2" />' . esc_html__( 'Tuesday',
											'backwpup' ) . '</option>';
									echo '<option ' . selected( in_array( "3", $wday, true ),
											true,
											false ) . '  value="3" />' . esc_html__( 'Wednesday',
											'backwpup' ) . '</option>';
									echo '<option ' . selected( in_array( "4", $wday, true ),
											true,
											false ) . '  value="4" />' . esc_html__( 'Thursday',
											'backwpup' ) . '</option>';
									echo '<option ' . selected( in_array( "5", $wday, true ),
											true,
											false ) . '  value="5" />' . esc_html__( 'Friday',
											'backwpup' ) . '</option>';
									echo '<option ' . selected( in_array( "6", $wday, true ),
											true,
											false ) . '  value="6" />' . esc_html__( 'Saturday',
											'backwpup' ) . '</option>'; ?>
								</select></td>
							<td><select name="weekcronhours"><?php for ( $i = 0; $i < 24; $i ++ ) {
										echo '<option ' . selected( in_array( "$i", $hours, true ),
												true,
												false ) . '  value="' . $i . '" />' . $i . '</option>';
									} ?></select></td>
							<td><select name="weekcronminutes"><?php for ( $i = 0; $i < 60; $i = $i + 10 ) {
										echo '<option ' . selected( in_array( "$i", $minutes, true ),
												true,
												false ) . '  value="' . $i . '" />' . $i . '</option>';
									} ?></select></td>
						</tr>
						<tr>
							<td><label
									for="idcronbtype-day"><?php echo '<input class="radio" type="radio"' . checked( "**",
											$mday[0] . $wday[0],
											false ) . ' name="cronbtype" id="idcronbtype-day" value="day" /> ' . esc_html__( 'daily',
											'backwpup' ); ?></label></td>
							<td></td>
							<td><select name="daycronhours"><?php for ( $i = 0; $i < 24; $i ++ ) {
										echo '<option ' . selected( in_array( "$i", $hours, true ),
												true,
												false ) . '  value="' . $i . '" />' . $i . '</option>';
									} ?></select></td>
							<td><select name="daycronminutes"><?php for ( $i = 0; $i < 60; $i = $i + 10 ) {
										echo '<option ' . selected( in_array( "$i", $minutes, true ),
												true,
												false ) . '  value="' . $i . '" />' . $i . '</option>';
									} ?></select></td>
						</tr>
						<tr>
							<td><label
									for="idcronbtype-hour"><?php echo '<input class="radio" type="radio"' . checked( "*",
											$hours[0],
											false,
											false ) . ' name="cronbtype" id="idcronbtype-hour" value="hour" /> ' . esc_html__( 'hourly',
											'backwpup' ); ?></label></td>
							<td></td>
							<td></td>
							<td><select name="hourcronminutes"><?php for ( $i = 0; $i < 60; $i = $i + 10 ) {
										echo '<option ' . selected( in_array( "$i", $minutes, true ),
												true,
												false ) . '  value="' . $i . '" />' . $i . '</option>';
									} ?></select></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<?php
	}

	public function page_archive( array $job_settings ) {

		?>
		<table class="form-table">
			<tr>
				<td>
					<h3 class="title hasdests"><?php esc_html_e( 'Backup type', 'backwpup' ); ?></h3>
					<p class="hasdests"></p>
					<fieldset>
						<legend class="screen-reader-text"><?php esc_html_e( 'Backup type', 'backwpup' ) ?></legend>
						<label for="backuptype-sync"><input class="radio"
						                                    type="radio"<?php checked( 'sync',
								$job_settings['backuptype'],
								true ); ?>
						                                    name="backuptype" id="backuptype-sync"
						                                    value="sync"/> <?php esc_html_e( 'Sync file by file to destination',
								'backwpup' ); ?></label><br/>
						<label for="backuptype-archive"><input class="radio"
						                                       type="radio"<?php checked( 'archive',
								$job_settings['backuptype'],
								true ); ?>
						                                       name="backuptype" id="backuptype-archive"
						                                       value="archive"/> <?php esc_html_e( 'Create a backup archive',
								'backwpup' ); ?></label><br/>
					</fieldset>
				</td>
			</tr>

			<tr class="archive">
				<td>
					<h3 class="title hasdests"><?php esc_html_e( 'Select a compression type for the backup archive',
							'backwpup' ) ?></h3>
					<p class="hasdests"></p>
					<fieldset>
						<legend class="screen-reader-text"><?php esc_html_e( 'Archive compression type',
								'backwpup' ) ?></legend>
						<?php
						if ( class_exists( 'ZipArchive' ) ) {
							echo '<label for="idarchiveformat-zip"><input class="radio" type="radio"' . checked( '.zip',
									$job_settings['archiveformat'],
									false ) . ' name="archiveformat" id="idarchiveformat-zip" value=".zip" /> ' . esc_html__( 'Zip',
									'backwpup' ) . '</label><br />';
							echo '<p class="description">' . esc_html__( 'PHP Zip functions will be used if available (memory lees). Else PCLZip Class will used.',
									'backwpup' ) . '</p>';
						} else {
							echo '<label for="idarchiveformat-zip"><input class="radio " type="radio"' . checked( '.zip',
									$job_settings['archiveformat'],
									false ) . ' name="archiveformat" id="idarchiveformat-zip" value=".zip" disabled="disabled" /> ' . esc_html__( 'Zip',
									'backwpup' ) . '</label><br />';
							echo '<p class="description">' . esc_html__( 'Disabled because missing PHP function.',
									'backwpup' ) . '</p>';

						}
						echo '<label for="idarchiveformat-tar"><input class="radio" type="radio"' . checked( '.tar',
								$job_settings['archiveformat'],
								false ) . ' name="archiveformat" id="idarchiveformat-tar" value=".tar" title="' . esc_attr__( 'Tar (fast and memory less) uncompressed',
								'backwpup' ) . '" /> ' . esc_html__( 'Tar', 'backwpup' ) . '</label><br />';
						echo '<p class="description">' . esc_html__( 'Tar (fast and memory less) uncompressed',
								'backwpup' ) . '</p>';

						if ( function_exists( 'gzopen' ) ) {
							echo '<label for="idarchiveformat-targz"><input class="radio" type="radio"' . checked( '.tar.gz',
									$job_settings['archiveformat'],
									false ) . ' name="archiveformat" id="idarchiveformat-targz" value=".tar.gz" title="' . esc_attr__( 'A tared and GZipped archive (fast and memory less)',
									'backwpup' ) . '" /> ' . esc_html__( 'Tar GZip', 'backwpup' ) . '</label><br />';
							echo '<p class="description">' . esc_html__( 'A tared and GZipped archive (fast and memory less)',
									'backwpup' ) . '</p>';
						} else {
							echo '<label for="idarchiveformat-targz"><input class="radio" type="radio "' . checked( '.tar.gz',
									$job_settings['archiveformat'],
									false ) . ' name="archiveformat" id="idarchiveformat-targz" value=".tar.gz" disabled="disabled" /> ' . esc_html__( 'Tar GZip',
									'backwpup' ) . '</label><br />';
							echo '<p class="description">' . esc_html__( 'Disabled because missing PHP function.',
									'backwpup' ) . '</p>';
						}
						echo '<p class="description">' . esc_html__( 'Disabled because missing PHP function.',
								'backwpup' ) . '</p>';
						?>
					</fieldset>
				</td>
			</tr>
		</table>
		<?php
	}

	public function page_destinations( array $job_settings ) {

		?>
		<table class="form-table">
			<tr>
				<td>
					<h3 class="title"><?php esc_html_e( 'Where to store the files', 'backwpup' ) ?></h3>
					<p></p>
					<fieldset>
						<legend class="screen-reader-text"><?php esc_html_e( 'Destinations', 'backwpup' ) ?></legend>
						<?php
						foreach ( $this->destinations as $id => $dest ) {
							if ( $job_settings['backuptype'] == 'archive' || ( $job_settings['backuptype'] == 'sync' && $dest['can_sync'] ) ) {
								echo '<label for="dest-select-' . strtolower( $id ) . '">';
								if ( ! empty( $dest['error'] ) ) {
									echo '<span class="description">' . esc_html( $dest['error'] ) . '</span><br />';
								}
								echo '<input class="checkbox" id="dest-select-' . strtolower( $id ) . '" type="checkbox"' . checked( true,
										in_array( $id, $job_settings['destinations'], true ),
										false ) . ' name="destinations[]" value="' . esc_attr( $id ) . '" ' . disabled( ! empty( $dest['error'] ),
										true,
										false ) . ' /> ' . esc_html( $dest['info']['description'] ) . '</label><br />';
							}
						}
						?>
					</fieldset>
				</td>
			</tr>
		</table>
		<?php
	}

	public function save_jobtypes( array $job_settings ) {

		$job_types = BackWPup::get_job_types();

		if ( isset( $_POST['type'] ) && is_array( $_POST['type'] ) ) {
			foreach ( (array) $_POST['type'] as $typeid ) {
				if ( empty( $job_types[ $typeid ] ) ) {
					unset( $_POST['type'][ $typeid ] );
				}
			}
			if ( is_array( $_POST['type'] ) ) {
				sort( $_POST['type'] );
			}
		} else {
			$_POST['type'] = array();
		}

		$job_settings['type'] = $_POST['type'];

		$creates_file = false;
		foreach ( $job_settings['type'] as $type ) {
			if ( $job_types[ $type ]->creates_file() ) {
				$creates_file = true;
				break;
			}
		}

		if ( ! $creates_file ) {
			$job_settings['destinations'] = array();
		}

		return $job_settings;
	}

	public function save_schedule( array $job_settings ) {

		if ( isset( $_POST['activetype'] ) ) {
			$job_settings['activetype'] = ( $_POST['activetype'] == 'wpcron' ) ? 'wpcron' : '';
		} else {
			$job_settings['activetype'] = '';
		}
		$job_settings['cronselect'] = 'basic';
		if ( $_POST['cronbtype'] === 'mon' ) {
			$job_settings['cron'] = $_POST['moncronminutes'] . ' ' . $_POST['moncronhours'] . ' ' . $_POST['moncronmday'] . ' * *';
		}
		if ( $_POST['cronbtype'] === 'week' ) {
			$job_settings['cron'] = $_POST['weekcronminutes'] . ' ' . $_POST['weekcronhours'] . ' * * ' . $_POST['weekcronwday'];
		}
		if ( $_POST['cronbtype'] === 'day' ) {
			$job_settings['cron'] = $_POST['daycronminutes'] . ' ' . $_POST['daycronhours'] . ' * * *';
		}
		if ( $_POST['cronbtype'] === 'hour' ) {
			$job_settings['cron'] = $_POST['hourcronminutes'] . ' * * * *';
		}

		return $job_settings;
	}

	public function save_destinations( array $job_settings ) {

		if ( isset( $_POST['destinations'] ) && is_array( $_POST['destinations'] ) ) {
			foreach ( (array) $_POST['destinations'] as $dst_id ) {
				if ( empty( $this->destinations[ $dst_id ] ) ) {
					unset( $_POST['destinations'][ $dst_id ] );
				}
			}
			if ( is_array( $_POST['destinations'] ) ) {
				sort( $_POST['destinations'] );
			}
		} else {
			$_POST['destinations'] = array();
		}

		$job_settings['destinations'] = $_POST['destinations'];

		return $job_settings;
	}

	public function save_archive( array $job_settings ) {

		$job_settings['archiveformat'] = $_POST['archiveformat'];
		$job_settings['archivename'] = 'backwpup_%Y-%m-%d_%H-%i-%s_%hash%';
		$job_settings['backuptype'] = $_POST['backuptype'];

		return $job_settings;
	}

	public function inline_js_archive() {

		?>
		<script type="text/javascript">
            jQuery( document ).ready( function ( $ )
            {
                $( 'input[name="backuptype"]' ).change( function ()
                {
                    if ( $( this ).val() == 'sync' ) {
                        $( '.archive' ).hide();
                        $( '.sync' ).show();
                    } else {
                        $( '.archive' ).show();
                        $( '.sync' ).hide();
                    }
                } );

                if ( $( 'input[name="backuptype"]:checked' ).val() == 'sync' ) {
                    $( '.archive' ).hide();
                    $( '.sync' ).show();
                } else {
                    $( '.archive' ).show();
                    $( '.sync' ).hide();
                }
            } );
		</script>
		<?php
	}

	public function inline_js_schedule() {

		?>
		<script type="text/javascript">
            jQuery( document ).ready( function ( $ )
            {
                $( 'input[name="activetype"]' ).change( function ()
                {
                    if ( $( this ).prop( "checked" ) ) {
                        $( '.scheduler' ).show();
                    } else {
                        $( '.scheduler' ).hide();
                    }
                } );

                if ( $( 'input[name="activetype"]' ).prop( "checked" ) ) {
                    $( '.scheduler' ).show();
                } else {
                    $( '.scheduler' ).hide();
                }
            } );
		</script>
		<?php
	}

	public function execute( array $wizard_settings ) {

		//get new jobid for new jobs
		$exsitingjobids = BackWPup_Option::get_job_ids();
		sort( $exsitingjobids );
		$wizard_settings['job_settings']['jobid'] = end( $exsitingjobids ) + 1;

		// set job name
		$wizard_settings['job_settings']['name'] = sprintf( esc_html__( 'Wizard: %1$s', 'backwpup' ),
			$wizard_settings['wizard']['pre_config']['name'] );

		//some default settings
		$wizard_settings['job_settings']['mailaddresslog'] = sanitize_email( get_bloginfo( 'admin_email' ) );
		$wizard_settings['job_settings']['mailerroronly'] = true;

		//reschedule job
		$cron_next = BackWPup_Cron::cron_next( $wizard_settings['job_settings']['cron'] );
		wp_clear_scheduled_hook( 'backwpup_cron', array( 'id' => $wizard_settings['job_settings']['jobid'] ) );
		if ( $wizard_settings['job_settings']['activetype'] == 'wpcron' ) {
			wp_schedule_single_event( $cron_next,
				'backwpup_cron',
				array( 'id' => $wizard_settings['job_settings']['jobid'] ) );
		}

		// save
		foreach ( $wizard_settings['job_settings'] as $option_name => $option_value ) {
			BackWPup_Option::update( $wizard_settings['job_settings']['jobid'], $option_name, $option_value );
		}

		//text
		echo '<div id="message" class="updated below-h2"><p>' . sprintf( esc_html__( 'New job %s generated.',
				'backwpup' ),
				$wizard_settings['job_settings']['name'] ) . '</p></div>';

	}

	public function get_last_button_name( array $wizard_settings ) {

		return esc_html__( 'Create Job', 'backwpup' );
	}

	public function is_step_by_step( array $wizard_settings ) {

		return true;
	}

	public function get_pre_configurations( $id = null ) {

		global $wpdb;
		/* @var wpdb $wpdb */

		//pre config for Database backup
		$pre_configurations['db']['name'] = esc_html__( 'Database Backup and XML Export (Daily)', 'backwpup' );
		$pre_configurations['db']['description'] = esc_html__( 'Database Backup and XML Export (Daily)', 'backwpup' );
		//get tables that should not uses on DB configs
		$dbdumpexclude = array();
		$dbtables = $wpdb->get_results( 'SHOW TABLES FROM `' . DB_NAME . '`', ARRAY_N );
		foreach ( $dbtables as $dbtable ) {
			if ( ! strstr( $dbtable[0], $wpdb->prefix ) ) {
				$dbdumpexclude[] = $dbtable[0];
			}
		}
		$pre_configurations['db']['job_settings'] = array(
			'type' => array( 'DBDUMP', 'WPEXP' ),
			'dbdumpexclude' => $dbdumpexclude,
			'cron' => '0 1 * * *',
			'activetype' => 'wpcron',
		);
		$pre_configurations['db']['hide_steps'] = array( 'JOBTYPES', 'JOBTYPE-DBDUMP', 'JOBTYPE-WPEXP', 'SCHEDULE' );

		//pre config for Database Check and optimize
		$pre_configurations['dbchop']['name'] = esc_html__( 'Database Check (Weekly)', 'backwpup' );
		$pre_configurations['dbchop']['description'] = esc_html__( 'Database Check (Weekly)', 'backwpup' );
		$pre_configurations['dbchop']['job_settings'] = array(
			'type' => array( 'DBCHECK' ),
			'cron' => '30 3 * * 1',
			'activetype' => 'wpcron',
		);
		$pre_configurations['dbchop']['hide_steps'] = array( 'JOBTYPES', 'JOBTYPE-DBCHECK', 'SCHEDULE' );

		//pre config for uploads backup
		$pre_configurations['upfile']['name'] = esc_html__( 'Backup uploads folder', 'backwpup' );
		$pre_configurations['upfile']['description'] = esc_html__( 'Backup uploads folder', 'backwpup' );
		$pre_configurations['upfile']['job_settings'] = array(
			'type' => array( 'FILE' ),
			'backupexcludethumbs' => false,
			'backupspecialfiles' => false,
			'backuproot' => false,
			'backupcontent' => false,
			'backupplugins' => false,
			'backupthemes' => false,
			'backupuploads' => true,
		);
		$pre_configurations['upfile']['hide_steps'] = array( 'JOBTYPES', 'JOBTYPE-FILE' );

		//pre config for all file backup
		$pre_configurations['file']['name'] = esc_html__( 'Backup all files', 'backwpup' );
		$pre_configurations['file']['description'] = esc_html__( 'Backup all files', 'backwpup' );
		$pre_configurations['file']['job_settings'] = array(
			'type' => array( 'FILE' ),
			'backupexcludethumbs' => false,
			'backupspecialfiles' => true,
			'backuproot' => true,
			'backupcontent' => true,
			'backupplugins' => true,
			'backupthemes' => true,
			'backupuploads' => true,
		);
		$pre_configurations['file']['hide_steps'] = array( 'JOBTYPES', 'JOBTYPE-FILE' );

		//pre config for Needed files backup
		$pre_configurations['neddedfile']['name'] = esc_html__( 'Essential files + list of plugins', 'backwpup' );
		$pre_configurations['neddedfile']['description'] = esc_html__( 'Backup essential files and folders, plus a list of installed plugins.',
			'backwpup' );
		$pre_configurations['neddedfile']['job_settings'] = array(
			'type' => array( 'FILE', 'WPPLUGIN' ),
			'backupexcludethumbs' => true,
			'backupspecialfiles' => true,
			'backuproot' => false,
			'backuprootexcludedirs' => array( 'wp-includes', 'wp-admin' ),
			'backupcontent' => true,
			'backupplugins' => false,
			'backupthemes' => true,
			'backupuploads' => true,
		);
		$pre_configurations['neddedfile']['hide_steps'] = array( 'JOBTYPES', 'JOBTYPE-FILE', 'JOBTYPE-WPPLUGIN' );

		//Pre config where all must done self
		$pre_configurations['all']['name'] = esc_html__( 'Custom configuration', 'backwpup' );
		$pre_configurations['all']['description'] = esc_html__( 'Custom configuration', 'backwpup' );
		$pre_configurations['all']['job_settings'] = array();
		$pre_configurations['all']['hide_steps'] = array( 'WPPLUGIN' );

		if ( $id == null ) {
			$pre_configurations_names = array();
			foreach ( $pre_configurations as $id => $values ) {
				$pre_configurations_names[ $id ] = $values['name'];
			}

			return $pre_configurations_names;
		} else {
			return $pre_configurations[ $id ];
		}
	}
}
