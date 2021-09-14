<?php
/**
 *
 */
class BackWPup_Pro_JobType_DBDump extends BackWPup_JobType_DBDump {

	/**
	 * @return array
	 */
	public function option_defaults() {
		global $wpdb;
		/* @var wpdb $wpdb */

		$defaults = array(
			'dbdumpexclude'    => array(), 'dbdumpfile' => sanitize_file_name( DB_NAME ), 'dbdumptype' => 'sql', 'dbdumpfilecompression' => '',
			'dbdumpwpdbsettings' => TRUE, 'dbdumpdbhost' => DB_HOST, 'dbdumpdbname' =>  DB_NAME , 'dbdumpdbuser' => DB_USER, 'dbdumpdbpassword' => BackWPup_Encryption::encrypt( DB_PASSWORD ),
			'dbdumpdbcharset'    => defined( 'DB_CHARSET' ) ? DB_CHARSET : '', 'dbdumpmysqlfolder' => 'mysqldump'
		);
		//get path to mysqldump
		if ( strstr( PHP_OS ,'WIN' ) ) {
			$defaults[ 'dbdumpmysqlfolder' ] = 'mysqldump.exe';
		}
		if ( strtolower( DB_HOST ) === 'localhost' ) {
			$mysqlinfo = $wpdb->get_results( "SHOW VARIABLES" );
			foreach ( $mysqlinfo as $info ) {
				if ( strtolower( $info->Variable_name ) === 'basedir' ) {
					if ( BackWPup_File::is_in_open_basedir( trailingslashit( $info->Value ) . 'bin' ) ) {
						if ( is_executable( trailingslashit( $info->Value ) . 'bin/' . $defaults[ 'dbdumpmysqlfolder' ] ) ) {
							$defaults[ 'dbdumpmysqlfolder' ] = trailingslashit( $info->Value ) . 'bin/' . $defaults[ 'dbdumpmysqlfolder' ];
						}
					}
					break;
				}
			}
		}
		//test other locations
		if ( $defaults[ 'dbdumpmysqlfolder' ] === 'mysqldump.exe' || $defaults[ 'dbdumpmysqlfolder' ] === 'mysqldump' ) {
			$mysqldump_locations = array(
				'/usr/local/bin/',
				'/usr/local/mysql/bin/',
				'/usr/mysql/bin/',
				'/usr/bin/',
				'/opt/local/lib/mysql6/bin/',
				'/opt/local/lib/mysql5/bin/',
				'/opt/local/lib/mysql4/bin/',
				'/xampp/mysql/bin/',
				'/Program Files/xampp/mysql/bin/',
				'/Program Files/MySQL/MySQL Server 6.0/bin/',
				'/Program Files/MySQL/MySQL Server 5.5/bin/',
				'/Program Files/MySQL/MySQL Server 5.4/bin/',
				'/Program Files/MySQL/MySQL Server 5.1/bin/',
				'/Program Files/MySQL/MySQL Server 5.0/bin/',
				'/Program Files/MySQL/MySQL Server 4.1/bin/',
				'/wamp/bin/mysql/mysql5.5.24/bin/',
				'/wamp/bin/mysql/mysql5.5.28/bin/'
			);
			foreach ( $mysqldump_locations as $location ) {
				if ( BackWPup_File::is_in_open_basedir( $location . $defaults[ 'dbdumpmysqlfolder' ] ) ) {
					if ( is_executable( $location . $defaults[ 'dbdumpmysqlfolder' ] ) ) {
						$defaults[ 'dbdumpmysqlfolder' ] = $location . $defaults[ 'dbdumpmysqlfolder' ];
						break;
					}
				}
			}
		}

		//set only wordpress tables as default
		$dbtables = $wpdb->get_results( 'SHOW TABLES FROM `' . DB_NAME . '`', ARRAY_N );
		foreach ( $dbtables as $dbtable ) {
			if ( $wpdb->prefix != substr( $dbtable[ 0 ], 0, strlen( $wpdb->prefix ) ) ) {
				$defaults[ 'dbdumpexclude' ][] = $dbtable[ 0 ];
			}
		}

		return $defaults;
	}

	/**
	 * @param $job_settings
	 */
	public function wizard_page( array $job_settings ) {

		?>
		<table class="form-table">
			<tr>
				<td>
					<h3 class="title"><?php esc_html_e( 'Settings for database backup', 'backwpup' ) ?></h3>
					<p></p>
					<fieldset>
						<input class="checkbox" value="1" id="iddbdumpwpony"
							   type="checkbox" <?php checked( empty( $job_settings[ 'dbdumpexclude' ] ), FALSE ); ?>
							   name="dbdumpwpony" /> <?php esc_html_e( 'Backup only WordPress Database tables', 'backwpup' ); ?>
					</fieldset>
				</td>
			</tr>
		</table>
		<?php
	}

	/**
	 * @param $jobid
	 */
	public function edit_tab( $jobid ) {
		global $wpdb;

		?>
    <input name="dbdumpwpony" type="hidden" value="1" />
    <h3 class="title"><?php esc_html_e( 'Settings for database backup', 'backwpup' ) ?></h3>
    <p></p>
    <table class="form-table">
        <tr>
            <th scope="row"><?php esc_html_e( 'Database connection', 'backwpup' ); ?></th>
            <td>
                <label for="dbdumpwpdbsettings"><input class="checkbox"
                       type="checkbox"<?php checked( BackWPup_Option::get( $jobid, 'dbdumpwpdbsettings' ), TRUE, TRUE );?>
                       id="dbdumpwpdbsettings" name="dbdumpwpdbsettings" value="1"/> <?php esc_html_e( 'Use WordPress database connection.', 'backwpup' ); ?></label>
                <br/>
                <table id="dbconnection"<?php if ( BackWPup_Option::get( $jobid, 'dbdumpwpdbsettings' ) ) echo ' style="display:none;"';?>>
                    <tr><td>
                        <label for="dbdumpdbhost"><?php esc_html_e( 'Host:', 'backwpup' );?><br/>
                        <input class="text" type="text" id="dbdumpdbhost" name="dbdumpdbhost" autocomplete="off"
                               value="<?php echo esc_attr( BackWPup_Option::get( $jobid, 'dbdumpdbhost' ) );?>" /></label><br/>
                        <label for="dbdumpdbuser"><?php esc_html_e( 'User:', 'backwpup' );?><br/>
                        <input class="text" type="text" id="dbdumpdbuser" name="dbdumpdbuser" autocomplete="off"
                               value="<?php echo esc_attr( BackWPup_Option::get( $jobid, 'dbdumpdbuser' ) );?>" /></label><br/>
                        <label for="dbdumpdbpassword"><?php esc_html_e( 'Password:', 'backwpup' );?><br/>
                        <input class="text" type="password" id="dbdumpdbpassword" name="dbdumpdbpassword" autocomplete="off"
                               value="<?php echo esc_attr( BackWPup_Encryption::decrypt( BackWPup_Option::get( $jobid, 'dbdumpdbpassword' ) ) );?>" /></label>
                    </td><td>
                        <label for="dbdumpdbcharset"><?php esc_html_e( 'Charset:', 'backwpup' );?></label><br/>
                        <select id="dbdumpdbcharset" name="dbdumpdbcharset">
							<?php
							$colations = $wpdb->get_results( 'SHOW CHARACTER SET', ARRAY_A );
							foreach ( $colations as $colation ) {
								echo '<option value="' . esc_attr( $colation[ 'Charset' ] ) . '" ' . selected( BackWPup_Option::get( $jobid, 'dbdumpdbcharset' ), $colation[ 'Charset' ] ) . ' title="' . esc_attr($colation[ 'Description' ]) . '">' . esc_html($colation[ 'Charset' ]) . '</option>';
							}
							?>
                        </select>
                        <br/>
                        <input type="hidden" name="dbselected" value="<?php echo esc_attr( BackWPup_Option::get( $jobid, 'dbdumpdbname' ) )?>" />
						<?php esc_html_e( 'Database:', 'backwpup' );?><br/>
						<?php
						$this->edit_ajax( array(
											   'dbselected' => BackWPup_Option::get( $jobid, 'dbdumpdbname' ), 'dbuser' => BackWPup_Option::get( $jobid, 'dbdumpdbuser' ),
											   'dbpassword' => BackWPup_Option::get( $jobid, 'dbdumpdbpassword' ), 'dbhost' => BackWPup_Option::get( $jobid, 'dbdumpdbhost' ),
											   'dbcharset'  => BackWPup_Option::get( $jobid, 'dbdumpdbcharset' ), 'action2' => 'databases', 'wpdbsettings' => BackWPup_Option::get( $jobid, 'dbdumpwpdbsettings' )
										  ) );
						?>
                    </td></tr>
                </table>
            </td>
        </tr>
        <tr>
            <th scope="row"><?php esc_html_e( 'Tables to backup', 'backwpup' ); ?></th>
            <td>
                <input type="button" class="button-secondary" id="dball" value="<?php esc_html_e( 'all', 'backwpup' ); ?>">&nbsp;
                <input type="button" class="button-secondary" id="dbnone" value="<?php esc_html_e( 'none', 'backwpup' ); ?>">&nbsp;
                <input type="button" class="button-secondary" id="dbwp" value="<?php echo esc_attr( $wpdb->prefix ); ?>">
				<?php
					$this->edit_ajax( array(
										   'dbname'     => BackWPup_Option::get( $jobid, 'dbdumpdbname' ), 'dbuser' => BackWPup_Option::get( $jobid, 'dbdumpdbuser' ),
										   'dbpassword' => BackWPup_Option::get( $jobid, 'dbdumpdbpassword' ), 'dbhost' => BackWPup_Option::get( $jobid, 'dbdumpdbhost' ),
										   'dbcharset'  => BackWPup_Option::get( $jobid, 'dbdumpdbcharset' ), 'jobid' => $jobid, 'wpdbsettings' => BackWPup_Option::get( $jobid, 'dbdumpwpdbsettings' ), 'action2' => 'tables'
									  ) );
				?>
            </td>
        </tr>
        <tr>
            <th scope="row"><?php esc_html_e( 'Database Backup type', 'backwpup' ) ?></th>
            <td>
				<fieldset>
					<?php
					echo '<label for="iddbdumptype-sql"><input class="radio" type="radio"' . checked( 'sql', BackWPup_Option::get( $jobid, 'dbdumptype' ), FALSE ) . ' name="dbdumptype" id="iddbdumptype-sql" value="sql" /> ' . esc_html__( 'SQL File (with mysqli)', 'backwpup' ) . '</label><br />';
					echo '<label for="iddbdumptype-syssql"><input class="radio" type="radio"' . checked( 'syssql', BackWPup_Option::get( $jobid, 'dbdumptype' ), FALSE ) . ' name="dbdumptype" id="iddbdumptype-syssql" value="syssql"' . disabled( BackWPup_Job::mysqldump_installed( BackWPup_Option::get( $jobid, 'dbdumpmysqlfolder' ) ), FALSE, FALSE ) .' /> ' . esc_html__( 'SQL File (with mysqldump) ', 'backwpup' ) . BackWPup_Job::mysqldump_error_message( BackWPup_Option::get( $jobid, 'dbdumpmysqlfolder' ) ) . '</label><br />';
					echo '<label for="iddbdumptype-xml"><input class="radio" type="radio"' . checked( 'xml', BackWPup_Option::get( $jobid, 'dbdumptype' ), FALSE ) . ' name="dbdumptype" id="iddbdumptype-xml" value="xml" /> ' . esc_html__( 'XML File (phpMyAdmin schema)', 'backwpup' ) . '</label><br />';
					?>
				</fieldset>
            </td>
        </tr>
        <tr id="trdbdumpmysqlfolder">
            <th scope="row"><label for="dbdumpmysqlfolder"><?php _e( 'Path to <em>mysqldump</em> file', 'backwpup' ) ?></label></th>
            <td>
                <input
	                name="dbdumpmysqlfolder"
	                id="dbdumpmysqlfolder"
	                type="text"
	                value="<?php echo esc_attr( BackWPup_Option::get( $jobid, 'dbdumpmysqlfolder' ) ); ?>"
	                class="regular-text code"<?php disabled( BackWPup_Job::is_exec(), FALSE ); ?> />
	            <p class="description">
		            <?php _e( 'Path to mysqldump file, so a backup can be made with it. If it is correct and <em>shell_exec</em> is active, the backup will be generated with a system command. If <em>shell_exec</em> ist not active, this is disabled', 'backwpup'); ?>
	            </p>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="dbdumpfile"><?php esc_html_e( 'Backup file name', 'backwpup' ); ?></label></th>
            <td>
                <input name="dbdumpfile" type="text" id="dbdumpfile" value="<?php echo esc_attr( BackWPup_Option::get( $jobid, 'dbdumpfile' ) ); ?>" class="medium-text code" /> .sql
            </td>
        </tr>
        <tr>
            <th scope="row"><?php esc_html_e( 'Backup file compression', 'backwpup' ) ?></th>
            <td>
				<fieldset>
					<?php
					echo '<label for="iddbdumpfilecompression"><input class="radio" type="radio"' . checked( '', BackWPup_Option::get( $jobid, 'dbdumpfilecompression' ), FALSE ) . ' name="dbdumpfilecompression" id="iddbdumpfilecompression" value="" /> ' . esc_html__( 'none', 'backwpup' ). '</label><br />';
					if ( function_exists( 'gzopen' ) ) {
						echo '<label for="iddbdumpfilecompression-gz"><input class="radio" type="radio"' . checked( '.gz', BackWPup_Option::get( $jobid, 'dbdumpfilecompression' ), FALSE ) . ' name="dbdumpfilecompression" id="iddbdumpfilecompression-gz" value=".gz" /> ' . esc_html__( 'GZip', 'backwpup' ). '</label><br />';
					} else {
						echo '<label for="iddbdumpfilecompression-gz"><input class="radio" type="radio"' . checked( '.gz', BackWPup_Option::get( $jobid, 'dbdumpfilecompression' ), FALSE ) . ' name="dbdumpfilecompression" id="iddbdumpfilecompression-gz" value=".gz" disabled="disabled" /> ' . esc_html__( 'GZip', 'backwpup' ). '</label><br />';
					}
					?>
				</fieldset>
            </td>
        </tr>
    </table>
	<?php
	}


	/**
	 * @param $job_settings
	 * @return mixed
	 */
	public function wizard_save( array $job_settings ) {
		global $wpdb;
		/* @var wpdb $wpdb */

		$job_settings[ 'dbdumpexclude' ] = array();
		if ( ! empty( $_POST[ 'dbdumpwpony' ] ) ) {
			$dbtables = $wpdb->get_results( 'SHOW TABLES FROM `' . DB_NAME . '`', ARRAY_N );
			foreach ( $dbtables as $dbtable) {
				if ( ! strstr( $dbtable[ 0 ], $wpdb->prefix ) ) {
					$job_settings[ 'dbdumpexclude' ][] = $dbtable[ 0 ];
				}
			}
		}
		$job_settings[ 'dbdumpwpdbsettings' ] = TRUE;
		$job_settings[ 'dbdumptype' ] = 'sql';
		$job_settings[ 'dbdumpfilecompression' ] = '';
		$job_settings[ 'dbdumpfile' ] = sanitize_file_name( DB_NAME );

		return $job_settings;
	}

	/**
	 * @param $id
	 */
	public function edit_form_post_save( $id ) {
		global $wpdb;
		/* @var wpdb $wpdb */

		BackWPup_Option::update( $id, 'dbdumpwpdbsettings', ! empty( $_POST[ 'dbdumpwpdbsettings' ] ) );
		BackWPup_Option::update( $id, 'dbdumptype', ( ! isset( $_POST[ 'dbdumptype' ] ) ) ? 'sql' : trim( $_POST[ 'dbdumptype' ] ) );
		if ( $_POST[ 'dbdumpfilecompression' ] === '' || $_POST[ 'dbdumpfilecompression' ] === '.gz' ) {
			BackWPup_Option::update( $id, 'dbdumpfilecompression', $_POST[ 'dbdumpfilecompression' ] );
		}
		BackWPup_Option::update( $id, 'dbdumpfile', BackWPup_Job::sanitize_file_name( $_POST[ 'dbdumpfile' ] ) );
		BackWPup_Option::update( $id, 'dbdumpdbhost', $_POST[ 'dbdumpdbhost' ] );
		BackWPup_Option::update( $id, 'dbdumpdbuser', $_POST[ 'dbdumpdbuser' ] );
		BackWPup_Option::update( $id, 'dbdumpdbpassword', BackWPup_Encryption::encrypt( $_POST[ 'dbdumpdbpassword' ] ) );
		BackWPup_Option::update( $id, 'dbdumpdbname', ( ! isset( $_POST[ 'dbdumpdbname' ] ) ) ? '' : trim( $_POST[ 'dbdumpdbname' ] ) );
		BackWPup_Option::update( $id, 'dbdumpdbcharset', $_POST[ 'dbdumpdbcharset' ] );
		BackWPup_Option::update( $id, 'dbdumpmysqlfolder', str_replace( '\\', '/', $_POST[ 'dbdumpmysqlfolder' ] ) );
		//selected tables
		$dbdumpexclude = array();
		$checked_db_tables = array();
		if ( isset( $_POST[ 'tabledb' ] ) ) {
			foreach ( $_POST[ 'tabledb' ] as $dbtable ) {
				$checked_db_tables[] = rawurldecode( $dbtable );
			}
		}
		if ( ! BackWPup_Option::get( $id, 'dbdumpwpdbsettings' ) ) {
			$mysqli = new mysqli( BackWPup_Option::get( $id, 'dbdumpdbhost' ),
				BackWPup_Option::get( $id, 'dbdumpdbuser' ),
				BackWPup_Encryption::decrypt( BackWPup_Option::get( $id, 'dbdumpdbpassword' ) )
			);
			if ($res  = $mysqli->query( 'SHOW TABLES FROM `' . BackWPup_Option::get( $id, 'dbdumpdbname' ) . '`' ) ) {
				while ( $dbtable = $res->fetch_array( MYSQLI_NUM ) ) {
					if ( ! in_array( $dbtable[ 0 ], $checked_db_tables, true ) ) {
						$dbdumpexclude[] = $dbtable[ 0 ];
					}
				}
				$res->close();
			}
			$mysqli->close();
		}
		else {
			$dbtables = $wpdb->get_results( 'SHOW TABLES FROM `' . DB_NAME . '`', ARRAY_N );
			foreach ( $dbtables as $dbtable ) {
				if ( ! in_array( $dbtable[ 0 ], $checked_db_tables, true ) ) {
					$dbdumpexclude[] = $dbtable[ 0 ];
				}
			}
		}

		BackWPup_Option::update( $id, 'dbdumpexclude', $dbdumpexclude );

	}

	/**
	 * @param BackWPup_Job $job_object
	 * @global wpdb $wpdb
	 * @return bool
	 */
	public function job_run( BackWPup_Job $job_object ) {

		//get and set WordPress db settings for job execution
		if ( $job_object->job[ 'dbdumpwpdbsettings' ] ) {
			$job_object->job[ 'dbdumpdbhost' ] = DB_HOST;
			$job_object->job[ 'dbdumpdbuser' ] = DB_USER;
			$job_object->job[ 'dbdumpdbpassword' ] = BackWPup_Encryption::encrypt( DB_PASSWORD );
			$job_object->job[ 'dbdumpdbname' ]  = DB_NAME;
			$job_object->job[ 'dbdumpdbcharset' ] = defined( 'DB_CHARSET' ) ? DB_CHARSET : '';
			$job_object->job[ 'dbclientflags' ] = defined( 'MYSQL_CLIENT_FLAGS' ) ? MYSQL_CLIENT_FLAGS : 0;
		} else {
			$job_object->job[ 'dbclientflags' ] = 0;
		}
		//Run dump type
		if ( $job_object->job[ 'dbdumptype' ] === 'sql' ) {
			return $this->db_dump( $job_object );
		}
		elseif ( $job_object->job[ 'dbdumptype' ] === 'syssql') {
			return $this->db_mysqldump_system( $job_object );
		}
		elseif ( $job_object->job[ 'dbdumptype' ] === 'xml' ) {
			return $this->db_dump_xml( $job_object );
		}

		return false;
	}

	/**
	 * Load js scripts
	 */
	public function admin_print_scripts() {

		if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
			wp_enqueue_script( 'backwpupjobtypedbdump', BackWPup::get_plugin_data( 'URL' ) . '/assets/js/page_edit_jobtype_dbdump.js', array('jquery'), time(), TRUE );
		} else {
			wp_enqueue_script( 'backwpupjobtypedbdump', BackWPup::get_plugin_data( 'URL' ) . '/assets/js/page_edit_jobtype_dbdump.min.js', array('jquery'), BackWPup::get_plugin_data( 'Version' ), TRUE );
		}
	}

	/**
	 * @param array $args
	 */
	public function edit_ajax( $args = array() ) {
		global $wpdb;
		/* @var wpdb $wpdb */

		@set_time_limit( 300 );
		$ajax = FALSE;

		if ( isset($_POST[ 'dbname' ]) || isset( $_POST[ 'dbhost' ]) || isset( $_POST[ 'dbuser' ]) || isset( $_POST[ 'dbpassword' ]) ) {
			$args = array();
			$ajax = TRUE;
			if ( ! current_user_can( 'backwpup_jobs_edit' ) ) {
				wp_die( -1 );
			}
			check_ajax_referer( 'backwpup_ajax_nonce' );
			if ( $_POST[ 'action2' ] === 'tables' ) {
				$args[ 'dbname' ]		= $_POST[ 'dbname' ];
				$args[ 'dbhost' ]       = $_POST[ 'dbhost' ];
				$args[ 'dbuser' ]       = $_POST[ 'dbuser' ];
				$args[ 'dbpassword' ]   = $_POST[ 'dbpassword' ];
				$args[ 'jobid' ]        = (int)$_POST[ 'jobid' ];
				$args[ 'wpdbsettings' ] = ! empty( $_POST[ 'wpdbsettings' ] );
				$args[ 'action2' ]      = 'tables';
			}
			elseif ( $_POST[ 'action2' ] === 'databases' ) {
				$args[ 'dbselected' ] = ( isset( $_POST[ 'dbname' ] ) ) ? $_POST[ 'dbname' ] : '';
				$args[ 'dbhost' ]     = $_POST[ 'dbhost' ];
				$args[ 'dbuser' ]     = $_POST[ 'dbuser' ];
				$args[ 'dbpassword' ] = $_POST[ 'dbpassword' ];
				$args[ 'wpdbsettings' ] = ! empty( $_POST[ 'wpdbsettings' ] );
				$args[ 'action2' ]    = 'databases';
			}
		}
		if ( $args[ 'action2' ] === 'tables' ) {
			$tables = array();
			$num_rows = 0;
			if ( ! empty( $args[ 'wpdbsettings' ] ) ) {
				$tables = $wpdb->get_results( 'SHOW FULL TABLES FROM `' . DB_NAME . '`', ARRAY_N );
				$num_rows = count( $tables );
			} else {
				$mysqli = @new mysqli( $args[ 'dbhost' ] , $args[ 'dbuser' ], BackWPup_Encryption::decrypt( $args[ 'dbpassword' ] ) );
				if ( $mysqli->connect_error || empty( $args[ 'dbname' ] ) ) {
					echo '<table id="dbtables"><tr><td></td></tr></table>';
					if ( $ajax ) {
						die();
					}else {
						return;
					}
				}
				if ($res = $mysqli->query( 'SHOW FULL TABLES FROM `' . $args[ 'dbname' ] . '`') ) {
					$num_rows = $res->num_rows;
					while( $table = $res->fetch_array( MYSQLI_NUM ) ) {
						$tables[] = $table;
					}
					$res->close();
				}
				$mysqli->close();
			}

			echo '<fieldset id="dbtables"><div style="width: 30%; float:left; min-width: 250px; margin-right: 10px;">';
			$next_row = ceil( $num_rows / 3 );
			$counter = 0;
			foreach ( $tables as $table ) {
				$tabletype = '';
				if ( $table[ 1 ] != 'BASE TABLE' )
					$tabletype = ' <i>(' . strtolower( $table[ 1 ] ) . ')</i>';
				echo '<label for="idtabledb-' . rawurlencode( $table[ 0 ] ) . '""><input class="checkbox" type="checkbox"' . checked( ! in_array( $table[ 0 ], BackWPup_Option::get( $args[ 'jobid' ], 'dbdumpexclude' ), true ), TRUE, FALSE ) . ' name="tabledb[]" id="idtabledb-' . rawurlencode( $table[ 0 ] ) . '" value="' . rawurlencode( $table[ 0 ] ) . '"/> ' . $table[ 0 ] . $tabletype . '</label><br />';
				$counter++;
				if ($next_row <= $counter) {
					echo '</div><div style="width: 30%; float:left; min-width: 250px; margin-right: 10px;">';
					$counter = 0;
				}
			}
			echo '</div></fieldset>';
			if ( $ajax ) {
				die();
			}else {
				return;
			}
		}
		elseif ( $args[ 'action2' ] === 'databases' ) {

			if ( ! empty( $args[ 'wpdbsettings' ] ) ) {
				echo '<input id="dbdumpdbname" name="dbdumpdbname" value="' . esc_attr( DB_NAME ) . '">';
				if ( $ajax ) {
					die();
				} else {
					return;
				}
			}

			$mysqli = @new mysqli( $args[ 'dbhost' ] , $args[ 'dbuser' ], BackWPup_Encryption::decrypt( $args[ 'dbpassword' ] ) );
			if ( $mysqli->connect_error ) {
				echo '<span id="dbdumpdbname" class="bwu-message-error">' . $mysqli->connect_error . '</span>';
				if ( $ajax )
					die();
				else
					return;
			}
			if ( $res = $mysqli->query( 'SHOW DATABASES') ) {
				echo '<select id="dbdumpdbname" name="dbdumpdbname">';
				while ( $db = $res->fetch_array() ) {
					echo '<option' . selected( $db[ 'Database' ], $args[ 'dbselected' ], FALSE ) . ' value="' . esc_attr($db[ 'Database' ]) . '">' . esc_html( $db[ 'Database' ] ). '</option>';
				}
				echo '</select>';
				$res->close();
			}
			$mysqli->close();
			if ( $ajax ) {
				die();
			}else {
				return;
			}
		}
	}

	/**
	 * Dumps the Database
	 *
	 * @param $job_object BackWPup_Job
	 *
	 * @return bool
	 */
	private function db_dump( BackWPup_Job $job_object ) {

		$job_object->substeps_todo = 1;

		if ( $job_object->steps_data[ $job_object->step_working ]['SAVE_STEP_TRY'] != $job_object->steps_data[ $job_object->step_working ][ 'STEP_TRY' ] )
			$job_object->log( sprintf( __( '%d. Try to backup database&#160;&hellip;', 'backwpup' ), $job_object->steps_data[ $job_object->step_working ][ 'STEP_TRY' ] ) );

		//build filename
		if ( empty( $job_object->steps_data[ $job_object->step_working ][ 'dbdumpfile' ] ) )
			$job_object->steps_data[ $job_object->step_working ][ 'dbdumpfile' ] = $job_object->generate_filename( $job_object->job[ 'dbdumpfile' ], 'sql' ) . $job_object->job[ 'dbdumpfilecompression' ];

		try {

			//Connect to Database
			$sql_dump = new BackWPup_MySQLDump( array(
													'dumpfile'	  => BackWPup::get_plugin_data( 'TEMP' ) . $job_object->steps_data[ $job_object->step_working ][ 'dbdumpfile' ],
													'dbhost' 	  => $job_object->job[ 'dbdumpdbhost' ],
													'dbname' 	  => $job_object->job[ 'dbdumpdbname' ],
													'dbuser' 	  => $job_object->job[ 'dbdumpdbuser' ],
													'dbpassword'  => BackWPup_Encryption::decrypt( $job_object->job[ 'dbdumpdbpassword' ] ),
													'dbcharset'   => $job_object->job[ 'dbdumpdbcharset' ],
													'dbclientflags' => $job_object->job[ 'dbclientflags' ]
										   		) );

			if ( $job_object->steps_data[ $job_object->step_working ]['SAVE_STEP_TRY'] != $job_object->steps_data[ $job_object->step_working ][ 'STEP_TRY' ] ) {
				$job_object->log( sprintf( __( 'Connected to database %1$s on %2$s', 'backwpup' ), $job_object->job[ 'dbdumpdbname' ], $job_object->job[ 'dbdumpdbhost' ] ) );
			}


			//Exclude Tables
			foreach ( $sql_dump->tables_to_dump as $key => $table ) {
				if ( in_array( $table, $job_object->job[ 'dbdumpexclude' ], true ) ) {
					unset( $sql_dump->tables_to_dump[ $key ] );
				}
			}

			//set steps must done
			$job_object->substeps_todo = count( $sql_dump->tables_to_dump );

			if ( $job_object->substeps_todo === 0 ) {
				$job_object->log( __( 'No tables to backup.', 'backwpup' ), E_USER_WARNING );
				unset( $sql_dump );

				return TRUE;
			}

			//dump head
			if ( ! isset( $job_object->steps_data[ $job_object->step_working ][ 'is_head' ] ) ) {
				if ( $job_object->job[ 'dbdumpdbhost' ] == $GLOBALS[ 'wpdb' ]->dbhost && $job_object->job[ 'dbdumpdbname' ] === $GLOBALS[ 'wpdb' ]->dbname ) {
					$sql_dump->dump_head( TRUE );
				} else {
					$sql_dump->dump_head();
				}
				$job_object->steps_data[ $job_object->step_working ][ 'is_head' ] = TRUE;
			}
			//dump tables
			$i = 0;
			foreach(  $sql_dump->tables_to_dump as $table ) {
				if ( $i < $job_object->substeps_done ) {
					$i++;
					continue;
				}
				if ( empty( $job_object->steps_data[ $job_object->step_working ][ 'tables' ][ $table ] ) ) {
					$num_records = $sql_dump->dump_table_head( $table );
					$job_object->steps_data[ $job_object->step_working ][ 'tables' ][ $table ] = array( 'start'   => 0,	'length'   => 1000 );
					if ( $job_object->is_debug() ) {
						$job_object->log( sprintf( __( 'Backup database table "%s" with "%s" records', 'backwpup' ), $table, $num_records ) );
					}
				}
				$while = true;
				while ( $while ) {
					$dump_start_time = microtime( TRUE );
					$done_records = $sql_dump->dump_table( $table ,$job_object->steps_data[ $job_object->step_working ][ 'tables' ][ $table ][ 'start' ], $job_object->steps_data[ $job_object->step_working ][ 'tables' ][ $table ][ 'length' ] );
					$dump_time = microtime( TRUE ) - $dump_start_time;
					if ( empty( $dump_time ) )
						$dump_time = 0.01;
					if ( $done_records < $job_object->steps_data[ $job_object->step_working ][ 'tables' ][ $table ][ 'length' ] ) //that is the last chunk
						$while = FALSE;
					$job_object->steps_data[ $job_object->step_working ][ 'tables' ][ $table ][ 'start' ] = $job_object->steps_data[ $job_object->step_working ][ 'tables' ][ $table ][ 'start' ] + $done_records;
					// dump time per record and set next length
					$length = ceil( ( $done_records / $dump_time ) * $job_object->get_restart_time() );
					if ( $length > 25000 || 0 >= $job_object->get_restart_time() )
						$length = 25000;
					if ( $length < 1000 )
						$length = 1000;
					$job_object->steps_data[ $job_object->step_working ][ 'tables' ][ $table ][ 'length' ] =  $length;
					$job_object->do_restart_time();
				}
				$sql_dump->dump_table_footer( $table );
				$job_object->substeps_done++;
				$i++;
				$job_object->update_working_data();
			}
			//dump footer
			$sql_dump->dump_footer();
			unset( $sql_dump );

		} catch ( Exception $e ) {
			$job_object->log( $e->getMessage(), E_USER_ERROR, $e->getFile(), $e->getLine() );
			unset( $sql_dump );
			return FALSE;
		}

		$filesize = filesize( BackWPup::get_plugin_data( 'TEMP' ) . $job_object->steps_data[ $job_object->step_working ][ 'dbdumpfile' ] );

		if ( ! is_file( BackWPup::get_plugin_data( 'TEMP' ) . $job_object->steps_data[ $job_object->step_working ][ 'dbdumpfile' ] ) || $filesize < 1 ) {
			$job_object->log( __( 'MySQL backup file not created', 'backwpup' ), E_USER_ERROR );
			return FALSE;
		} else {
			$job_object->additional_files_to_backup[ ] = BackWPup::get_plugin_data( 'TEMP' ) . $job_object->steps_data[ $job_object->step_working ][ 'dbdumpfile' ];
			$job_object->log( sprintf( __( 'Added database backup "%1$s" with %2$s to backup file list', 'backwpup' ), $job_object->steps_data[ $job_object->step_working ][ 'dbdumpfile' ], size_format( $filesize, 2 ) ) );
		}

		//cleanups
		unset( $job_object->steps_data[ $job_object->step_working ][ 'tables' ] );

		$job_object->log( __( 'Database backup done!', 'backwpup' ) );

		return TRUE;
	}

	/**
	 * Dumps the Database in xml
	 *
	 * @param $job_object BackWPup_Job
	 *
	 * @return bool
	 */
	private function db_mysqldump_system( BackWPup_Job $job_object ) {

		if ( $job_object->steps_data[ $job_object->step_working ]['SAVE_STEP_TRY'] != $job_object->steps_data[ $job_object->step_working ][ 'STEP_TRY' ] ) {
			$job_object->log( sprintf( __( '%d. Try to backup MySQL system&#160;&hellip;', 'backwpup' ), $job_object->steps_data[ $job_object->step_working ][ 'STEP_TRY' ] ) );
			$job_object->substeps_todo = 2;
			$job_object->substeps_done = 0;
		}

		//check can working
		if ( ! $job_object->is_exec() ) {
			$job_object->log( __( 'Executing of system commands not allowed. Please use backup with mysqli.', 'backwpup' ), E_USER_ERROR );
			return FALSE;
		}

		if ( ! BackWPup_File::is_in_open_basedir( $job_object->job[ 'dbdumpmysqlfolder' ] ) ) {
			$job_object->log( sprintf( __( '%s file not in open basedir of PHP.', 'backwpup' ), $job_object->job[ 'dbdumpmysqlfolder' ] ), E_USER_ERROR );
			return FALSE;
		}

		if ( ! is_file( $job_object->job[ 'dbdumpmysqlfolder' ] ) ) {
			$job_object->log( sprintf( __( '%s file not found. Please correct the path for the mysqldump file.', 'backwpup' ), $job_object->job[ 'dbdumpmysqlfolder' ] ), E_USER_ERROR );
			return FALSE;
		}


		//do restart for more time
		$job_object->do_restart();

		if ( $job_object->substeps_done === 0 ) {

			if ( ! isset( $job_object->steps_data[ $job_object->step_working ][ 'TABLES' ] ) || ! is_array( $job_object->steps_data[ $job_object->step_working ][ 'TABLES' ] ) ) {
				$job_object->steps_data[ $job_object->step_working ][ 'TABLES' ] = array();
			}

			//build filename
			if ( empty( $job_object->temp[ 'dbdumpfile' ] ) ) {
				$job_object->temp[ 'dbdumpfile' ] = $job_object->generate_filename( $job_object->job[ 'dbdumpfile' ], 'sql' );
			}

			//check if port or socket in hostname and set port and socket
			$dbport   = NULL;
			$dbsocket = NULL;
			if ( strstr( ':', $job_object->job[ 'dbdumpdbhost' ] ) ) {
				$hostparts = explode( ':', $job_object->job[ 'dbdumpdbhost' ] );
				$job_object->job[ 'dbdumpdbhost' ] = $hostparts[ 0 ];
				if ( is_numeric( $hostparts[ 1 ] ) )
					$dbport = (string) $hostparts[ 1];
				else
					$dbsocket = $hostparts[ 1 ] ;
				if ( isset( $hostparts[ 2 ] ) )
					$dbsocket = $hostparts[ 2 ];
			}

			// Path to the mysqldump executable
			$cmd = escapeshellarg( $job_object->job[ 'dbdumpmysqlfolder' ] );
			// No Create DB command --no-create-db
			$cmd .= ' -n';
			// Add Table locks
			$cmd .= ' --add-locks';
			// Add disable keys --disable-keys
			$cmd .= ' -K';
			// Use extended inserts --extended-insert
			$cmd .= ' -e';
			// Write per data set --quick
			$cmd .= ' -q';
			// execute on errors --force
			$cmd .= ' -f';
			// Add functions and procedures to dump --routines
			$cmd .= ' -R';
			// Make sure binary data is exported properly
			$cmd .= ' --hex-blob';
			// drop table before create
			$cmd .= ' --add-drop-table --add-drop-trigger';
			// set charset
			if ( ! empty( $job_object->job[ 'dbdumpdbcharset' ] ) ) {
				$cmd .= ' --default-character-set=' . $job_object->job[ 'dbdumpdbcharset' ];
			}
			// The file we're saving too --result-file=
			$cmd .= ' -r ' . escapeshellarg( BackWPup::get_plugin_data( 'TEMP' ) . $job_object->temp[ 'dbdumpfile' ] );
			// Username --user=
			$cmd .= ' -u' . $job_object->job[ 'dbdumpdbuser' ];
			// Don't pass the password if it's blank --password=
			$cmd .= ' -p' . BackWPup_Encryption::decrypt( $job_object->job[ 'dbdumpdbpassword' ] );
			// Set the host --host
			$cmd .= ' -h ' . $job_object->job[ 'dbdumpdbhost' ];
			// Set the port if it was set  --port
			if ( ! empty( $dbport ) ) {
				$cmd .= ' -P ' .  escapeshellarg( $dbport );
			}
			// Set the socket if it set  --socket
			if ( ! empty( $dbsocket ) ) {
				$cmd .= ' -S ' . escapeshellarg( $dbsocket );
			}
			// Tables to ignore
			foreach ( $job_object->job[ 'dbdumpexclude' ] as $table )
				$cmd .= ' --ignore-table=' . escapeshellarg( $job_object->job[ 'dbdumpdbname' ] . '.' . $table );
			// The database we're dumping
			$cmd .= ' ' . escapeshellarg( $job_object->job[ 'dbdumpdbname' ] );


			// Store any returned data in warning
			if ( $job_object->is_debug() ) {
				$job_object->log(
					sprintf(
						_x( 'CLI Exec: %s', 'Executed exec() command', 'backwpup' ), str_replace(
						BackWPup_Encryption::decrypt( $job_object->job[ 'dbdumpdbpassword' ] ), '*******', $cmd	)
					)
				); //don't display password
			}
			$output = array();
			$return_var = 0;
			exec( $cmd, $output, $return_var );
			if ( $return_var != 0 ) {
				$return_text = array();
				$return_text[1] = __( 'Usage error.', 'backwpup' ); //EX_USAGE
				$return_text[2] = __( 'MySQL Server Error. This could be an issue with permissions. Try using database backup with mysqli.', 'backwpup' ); //EX_MYSQLERR
				$return_text[3] = __( 'Error during consistency checks.', 'backwpup' ); //EX_CONSCHECK
				$return_text[4] = __( 'Not enough memory.', 'backwpup' ); //EX_EOM
				$return_text[5] = __( 'Error during writing of SQL backup file.', 'backwpup' ); //EX_EOF
				$return_text[6] = __( 'Illegal table', 'backwpup' ); //EX_ILLEGAL_TABLE
				$error_text = '';
				if ( isset( $return_text[ $return_var ] ) ) {
					$error_text = $return_text[ $return_var ];
				}
				$job_object->log( sprintf( __( 'mysqldump returned: (%d) %s', 'backwpup' ), $return_var, $error_text  ), E_USER_ERROR );
				foreach ( $output as $out_line ) {
					$job_object->log( $out_line,  E_USER_ERROR );
				}
				return FALSE;
			}

			$job_object->substeps_done++;
		}

		$filesize = filesize( BackWPup::get_plugin_data( 'TEMP' ) . $job_object->temp[ 'dbdumpfile' ] );

		if ( ! is_file( BackWPup::get_plugin_data( 'TEMP' ) . $job_object->temp[ 'dbdumpfile' ] ) || $filesize < 1 ) {
			$job_object->log( __( 'Can not create mysql backup with mysqldump command', 'backwpup' ), E_USER_ERROR );
			return FALSE;
		}

		//Compress file
		if ( ! empty( $job_object->job[ 'dbdumpfilecompression' ] ) ) {
			$job_object->log( __( 'Compressing file&#160;&hellip;', 'backwpup' ) );
			try {
				$compress = new BackWPup_Create_Archive( BackWPup::get_plugin_data( 'TEMP' ) . $job_object->temp[ 'dbdumpfile' ] . $job_object->job[ 'dbdumpfilecompression' ] );
				if ( $compress->add_file( BackWPup::get_plugin_data( 'TEMP' ) . $job_object->temp[ 'dbdumpfile' ] ) ) {
					unlink( BackWPup::get_plugin_data( 'TEMP' ) . $job_object->temp[ 'dbdumpfile' ] );
					$job_object->temp[ 'dbdumpfile' ] .= $job_object->job[ 'dbdumpfilecompression' ];
					$filesize = filesize( BackWPup::get_plugin_data( 'TEMP' ) . $job_object->temp[ 'dbdumpfile' ] );
					$job_object->log( __( 'Compressing done.', 'backwpup' ) );
				}
				$compress->close();
				unset( $compress );
			} catch ( Exception $e ) {
				$job_object->log( $e->getMessage(), E_USER_ERROR, $e->getFile(), $e->getLine() );
				unset( $compress );
				return FALSE;
			}
		}

		$job_object->substeps_done++;

		//add database file to backup files
		if ( is_readable( BackWPup::get_plugin_data( 'TEMP' ) . $job_object->temp[ 'dbdumpfile' ] ) ) {
			$job_object->additional_files_to_backup[ ] = BackWPup::get_plugin_data( 'TEMP' ) . $job_object->temp[ 'dbdumpfile' ];
			$job_object->log( sprintf( __( 'Added database dump "%1$s" with %2$s to backup file list', 'backwpup' ), $job_object->temp[ 'dbdumpfile' ], size_format( $filesize, 2 ) ) );
		}

		$job_object->log( __( 'Database backup done!', 'backwpup' ) );

		return TRUE;
	}

	/**
	 * Dumps the Database in xml
	 *
	 * @param $job_object BackWPup_Job
	 *
	 * @return bool
	 */
	private function db_dump_xml( &$job_object ) {
		global $wpdb;

		$job_object->log( sprintf( __( '%d. Try to backup database as XML&#160;&hellip;', 'backwpup' ), $job_object->steps_data[ $job_object->step_working ][ 'STEP_TRY' ] ) );

		if ( ! isset( $job_object->steps_data[ $job_object->step_working ][ 'TABLES' ] ) || ! is_array( $job_object->steps_data[ $job_object->step_working ][ 'TABLES' ] ) )
			$job_object->steps_data[ $job_object->step_working ][ 'TABLES' ] = array();

		//build filename
		if ( empty( $job_object->temp[ 'dbdumpfile' ] ) )
			$job_object->temp[ 'dbdumpfile' ] = $job_object->generate_filename( $job_object->job[ 'dbdumpfile' ], 'xml' ) . $job_object->job[ 'dbdumpfilecompression' ];


		if ( $job_object->job[ 'dbdumpfilecompression' ] == '.gz' )
			$handle = fopen( 'compress.zlib://' . BackWPup::get_plugin_data( 'TEMP' ) . $job_object->temp[ 'dbdumpfile' ], 'w' );
		else
			$handle = fopen( BackWPup::get_plugin_data( 'TEMP' ) . $job_object->temp[ 'dbdumpfile' ], 'w' );


		if ( ! $handle ) {
			$job_object->log( __( 'Can not open target file for writing.', 'backwpup' ), E_USER_ERROR );
			return FALSE;
		}

		//make a new DB connection

		//set empty host to localhost
		if ( empty( $job_object->job[ 'dbdumpdbhost' ] ) ) {
			$job_object->job[ 'dbdumpdbhost' ] = NULL;
		}

		//check if port or socket in hostname and set port and socket
		$args = array();
		$args[ 'dbport' ]   = NULL;
		$args[ 'dbsocket' ] = NULL;
		if ( strstr( $job_object->job[ 'dbdumpdbhost' ], ':' ) ) {
			$hostparts = explode( ':', $job_object->job[ 'dbdumpdbhost' ], 2 );
			$hostparts[ 0 ] = trim( $hostparts[ 0 ] );
			$hostparts[ 1 ] = trim( $hostparts[ 1 ] );
			if ( empty( $hostparts[ 0 ] ) )
				$job_object->job[ 'dbdumpdbhost' ] = NULL;
			else
				$job_object->job[ 'dbdumpdbhost' ] = $hostparts[ 0 ];
			if ( is_numeric( $hostparts[ 1 ] ) )
				$args[ 'dbport' ] = (int) $hostparts[ 1 ];
			else
				$args[ 'dbsocket' ] = $hostparts[ 1 ];
		}



		$mysqli = mysqli_init();
		if ( ! $mysqli ) {
			$job_object->log( __( 'Cannot init MySQLi database connection', 'backwpup' ) );
			return TRUE;
		}


		if ( ! $mysqli->options( MYSQLI_OPT_CONNECT_TIMEOUT, 5 ) ) {
			$job_object->log( __( 'Setting of MySQLi connection timeout failed', 'backwpup' ) );
			return TRUE;
		}

		//connect to Database
		if ( ! $mysqli->real_connect( $job_object->job[ 'dbdumpdbhost' ], $job_object->job[ 'dbdumpdbuser' ], BackWPup_Encryption::decrypt( $job_object->job[ 'dbdumpdbpassword' ] ), $job_object->job[ 'dbdumpdbname' ], $args[ 'dbport' ], $args[ 'dbsocket' ], $job_object->job[ 'dbclientflags' ] ) ) {
			$job_object->log( sprintf( __( 'Cannot connect to MySQL database %1$d: %2$s', 'backwpup' ), mysqli_connect_errno(), mysqli_connect_error() ) );
			return TRUE;
		}

		//set charset
		if ( ! empty( $job_object->job[ 'dbdumpdbcharset' ] ) && method_exists( $mysqli, 'set_charset' ) ) {
			$res = $mysqli->set_charset( $job_object->job[ 'dbdumpdbcharset' ] );
			if ( ! $res ) {
				$job_object->log( sprintf( _x( 'Cannot set DB charset to %s','Database Charset', 'backwpup' ), $job_object->job[ 'dbdumpdbcharset' ] ) );
				return TRUE;
			}
		}

		//get tables to backup
		$res = $mysqli->query( 'SHOW FULL TABLES FROM `' . $job_object->job[ 'dbdumpdbname' ] . '`' );
		if ( $mysqli->error )
			$job_object->log( sprintf( __( 'Database error %1$s for query %2$s', 'backwpup' ), $mysqli->error, 'SHOW FULL TABLES FROM `' . $job_object->job[ 'dbdumpdbname' ] . '`' ), E_USER_ERROR );
		while ( $table = $res->fetch_array( MYSQLI_NUM ) ) {
			if ( in_array( $table[ 0 ], $job_object->job[ 'dbdumpexclude' ], true ) ) //tables to ignore
				continue;
			$job_object->steps_data[ $job_object->step_working ][ 'TABLES' ][] = $table[ 0 ];
			$job_object->steps_data[ $job_object->step_working ][ 'TABLETYPE' ][ $table[ 0 ] ] = $table[ 1 ];
		}
		$res->close();

		$job_object->substeps_todo = count( $job_object->steps_data[ $job_object->step_working ][ 'TABLES' ] ) * 2;

		//Get table status
		$res = $mysqli->query( "SHOW TABLE STATUS FROM `" . $job_object->job[ 'dbdumpdbname' ] . "`" );
		if ( $mysqli->error )
			$job_object->log( sprintf( __( 'Database error %1$s for query %2$s', 'backwpup' ), $mysqli->error, "SHOW TABLE STATUS FROM `" . $job_object->job[ 'dbdumpdbname' ] . "`" ), E_USER_ERROR );
		while ( $tablestatus = $res->fetch_assoc() ) {
			$job_object->steps_data[ $job_object->step_working ][ 'TABLESTATUS' ][ $tablestatus[ 'Name' ] ] = $tablestatus;
		}
		$res->close();

		if ( count( $job_object->steps_data[ $job_object->step_working ][ 'TABLES' ] ) == 0 ) {
			$job_object->log( __( 'No tables for XML backup', 'backwpup' ), E_USER_WARNING );

			return TRUE;
		}
		//create header
		$dbdumpheader = '<?xml version="1.0" encoding="' . get_bloginfo( 'charset' ) . '"?>' . PHP_EOL;
		$dbdumpheader .= '<!--' . PHP_EOL;
		$dbdumpheader .= "- Backup with BackWPup ver.: " . BackWPup::get_plugin_data( 'Version' ) . PHP_EOL;
		$dbdumpheader .= "- http://backwpup.com" . PHP_EOL;
		if ( ! empty( $job_object->job[ 'dbdumpwpdbsettings' ] ) ) {
			$dbdumpheader .= "- Blog Name: " . get_bloginfo( 'name' ) . PHP_EOL;
			$dbdumpheader .= "- Blog URL: " . trailingslashit( get_bloginfo( 'url' ) ) . PHP_EOL;
			$dbdumpheader .= "- Blog ABSPATH: " . trailingslashit( str_replace( '\\', '/', ABSPATH ) ) . PHP_EOL;
			$dbdumpheader .= "- Blog Charset: " . get_bloginfo( 'charset' ) . PHP_EOL;
			$dbdumpheader .= "- Table Prefix: " . $wpdb->prefix . PHP_EOL;
		}
		$dbdumpheader .= "- Database Name: " . $job_object->job[ 'dbdumpdbname' ] . PHP_EOL;
		$dbdumpheader .= "- Backup on: " . date( 'Y-m-d H:i.s', current_time( 'timestamp' ) ) . PHP_EOL;
		$dbdumpheader .= "-->" . PHP_EOL . PHP_EOL;
		//for better import with mysql client
		$dbdumpheader .= '<pma_xml_export version="1.0" xmlns:pma="http://www.phpmyadmin.net/some_doc_url/">' . PHP_EOL;
		$dbdumpheader .= "\t<!-- " . PHP_EOL;
		$dbdumpheader .= "\t- Structure schemas" . PHP_EOL;
		$dbdumpheader .= "\t-->" . PHP_EOL;
		$dbdumpheader .= "\t<pma:structure_schemas>" . PHP_EOL;
		$dbdumpheader .= "\t\t<pma:database name=\"" . $job_object->job[ 'dbdumpdbname' ] . "\" collation=\"" . $mysqli->get_charset()->collation . "\" charset=\"" . $mysqli->character_set_name() . "\">" . PHP_EOL;
		fwrite( $handle, $dbdumpheader );


		foreach ( $job_object->steps_data[ $job_object->step_working ][ 'TABLES' ] as $tablekey => $table ) {

			$job_object->update_working_data();
			if ( ! isset( $job_object->steps_data[ $job_object->step_working ][ 'ROWDONE' ] ) )
				$job_object->steps_data[ $job_object->step_working ][ 'ROWDONE' ] = 0;

			if ( $job_object->steps_data[ $job_object->step_working ][ 'TABLETYPE' ][ $table ] == 'VIEW' ) {
				$job_object->log( sprintf( __( 'Dump database create view "%s"', 'backwpup' ), $table ) );
				$tablecreate = "\t\t\t<pma:view name=\"" . $table . "\">" . PHP_EOL;
				//Dump the view structure
				$res = $mysqli->query( "SHOW CREATE VIEW `" . $table . "`" );
				if ( $mysqli->error ) {
					$job_object->log( sprintf( __( 'Database error %1$s for query %2$s', 'backwpup' ), $mysqli->error, "SHOW CREATE VIEW `" . $table . "`" ), E_USER_ERROR );

					return FALSE;
				}
				$createview = $res->fetch_assoc();
				$res->close();
				$tablecreate .= "\t\t\t\t" . str_replace( "\n", PHP_EOL . "\t\t\t\t", $createview[ 'Create View' ] ) . ';' . PHP_EOL;
				$tablecreate .= "\t\t\t</pma:view>" . PHP_EOL;

				fwrite( $handle, $tablecreate );
			}
			else {
				if ( $job_object->is_debug() ) {
					$job_object->log( sprintf( __( 'Backup database structure "%s" to XML', 'backwpup' ), $table ) );
				}
				$tablecreate = "\t\t\t<pma:table name=\"" . $table . "\">" . PHP_EOL;
				//Dump the table structure
				$res = $mysqli->query( "SHOW CREATE TABLE `" . $table . "`" );
				if ( $mysqli->error ) {
					$job_object->log( sprintf( __( 'Database error %1$s for query %2$s', 'backwpup' ), $mysqli->error, "SHOW CREATE TABLE `" . $table . "`" ), E_USER_ERROR );

					return FALSE;
				}
				$createtable = $res->fetch_assoc();
				$res->close();
				$tablecreate .= "\t\t\t\t" . str_replace( "\n", PHP_EOL . "\t\t\t\t", $createtable[ 'Create Table' ] ) . ';' . PHP_EOL;
				$tablecreate .= "\t\t\t</pma:table>" . PHP_EOL;

				fwrite( $handle, $tablecreate );
			}

			unset( $job_object->steps_data[ $job_object->step_working ][ 'TABLES' ][ $tablekey ] );
			$job_object->steps_data[ $job_object->step_working ][ 'TABLESDUMP' ][ ] = $table;
			$job_object->substeps_done ++;
			$job_object->steps_data[ $job_object->step_working ][ 'ROWDONE' ] = 0;

		}
		$tablecreateend = "\t\t</pma:database>" . PHP_EOL;
		$tablecreateend .= "\t</pma:structure_schemas>" . PHP_EOL . PHP_EOL;
		$tablecreateend .= "\t<!--" . PHP_EOL;
		$tablecreateend .= "\t- Datenbank: '" . $job_object->job[ 'dbdumpdbname' ] . "'" . PHP_EOL;
		$tablecreateend .= "\t-->" . PHP_EOL;
		$tablecreateend .= "\t<database name=\"" . $job_object->job[ 'dbdumpdbname' ] . "\">" . PHP_EOL;
		fwrite( $handle, $tablecreateend );

		//make table data dumps

		foreach ( $job_object->steps_data[ $job_object->step_working ][ 'TABLESDUMP' ] as $tablekey => $table ) {

			$job_object->update_working_data();
			if ( ! isset( $job_object->steps_data[ $job_object->step_working ][ 'ROWDONE' ] ) )
				$job_object->steps_data[ $job_object->step_working ][ 'ROWDONE' ] = 0;

			if ( $job_object->steps_data[ $job_object->step_working ][ 'TABLETYPE' ][ $table ] == 'BASE TABLE' ) {
				if ( $job_object->is_debug() ) {
					$job_object->log( sprintf( __( 'Backup table "%s" data to XML', 'backwpup' ), $table ) );
				}
				fwrite( $handle, "\t\t<!-- Tabelle " . $table . " -->" . PHP_EOL );

				//get data from table
				$res = $mysqli->query( "SELECT * FROM `" . $table . "`" );
				if ( $mysqli->error ) {
					$job_object->log( sprintf( __( 'Database error %1$s for query %2$s', 'backwpup' ), $mysqli->error, "SELECT * FROM `" . $table . "`" ), E_USER_ERROR );

					return FALSE;
				}
				//get field information
				$fieldsarray = array();
				$fieldinfo   = array();
				$fields      = $res->fetch_fields();
				$i = 0;
				foreach ( $fields as $filed ) {
					$fieldsarray[ $i ]               = $filed->orgname;
					$fieldinfo[ $fieldsarray[ $i ] ] = $filed;
					$i ++;
				}

				$count = 0;
				while ( $data = $res->fetch_assoc() ) {
					if ( $job_object->steps_data[ $job_object->step_working ][ 'ROWDONE' ] > $count )
						continue;

					$dump = "\t\t<table name=\"" . $table . "\">" . PHP_EOL;

					foreach ( $data as $key => $value ) {
						if ( is_null( $value ) || ! isset( $value ) ) // Make Value NULL to string NULL
							$value = "NULL";
						elseif ( in_array( (int) $fieldinfo[ $key ]->type, array( MYSQLI_TYPE_DECIMAL, MYSQLI_TYPE_TINY, MYSQLI_TYPE_SHORT, MYSQLI_TYPE_LONG,  MYSQLI_TYPE_FLOAT, MYSQLI_TYPE_DOUBLE, MYSQLI_TYPE_LONGLONG, MYSQLI_TYPE_INT24 ), true ) ) //is value numeric no esc
							$value = empty( $value ) ? 0 : $value;
						elseif ( $value == '' )
							$value = '';
						else
							$value = '<![CDATA['.$value .']]>';
						$dump .= "\t\t\t<column name=\"" . $key . "\">" . $value . "</column>" . PHP_EOL;

					}
					$dump .= "\t\t</table>" . PHP_EOL;

					fwrite( $handle, $dump );
					$job_object->steps_data[ $job_object->step_working ][ 'ROWDONE' ] ++;
					$count ++;
				}

				$res->close();
			}

			unset( $job_object->steps_data[ $job_object->step_working ][ 'TABLESDUMP' ][ $tablekey ] );
			$job_object->substeps_done ++;
			$job_object->steps_data[ $job_object->step_working ][ 'ROWDONE' ] = 0;
		}


		if ( $job_object->substeps_todo == $job_object->substeps_done ) {
			$dbdumpfooter = "\t</database>" . PHP_EOL;
			$dbdumpfooter .= "</pma_xml_export>" . PHP_EOL;

			fwrite( $handle, $dbdumpfooter );

			fclose( $handle );

			//add database file to backup files
			if ( is_readable( BackWPup::get_plugin_data( 'TEMP' ) . $job_object->temp[ 'dbdumpfile' ] ) ) {
				$job_object->additional_files_to_backup[ ] = BackWPup::get_plugin_data( 'TEMP' ) . $job_object->temp[ 'dbdumpfile' ];
				$job_object->log( sprintf( __( 'Added database XML dump "%1$s" with %2$s to backup file list', 'backwpup' ), $job_object->temp[ 'dbdumpfile' ], size_format( filesize( BackWPup::get_plugin_data( 'TEMP' ) . $job_object->temp[ 'dbdumpfile' ] ), 2 ) ) );
			}

			$job_object->log( __( 'Database XML backup done!', 'backwpup' ) );
		}
		//close db connection
		$mysqli->close();
		//cleanup
		unset( $job_object->steps_data[ $job_object->step_working ][ 'TABLES' ] );
		unset( $job_object->steps_data[ $job_object->step_working ][ 'TABLETYPE' ] );
		unset( $job_object->steps_data[ $job_object->step_working ][ 'TABLESTATUS' ] );
		unset( $job_object->steps_data[ $job_object->step_working ][ 'TABLESTATUS' ] );
		unset( $job_object->steps_data[ $job_object->step_working ][ 'ROWDONE' ] );
		unset( $job_object->steps_data[ $job_object->step_working ][ 'TABLESDUMP' ] );

		return TRUE;
	}
}
