<?php

/**
 * Class String_Locator
 */
class String_Locator {
	/**
	 * @var string $string_locator_language The code language used for the editing page.
	 * @var string $version String Locator version number.
	 * @var array $notice An array containing all notices to display.
	 * @var bool $failed_edit Has there been a failed edit.
	 * @var string $path_to_use The path to the currently editable file.
	 * @var array $bad_http_codes An array of HTTP status codes that will trigger the rollback feature.
	 * @var array $bad_file_types An array of file extensions that will be ignored by the scanner.
	 * @var int $excerpt_length The length of the excerpt from the line containing a match.
	 * @var int|null $max_execution_time The server-configured max time a script can run.
	 * @var int $start_execution_time The current time when our script started executing.
	 * @var int $max_memory_consumption The server-configured max amount of memory a script can use.
	 */
	public $string_locator_language = '';
	public $version                 = '2.4.2';
	public $notice                  = array();
	public $failed_edit             = false;
	private $path_to_use            = '';
	private $bad_http_codes         = array( '500' );
	private $bad_file_types         = array( 'rar', '7z', 'zip', 'tar', 'gz', 'jpg', 'jpeg', 'png', 'gif', 'mp3', 'mp4', 'avi', 'wmv' );
	private $excerpt_length         = 25;
	private $max_execution_time     = null;
	private $start_execution_timer  = 0;
	private $max_memory_consumption = 0;

	private $rest_namespace = 'string-locator';

	/**
	 * Construct the plugin
	 */
	function __construct() {
		$this->init();
	}

	/**
	 * The plugin initialization, ready as a stand alone function so it can be instantiated in other
	 * scenarios as well.
	 *
	 * @since 2.2.0
	 *
	 * @return void
	 */
	public function init() {
		/**
		 * Define class variables requiring expressions
		 */
		$this->path_to_use    = ( is_multisite() ? 'network/admin.php' : 'tools.php' );
		$this->excerpt_length = apply_filters( 'string_locator_excerpt_length', 25 );

		$this->max_execution_time    = absint( ini_get( 'max_execution_time' ) );
		$this->start_execution_timer = microtime( true );

		if ( $this->max_execution_time > 30 ) {
			$this->max_execution_time = 30;
		}

		$this->set_memory_limit();

		add_filter( 'admin_body_class', array( $this, 'admin_body_class' ) );

		add_action( 'admin_menu', array( $this, 'populate_menu' ) );
		add_action( 'network_admin_menu', array( $this, 'populate_network_menu' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ), 11 );

		add_action( 'plugins_loaded', array( $this, 'load_i18n' ) );

		add_action( 'wp_ajax_string-locator-get-directory-structure', array( $this, 'ajax_get_directory_structure' ) );
		add_action( 'wp_ajax_string-locator-search', array( $this, 'ajax_file_search' ) );
		add_action( 'wp_ajax_string-locator-clean', array( $this, 'ajax_clean_search' ) );

		add_filter( 'plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 2 );

		add_action( 'rest_api_init', array( $this, 'add_rest_route' ) );
	}

	public function add_rest_route() {
		register_rest_route(
			sprintf(
				'%s/v1',
				$this->rest_namespace
			),
			'/save',
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'editor_save' ),
				'permission_callback' => function() {
					return current_user_can( 'edit_themes' );
				},
			)
		);
	}

	/**
	 * Sets up the memory limit variables.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	function set_memory_limit() {
		$memory_limit = ini_get( 'memory_limit' );

		$this->max_memory_consumption = absint( $memory_limit );

		if ( strstr( $memory_limit, 'k' ) ) {
			$this->max_memory_consumption = ( str_replace( 'k', '', $memory_limit ) * 1000 );
		}
		if ( strstr( $memory_limit, 'M' ) ) {
			$this->max_memory_consumption = ( str_replace( 'M', '', $memory_limit ) * 1000000 );
		}
		if ( strstr( $memory_limit, 'G' ) ) {
			$this->max_memory_consumption = ( str_replace( 'G', '', $memory_limit ) * 1000000000 );
		}
	}

	/**
	 * Add a donation link to the plugins page.
	 *
	 * @param array $meta An array of meta links for this plugin.
	 * @param string $plugin_file The main plugin file name, used to identify our own plugin.
	 *
	 * @return array
	 */
	function plugin_row_meta( $meta, $plugin_file ) {
		if ( 'string-locator/string-locator.php' === $plugin_file ) {
			$meta[] = sprintf(
				'<a href="https://www.paypal.me/clorith">%s</a>',
				esc_html__( 'Donate to this plugin', 'string-locator' )
			);
		}

		return $meta;
	}

	/**
	 * Create a set of drop-down options for picking one of the available themes.
	 *
	 * @param string $current The current selection option to match against.
	 *
	 * @return string
	 */
	public static function get_themes_options( $current = null ) {
		$options = sprintf(
			'<option value="%s" %s>&mdash; %s &mdash;</option>',
			't--',
			( 't--' === $current ? 'selected="selected"' : '' ),
			esc_html( __( 'All themes', 'string-locator' ) )
		);

		$string_locate_themes = wp_get_themes();

		foreach ( $string_locate_themes as $string_locate_theme_slug => $string_locate_theme ) {
			$string_locate_theme_data = wp_get_theme( $string_locate_theme_slug );
			$string_locate_value      = 't-' . $string_locate_theme_slug;

			$options .= sprintf(
				'<option value="%s" %s>%s</option>',
				$string_locate_value,
				( $current === $string_locate_value ? 'selected="selected"' : '' ),
				$string_locate_theme_data->Name // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
			);
		}

		return $options;
	}

	public static function get_edit_form_url() {
		$url_query = String_Locator::edit_form_fields();

		return admin_url(
			sprintf(
				'tools.php?%s',
				build_query( $url_query )
			)
		);
	}

	public static function edit_form_fields( $echo = false ) {
		$fields = array(
			'page'                => ( isset( $_GET['page'] ) ? $_GET['page'] : '' ),
			'edit-file'           => ( isset( $_GET['edit-file'] ) ? $_GET['edit-file'] : '' ),
			'file-reference'      => ( isset( $_GET['file-reference'] ) ? $_GET['file-reference'] : '' ),
			'file-type'           => ( isset( $_GET['file-type'] ) ? $_GET['file-type'] : '' ),
			'string-locator-line' => ( isset( $_GET['string-locator-line'] ) ? $_GET['string-locator-line'] : '' ),
			'string-locator-path' => ( isset( $_GET['string-locator-path'] ) ? $_GET['string-locator-path'] : '' ),
		);

		$field_output = array();

		foreach ( $fields as $label => $value ) {
			$field_output[] = sprintf(
				'<input type="hidden" name="%s" value="%s">',
				esc_attr( $label ),
				esc_attr( $value )
			);
		}

		if ( $echo ) {
			echo implode( "\n", $field_output );
		}

		return $field_output;
	}

	/**
	 * Create a set of drop-down options for picking one of the available plugins.
	 *
	 * @param string $current The current selection option to match against.
	 *
	 * @return string
	 */
	public static function get_plugins_options( $current = null ) {
		$options = sprintf(
			'<option value="%s" %s>&mdash; %s &mdash;</option>',
			'p--',
			( 'p--' === $current ? 'selected="selected"' : '' ),
			esc_html( __( 'All plugins', 'string-locator' ) )
		);

		$string_locate_plugins = get_plugins();

		foreach ( $string_locate_plugins as $string_locate_plugin_path => $string_locate_plugin ) {
			$string_locate_value = 'p-' . $string_locate_plugin_path;

			$options .= sprintf(
				'<option value="%s" %s>%s</option>',
				$string_locate_value,
				( $current === $string_locate_value ? 'selected="selected"' : '' ),
				$string_locate_plugin['Name']
			);
		}

		return $options;
	}

	/**
	 * Create a set of drop-down options for picking one of the available must-use plugins.
	 *
	 * @param string $current The current selection option to match against.
	 *
	 * @return string
	 */
	public static function get_mu_plugins_options( $current = null ) {
		$options = sprintf(
			'<option value="%s" %s>&mdash; %s &mdash;</option>',
			'mup--',
			( 'mup--' === $current ? 'selected="selected"' : '' ),
			esc_html__( 'All must-use plugins', 'string-locator' )
		);

		$string_locate_plugins = get_mu_plugins();

		foreach ( $string_locate_plugins as $string_locate_plugin_path => $string_locate_plugin ) {
			$string_locate_value = 'mup-' . $string_locate_plugin_path;

			$options .= sprintf(
				'<option value="%s" %s>%s</option>',
				$string_locate_value,
				( $current === $string_locate_value ? 'selected="selected"' : '' ),
				$string_locate_plugin['Name']
			);
		}

		return $options;
	}

	/**
	 * Check if there are Must-Use plugins available on this WordPress install.
	 *
	 * @since 2.2.0
	 *
	 * @return bool
	 */
	public static function has_mu_plugins() {
		$mu_plugin_count = get_mu_plugins();

		if ( count( $mu_plugin_count ) >= 1 ) {
			return true;
		}

		return false;
	}

	/**
	 * Handles the AJAX request to prepare the search hierarchy.
	 *
	 * @return void
	 */
	function ajax_get_directory_structure() {
		if ( ! check_ajax_referer( 'string-locator-search', 'nonce', false ) ) {
			wp_send_json_error( __( 'Authentication failed', 'string-locator' ) );
		}

		$scan_path = $this->prepare_scan_path( $_POST['directory'] );
		if ( is_file( $scan_path->path ) ) {
			$files = array( $scan_path->path );
		} else {
			$files = $this->ajax_scan_path( $scan_path->path );
		}

		/*
		 * Make sure each chunk of file arrays never exceeds 500 files
		 * This is to prevent the SQL string from being too large and crashing everything
		 */
		$back_compat_filter = apply_filters( 'string-locator-files-per-array', 500 ); //phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores

		$file_chunks = array_chunk( $files, apply_filters( 'string_locator_files_per_array', $back_compat_filter ), true );

		$store = (object) array(
			'scan_path' => $scan_path,
			'search'    => wp_unslash( $_POST['search'] ),
			'directory' => $_POST['directory'],
			'chunks'    => count( $file_chunks ),
			'regex'     => $_POST['regex'],
		);

		$response = array(
			'total'     => count( $files ),
			'current'   => 0,
			'directory' => $scan_path,
			'chunks'    => count( $file_chunks ),
			'regex'     => $_POST['regex'],
		);

		set_transient( 'string-locator-search-overview', $store );
		update_option( 'string-locator-search-history', array(), false );

		foreach ( $file_chunks as $count => $file_chunk ) {
			set_transient( 'string-locator-search-files-' . $count, $file_chunk );
		}

		wp_send_json_success( $response );
	}

	/**
	 * Check if the script is about to exceed the max execution time.
	 *
	 * @since 1.9.0
	 *
	 * @return bool
	 */
	function nearing_execution_limit() {
		// Max execution time is 0 or -1 (infinite) in server config
		if ( 0 === $this->max_execution_time || - 1 === $this->max_execution_time ) {
			return false;
		}

		$back_compat_filter = apply_filters( 'string-locator-extra-search-delay', 2 ); // phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores

		$built_in_delay = apply_filters( 'string_locator_extra_search_delay', $back_compat_filter );
		$execution_time = ( microtime( true ) - $this->start_execution_timer + $built_in_delay );

		if ( $execution_time >= $this->max_execution_time ) {
			return $execution_time;
		}

		return false;
	}

	/**
	 * Check if the script is about to exceed the server memory limit.
	 *
	 * @since 2.0.0
	 *
	 * @return bool
	 */
	function nearing_memory_limit() {
		// Check if the memory limit is set t o0 or -1 (infinite) in server config
		if ( 0 === $this->max_memory_consumption || - 1 === $this->max_memory_consumption ) {
			return false;
		}

		// We give our selves a 256k memory buffer, as we need to close off the script properly as well
		$back_compat_filter = apply_filters( 'string-locator-extra-memory-buffer', 256000 ); //phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores
		$built_in_buffer    = apply_filters( 'string_locator_extra_memory_buffer', $back_compat_filter );
		$memory_use         = ( memory_get_usage( true ) + $built_in_buffer );

		if ( $memory_use >= $this->max_memory_consumption ) {
			return $memory_use;
		}

		return false;
	}

	public static function absbool( $value ) {
		if ( is_bool( $value ) ) {
			$bool = $value;
		} else {
			if ( 'false' === $value ) {
				$bool = false;
			} else {
				$bool = true;
			}
		}

		return $bool;
	}

	/**
	 * Search an individual file supplied via AJAX.
	 *
	 * @since 1.9.0
	 *
	 * @return void
	 */
	function ajax_file_search() {
		if ( ! check_ajax_referer( 'string-locator-search', 'nonce', false ) ) {
			wp_send_json_error( __( 'Authentication failed', 'string-locator' ) );
		}

		$back_compat_filter = apply_filters( 'string-locator-files-per-array', 500 ); // phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores

		$files_per_chunk = apply_filters( 'string_locator_files_per_array', $back_compat_filter );
		$response        = array(
			'search'  => array(),
			'filenum' => absint( $_POST['filenum'] ),
		);

		$filenum   = absint( $_POST['filenum'] );
		$next_file = $filenum + 1;

		$next_chunk = ( ceil( ( $next_file ) / $files_per_chunk ) - 1 );
		$chunk      = ( ceil( $filenum / $files_per_chunk ) - 1 );
		if ( $chunk < 0 ) {
			$chunk = 0;
		}
		if ( $next_chunk < 0 ) {
			$next_chunk = 0;
		}

		$scan_data = get_transient( 'string-locator-search-overview' );
		$file_data = get_transient( 'string-locator-search-files-' . $chunk );

		if ( ! isset( $file_data[ $filenum ] ) ) {
			wp_send_json_error(
				array(
					'continue' => false,
					'message'  => sprintf(
						/* translators: %d: The numbered reference to a file being searched. */
						esc_html__( 'The file-number, %d, that was sent could not be found.', 'string-locator' ),
						$filenum
					),
				)
			);
		}

		if ( $this->nearing_execution_limit() ) {
			wp_send_json_error(
				array(
					'continue' => false,
					'message'  => sprintf(
						/* translators: %1$d: The time a PHP file can run, as defined by the server configuration. %2$d: The amount of time used by the PHP file so far. */
						esc_html__( 'The maximum time your server allows a script to run (%1$d) is too low for the plugin to run as intended, at startup %2$d seconds have passed', 'string-locator' ),
						$this->max_execution_time,
						$this->nearing_execution_limit()
					),
				)
			);
		}
		if ( $this->nearing_memory_limit() ) {
			wp_send_json_error(
				array(
					'continue' => false,
					'message'  => sprintf(
						/* translators: %1$d: Current amount of used system memory resources. %2$d: The maximum available system memory. */
						esc_html__( 'The memory limit is about to be exceeded before the search has started, this could be an early indicator that your site may soon struggle as well, unfortunately this means the plugin is unable to perform any searches. Current memory consumption: %1$d of %2$d bytes', 'string-locator' ),
						$this->nearing_memory_limit(),
						$this->max_memory_consumption
					),
				)
			);
		}

		$is_regex = false;
		if ( isset( $scan_data->regex ) ) {
			$is_regex = $this->absbool( $scan_data->regex );
		}

		if ( $is_regex ) {
			if ( false === @preg_match( $scan_data->search, '' ) ) { // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
				wp_send_json_error(
					array(
						'continue' => false,
						'message'  => sprintf(
							/* translators: %s: The search string used. */
							__( 'Your search string, <strong>%s</strong>, is not a valid pattern, and the search has been aborted.', 'string-locator' ),
							esc_html( $scan_data->search )
						),
					)
				);
			}
		}

		while ( ! $this->nearing_execution_limit() && ! $this->nearing_memory_limit() && isset( $file_data[ $filenum ] ) ) {
			$filenum        = absint( $_POST['filenum'] );
			$search_results = null;
			$next_file      = $filenum + 1;

			$next_chunk = ( ceil( ( $next_file ) / $files_per_chunk ) - 1 );
			$chunk      = ( ceil( $filenum / $files_per_chunk ) - 1 );
			if ( $chunk < 0 ) {
				$chunk = 0;
			}
			if ( $next_chunk < 0 ) {
				$next_chunk = 0;
			}

			if ( ! isset( $file_data[ $filenum ] ) ) {
				$chunk ++;
				$file_data = get_transient( 'string-locator-search-files-' . $chunk );
				continue;
			}

			$file_name = explode( '/', $file_data[ $filenum ] );
			$file_name = end( $file_name );

			/*
			 * Check the file type, if it's an unsupported type, we skip it
			 */
			$file_type = explode( '.', $file_name );
			$file_type = strtolower( end( $file_type ) );

			/*
			 * Scan the file and look for our string, but only if it's an approved file extension
			 */
			$bad_file_types = apply_filters( 'string_locator_bad_file_types', $this->bad_file_types );
			if ( ! in_array( $file_type, $bad_file_types, true ) ) {
				$search_results = $this->scan_file( $file_data[ $filenum ], $scan_data->search, $file_data[ $filenum ], $scan_data->scan_path->type, '', $is_regex );
			}

			$response['last_file'] = $file_data[ $filenum ];
			$response['filenum']   = $filenum;
			$response['filename']  = $file_name;
			if ( $search_results ) {
				$response['search'][] = $search_results;
			}

			if ( $next_chunk !== $chunk ) {
				$file_data = get_transient( 'string-locator-search-files-' . $next_chunk );
			}

			$response['next_file'] = ( isset( $file_data[ $next_file ] ) ? $file_data[ $next_file ] : '' );

			if ( ! empty( $search_results ) ) {
				$history = get_option( 'string-locator-search-history', array() );
				$history = array_merge( $history, $search_results );
				update_option( 'string-locator-search-history', $history, false );
			}

			$_POST['filenum'] ++;
		}

		wp_send_json_success( $response );
	}

	/**
	 * Clean up our options used to help during the search.
	 *
	 * @return void
	 */
	function ajax_clean_search() {
		if ( ! check_ajax_referer( 'string-locator-search', 'nonce', false ) ) {
			wp_send_json_error( __( 'Authentication failed', 'string-locator' ) );
		}

		$scan_data = get_transient( 'string-locator-search-overview' );
		for ( $i = 0; $i < $scan_data->chunks; $i ++ ) {
			delete_transient( 'string-locator-search-files-' . $i );
		}

		wp_send_json_success( true );
	}

	/**
	 * Create a table row for insertion into the search results list.
	 *
	 * @param array|object $item The table row item.
	 *
	 * @return string
	 */
	public static function prepare_table_row( $item ) {
		if ( ! is_object( $item ) ) {
			$item = (object) $item;
		}

		return sprintf(
			'<tr>
                <td>
                	%s
                	<div class="row-actions">
                		%s
                    </div>
                </td>
                <td>
                	%s
                </td>
                <td>
                	%d
                </td>
                <td>
                	%d
                </td>
            </tr>',
			$item->stringresult,
			( ! current_user_can( 'edit_themes' ) ? '' : sprintf(
				'<span class="edit"><a href="%1$s" aria-label="%2$s">%2$s</a></span>',
				esc_url( $item->editurl ),
				// translators: The row-action edit link label.
				esc_html__( 'Edit', 'string-locator' )
			) ),
			( ! current_user_can( 'edit_themes' ) ? $item->filename_raw : sprintf(
				'<a href="%s">%s</a>',
				esc_url( $item->editurl ),
				esc_html( $item->filename_raw )
			) ),
			esc_html( $item->linenum ),
			esc_html( $item->linepos )
		);
	}

	/**
	 * Create a full table populated with the supplied items.
	 *
	 * @param array $items An array of table rows.
	 * @param array $table_class An array of items to append to the table class along with the defaults.
	 *
	 * @return string
	 */
	public static function prepare_full_table( $items, $table_class = array() ) {
		$table_class = array_merge(
			$table_class,
			array(
				'wp-list-table',
				'widefat',
				'fixed',
				'striped',
				'tools_page_string-locator',
			)
		);

		$table_columns = sprintf(
			'<tr>
				<th scope="col" class="manage-column column-stringresult column-primary">%s</th>
				<th scope="col" class="manage-column column-filename">%s</th>
				<th scope="col" class="manage-column column-linenum">%s</th>
				<th scope="col" class="manage-column column-linepos">%s</th>
			</tr>',
			esc_html( __( 'String', 'string-locator' ) ),
			esc_html( __( 'File', 'string-locator' ) ),
			esc_html( __( 'Line number', 'string-locator' ) ),
			esc_html( __( 'Line position', 'string-locator' ) )
		);

		$table_rows = array();
		foreach ( $items as $item ) {
			$table_rows[] = self::prepare_table_row( $item );
		}

		$table = sprintf(
			'<div class="tablenav top"><br class="clear"></div><table class="%s"><thead>%s</thead><tbody>%s</tbody><tfoot>%s</tfoot></table>',
			implode( ' ', $table_class ),
			$table_columns,
			implode( "\n", $table_rows ),
			$table_columns
		);

		return $table;
	}

	/**
	 * Create an admin edit link for the supplied path.
	 *
	 * @param string $path Path to the file we'er adding a link for.
	 * @param int $line The line in the file where our search result was found.
	 * @param int $linepos The positin in the line where the search result was found.
	 *
	 * @return string
	 */
	function create_edit_link( $path, $line = 0, $linepos = 0 ) {
		$file_type    = 'core';
		$file_slug    = '';
		$content_path = str_replace( '\\', '/', WP_CONTENT_DIR );

		$path  = str_replace( '\\', '/', $path );
		$paths = explode( '/', $path );

		$url_args = array(
			'page=string-locator',
			'edit-file=' . end( $paths ),
		);

		switch ( true ) {
			case ( in_array( 'wp-content', $paths, true ) && in_array( 'plugins', $paths, true ) ):
				$file_type     = 'plugin';
				$content_path .= '/plugins/';
				break;
			case ( in_array( 'wp-content', $paths, true ) && in_array( 'themes', $paths, true ) ):
				$file_type     = 'theme';
				$content_path .= '/themes/';
				break;
		}

		$rel_path  = str_replace( $content_path, '', $path );
		$rel_paths = explode( '/', $rel_path );

		if ( 'core' !== $file_type ) {
			$file_slug = $rel_paths[0];
		}

		$url_args[] = 'file-reference=' . $file_slug;
		$url_args[] = 'file-type=' . $file_type;
		$url_args[] = 'string-locator-line=' . absint( $line );
		$url_args[] = 'string-locator-linepos=' . absint( $linepos );
		$url_args[] = 'string-locator-path=' . urlencode( str_replace( '/', DIRECTORY_SEPARATOR, $path ) );

		$url = admin_url( $this->path_to_use . '?' . implode( '&', $url_args ) );

		return $url;
	}

	/**
	 * Parse the search option to determine what kind of search we are performing and what directory to start in.
	 *
	 * @param string $option The search-type identifier.
	 *
	 * @return bool|object
	 */
	function prepare_scan_path( $option ) {
		$data = array(
			'path' => '',
			'type' => '',
			'slug' => '',
		);

		switch ( true ) {
			case ( 't--' === $option ):
				$data['path'] = WP_CONTENT_DIR . '/themes/';
				$data['type'] = 'theme';
				break;
			case ( strlen( $option ) > 3 && 't-' === substr( $option, 0, 2 ) ):
				$data['path'] = WP_CONTENT_DIR . '/themes/' . substr( $option, 2 );
				$data['type'] = 'theme';
				$data['slug'] = substr( $option, 2 );
				break;
			case ( 'p--' === $option ):
				$data['path'] = WP_CONTENT_DIR . '/plugins/';
				$data['type'] = 'plugin';
				break;
			case ( 'mup--' === $option ):
				$data['path'] = WP_CONTENT_DIR . '/mu-plugins/';
				$data['type'] = 'mu-plugin';
				break;
			case ( strlen( $option ) > 3 && 'p-' === substr( $option, 0, 2 ) ):
				$slug = explode( '/', substr( $option, 2 ) );

				$data['path'] = WP_CONTENT_DIR . '/plugins/' . $slug[0];
				$data['type'] = 'plugin';
				$data['slug'] = $slug[0];
				break;
			case ( 'core' === $option ):
				$data['path'] = ABSPATH;
				$data['type'] = 'core';
				break;
			case ( 'wp-content' === $option ):
				$data['path'] = WP_CONTENT_DIR;
				$data['type'] = 'core';
				break;
		}

		if ( empty( $data['path'] ) ) {
			return false;
		}

		return (object) $data;
	}

	/**
	 * Check if a file path is valid for editing.
	 *
	 * @param string $path Path to file.
	 *
	 * @return bool
	 */
	function is_valid_location( $path ) {
		$valid   = true;
		$path    = str_replace( array( '/' ), array( DIRECTORY_SEPARATOR ), stripslashes( $path ) );
		$abspath = str_replace( array( '/' ), array( DIRECTORY_SEPARATOR ), ABSPATH );

		// Check that it is a valid file we are trying to access as well.
		if ( ! file_exists( $path ) ) {
			$valid = false;
		}

		if ( empty( $path ) ) {
			$valid = false;
		}
		if ( stristr( $path, '..' ) ) {
			$valid = false;
		}
		if ( ! stristr( $path, $abspath ) ) {
			$valid = false;
		}

		return $valid;
	}

	/**
	 * Set the text domain for translated plugin content.
	 *
	 * @return void
	 */
	function load_i18n() {
		$i18n_dir = 'string-locator/languages/';
		load_plugin_textdomain( 'string-locator', false, $i18n_dir );
	}

	/**
	 * Load up JavaScript and CSS for our plugin on the appropriate admin pages.
	 *
	 * @return void
	 */
	function admin_enqueue_scripts( $hook ) {
		// Break out early if we are not on a String Locator page
		if ( 'tools_page_string-locator' !== $hook && 'toplevel_page_string-locator' !== $hook ) {
			return;
		}

		if ( ! wp_script_is( 'react', 'registered' ) ) {
			wp_register_script( 'react', trailingslashit( STRING_LOCATOR_PLUGIN_URL ) . 'resources/js/react.js', array() );
		}

		if ( ! wp_script_is( 'react-dom', 'registered' ) ) {
			wp_register_script( 'react-dom', trailingslashit( STRING_LOCATOR_PLUGIN_URL ) . 'resources/js/react-dom.js', array() );
		}

		/**
		 * String Locator Styles
		 */
		wp_enqueue_style( 'string-locator', trailingslashit( STRING_LOCATOR_PLUGIN_URL ) . 'resources/css/string-locator.css', array(), $this->version );

		if ( ! isset( $_GET['edit-file'] ) || ! current_user_can( 'edit_themes' ) ) {
			/**
			 * String Locator Scripts
			 */
			wp_enqueue_script( 'string-locator-search', trailingslashit( STRING_LOCATOR_PLUGIN_URL ) . 'resources/js/string-locator-search.js', array( 'jquery', 'wp-util' ), $this->version, true );

			wp_localize_script(
				'string-locator-search',
				'string_locator',
				array(
					'ajax_url'              => admin_url( 'admin-ajax.php' ),
					'search_nonce'          => wp_create_nonce( 'string-locator-search' ),
					'search_current_prefix' => __( 'Next file: ', 'string-locator' ),
					'saving_results_string' => __( 'Saving search results&hellip;', 'string-locator' ),
					'search_preparing'      => __( 'Preparing search&hellip;', 'string-locator' ),
					'search_started'        => __( 'Preparations completed, search started&hellip;', 'string-locator' ),
					'search_error'          => __( 'The above error was returned by your server, for more details please consult your servers error logs.', 'string-locator' ),
					'search_no_results'     => __( 'Your search was completed, but no results were found.', 'string-locator' ),
					'warning_title'         => __( 'Warning', 'string-locator' ),
				)
			);

		} else {
			$code_mirror = wp_enqueue_code_editor(
				array(
					'file' => $_GET['edit-file'],
				)
			);

			/**
			 * String Locator Scripts
			 */
			wp_enqueue_script( 'string-locator-editor', trailingslashit( STRING_LOCATOR_PLUGIN_URL ) . 'resources/js/string-locator.js', array( 'jquery', 'code-editor', 'wp-util' ), $this->version, true );

			wp_localize_script(
				'string-locator-editor',
				'string_locator',
				array(
					'CodeMirror'   => $code_mirror,
					'goto_line'    => absint( $_GET['string-locator-line'] ),
					'goto_linepos' => absint( $_GET['string-locator-linepos'] ),
					'save_url'     => get_rest_url( null, 'string-locator/v1/save' ),
				)
			);
		}
	}

	/**
	 * Add our plugin to the 'Tools' menu.
	 *
	 * @return void
	 */
	function populate_menu() {
		if ( is_multisite() ) {
			return;
		}
		$page_title  = __( 'String Locator', 'string-locator' );
		$menu_title  = __( 'String Locator', 'string-locator' );
		$capability  = 'install_plugins';
		$parent_slug = 'tools.php';
		$menu_slug   = 'string-locator';
		$function    = array( $this, 'options_page' );

		add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function );
	}

	/**
	 * Add our plugin to the main menu in the Network Admin.
	 *
	 * @return void
	 */
	function populate_network_menu() {
		$page_title = __( 'String Locator', 'string-locator' );
		$menu_title = __( 'String Locator', 'string-locator' );
		$capability = 'install_plugins';
		$menu_slug  = 'string-locator';
		$function   = array( $this, 'options_page' );

		add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, 'dashicons-edit' );
	}

	/**
	 * Function for including the actual plugin Admin UI page.
	 *
	 * @return mixed
	 */
	function options_page() {
		/**
		 * Don't load anything if the user can't edit themes any way
		 */
		if ( ! current_user_can( 'update_core' ) ) {
			return false;
		}

		/**
		 * Show the edit page if;
		 * - The edit file path query var is set
		 * - The edit file path query var isn't empty
		 * - The edit file path query var does not contains double dots (used to traverse directories)
		 * - The user is capable of editing files.
		 */
		if ( isset( $_GET['string-locator-path'] ) && $this->is_valid_location( $_GET['string-locator-path'] ) && current_user_can( 'edit_themes' ) ) {
			include_once( dirname( __FILE__ ) . '/../editor.php' );
		} else {
			include_once( dirname( __FILE__ ) . '/../search.php' );
		}
	}

	function admin_body_class( $class ) {
		if ( isset( $_GET['string-locator-path'] ) && $this->is_valid_location( $_GET['string-locator-path'] ) && current_user_can( 'edit_themes' ) ) {
			$class .= ' file-edit-screen';
		}

		return $class;
	}

	/**
	 * Check for inconsistencies in brackets and similar.
	 *
	 * @param string $start Start delimited.
	 * @param string $end End delimiter.
	 * @param string $string The string to scan.
	 *
	 * @return array
	 */
	function smart_scan( $start, $end, $string ) {
		$opened = array();

		$lines = explode( "\n", $string );
		for ( $i = 0; $i < count( $lines ); $i ++ ) {
			if ( stristr( $lines[ $i ], $start ) ) {
				$opened[] = $i;
			}
			if ( stristr( $lines[ $i ], $end ) ) {
				array_pop( $opened );
			}
		}

		return $opened;
	}

	/**
	 * Handler for storing the content of the code editor.
	 *
	 * Also runs over the Smart-Scan if enabled.
	 *
	 * @return void|array
	 */
	function editor_save( $request ) {
		$_POST = $request->get_params();

		$check_loopback = isset( $_POST['string-locator-loopback-check'] );
		$do_smart_scan  = isset( $_POST['string-locator-smart-edit'] );

		if ( $this->is_valid_location( $_POST['string-locator-path'] ) ) {
			$path    = urldecode( $_POST['string-locator-path'] );
			$content = stripslashes( $_POST['string-locator-editor-content'] );

			/**
			 * Send an error notice if the file isn't writable
			 */
			if ( ! is_writeable( $path ) ) {
				$this->notice[] = array(
					'type'    => 'error',
					'message' => __( 'The file could not be written to, please check file permissions or edit it manually.', 'string-locator' ),
				);

				return array(
					'notices' => $this->notice,
				);
			}

			/**
			 * If enabled, run the Smart-Scan on the content before saving it
			 */
			if ( $do_smart_scan ) {
				$open_brace  = substr_count( $content, '{' );
				$close_brace = substr_count( $content, '}' );
				if ( $open_brace !== $close_brace ) {
					$this->failed_edit = true;

					$opened = $this->smart_scan( '{', '}', $content );

					foreach ( $opened as $line ) {
						$this->notice[] = array(
							'type'    => 'error',
							'message' => sprintf(
								// translators: 1: Line number with an error.
								__( 'There is an inconsistency in the opening and closing braces, { and }, of your file on line %s', 'string-locator' ),
								'<a href="#" class="string-locator-edit-goto" data-goto-line="' . ( $line + 1 ) . '">' . ( $line + 1 ) . '</a>'
							),
						);
					}
				}

				$open_bracket  = substr_count( $content, '[' );
				$close_bracket = substr_count( $content, ']' );
				if ( $open_bracket !== $close_bracket ) {
					$this->failed_edit = true;

					$opened = $this->smart_scan( '[', ']', $content );

					foreach ( $opened as $line ) {
						$this->notice[] = array(
							'type'    => 'error',
							'message' => sprintf(
								// translators: 1: Line number with an error.
								__( 'There is an inconsistency in the opening and closing braces, [ and ], of your file on line %s', 'string-locator' ),
								'<a href="#" class="string-locator-edit-goto" data-goto-line="' . ( $line + 1 ) . '">' . ( $line + 1 ) . '</a>'
							),
						);
					}
				}

				$open_parenthesis  = substr_count( $content, '(' );
				$close_parenthesis = substr_count( $content, ')' );
				if ( $open_parenthesis !== $close_parenthesis ) {
					$this->failed_edit = true;

					$opened = $this->smart_scan( '(', ')', $content );

					foreach ( $opened as $line ) {
						$this->notice[] = array(
							'type'    => 'error',
							'message' => sprintf(
								// translators: 1: Line number with an error.
								__( 'There is an inconsistency in the opening and closing braces, ( and ), of your file on line %s', 'string-locator' ),
								'<a href="#" class="string-locator-edit-goto" data-goto-line="' . ( $line + 1 ) . '">' . ( $line + 1 ) . '</a>'
							),
						);
					}
				}

				if ( $this->failed_edit ) {
					return array(
						'notices' => $this->notice,
					);
				}
			}

			$original = file_get_contents( $path );

			$this->write_file( $path, $content );

			/**
			 * Check the status of the site after making our edits.
			 * If the site fails, revert the changes to return the sites to its original state
			 */
			if ( $check_loopback ) {
				$header = wp_remote_head( site_url() );

				if ( ! is_wp_error( $header ) && 301 === (int) $header['response']['code'] ) {
					$header = wp_remote_head( $header['headers']['location'] );
				}

				$bad_http_check = apply_filters( 'string_locator_bad_http_codes', $this->bad_http_codes );
			}

			if ( $check_loopback && is_wp_error( $header ) ) {
				$this->failed_edit = true;
				$this->write_file( $path, $original );

				// Likely loopback error, so be useful in our errors.
				if ( 'http_request_failed' === $header->get_error_code() ) {
					return array(
						'notices' => array(
							array(
								'type'    => 'error',
								'message' => __( 'Your changes were not saved, as a check of your site could not be completed afterwards. This may be due to a <a href="https://wordpress.org/support/article/loopbacks/">loopback</a> error.', 'string-locator' ),
							),
						),
					);
				}

				// Fallback error message here.
				return array(
					'notices' => array(
						array(
							'type'    => 'error',
							'message' => $header->get_error_message(),
						),
					),
				);
			} elseif ( $check_loopback && in_array( $header['response']['code'], $bad_http_check, true ) ) {
				$this->failed_edit = true;
				$this->write_file( $path, $original );

				return array(
					'notices' => array(
						array(
							'type'    => 'error',
							'message' => __( 'A 500 server error was detected on your site after updating your file. We have restored the previous version of the file for you.', 'string-locator' ),
						),
					),
				);
			} else {
				return array(
					'notices' => array(
						array(
							'type'    => 'success',
							'message' => __( 'The file has been saved', 'string-locator' ),
						),
					),
				);
			}
		} else {
			return array(
				'notices' => array(
					array(
						'type'    => 'error',
						'message' => sprintf(
							// translators: %s: The file location that was sent.
							__( 'The file location provided, <strong>%s</strong>, is not valid.', 'string-locator' ),
							$_POST['string-locator-path']
						),
					),
				),
			);
		}
	}

	/**
	 * When editing a file, this is where we write all the new content.
	 * We will break early if the user isn't allowed to edit files.
	 *
	 * @param string $path The path to the file.
	 * @param string $content The content to write to the file.
	 *
	 * @return void
	 */
	private function write_file( $path, $content ) {
		if ( ! current_user_can( 'edit_themes' ) ) {
			return;
		}

		// Verify the location is valid before we try using it.
		if ( ! $this->is_valid_location( $path ) ) {
			return;
		}

		$back_compat_filter = apply_filters( 'string-locator-filter-closing-php-tags', true ); // phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores

		if ( apply_filters( 'string_locator_filter_closing_php_tags', $back_compat_filter ) ) {
			$content = preg_replace( '/\?>$/si', '', trim( $content ), - 1, $replaced_strings );

			if ( $replaced_strings >= 1 ) {
				$this->notice[] = array(
					'type'    => 'error',
					'message' => __( 'We detected a PHP code tag ending, this has been automatically stripped out to help prevent errors in your code.', 'string-locator' ),
				);
			}
		}

		$file        = fopen( $path, 'w' );
		$lines       = explode( "\n", str_replace( array( "\r\n", "\r" ), "\n", $content ) );
		$total_lines = count( $lines );

		for ( $i = 0; $i < $total_lines; $i ++ ) {
			$write_line = $lines[ $i ];

			if ( ( $i + 1 ) < $total_lines ) {
				$write_line .= PHP_EOL;
			}

			fwrite( $file, $write_line );
		}

		fclose( $file );
	}

	/**
	 * Hook the admin notices and loop over any notices we've registered in the plugin.
	 *
	 * @return void
	 */
	function admin_notice() {
		if ( ! empty( $this->notice ) ) {
			foreach ( $this->notice as $note ) {
				printf(
					'<div class="%s"><p>%s</p></div>',
					esc_attr( $note['type'] ),
					$note['message']
				);
			}
		}
	}

	/**
	 * Scan through an individual file to look for occurrences of £string.
	 *
	 * @param string $filename The path to the file.
	 * @param string $string The search string.
	 * @param mixed $location The file location object/string.
	 * @param string $type File type.
	 * @param string $slug The plugin/theme slug of the file.
	 * @param boolean $regex Should a regex search be performed.
	 *
	 * @return array
	 */
	function scan_file( $filename, $string, $location, $type, $slug, $regex = false ) {
		if ( empty( $string ) || ! is_file( $filename ) ) {
			return array();
		}
		$output      = array();
		$linenum     = 0;
		$match_count = 0;

		if ( ! is_object( $location ) ) {
			$path     = $location;
			$location = explode( DIRECTORY_SEPARATOR, $location );
			$file     = end( $location );
		} else {
			$path = $location->getPathname();
			$file = $location->getFilename();
		}

		/*
		 * Check if the filename matches our search pattern
		 */
		if ( stristr( $file, $string ) || ( $regex && preg_match( $string, $file ) ) ) {
			$relativepath = str_replace(
				array(
					ABSPATH,
					'\\',
					'/',
				),
				array(
					'',
					DIRECTORY_SEPARATOR,
					DIRECTORY_SEPARATOR,
				),
				$path
			);
			$match_count ++;

			$editurl = $this->create_edit_link( $path, $linenum );

			$path_string = sprintf(
				'<a href="%s">%s</a>',
				esc_url( $editurl ),
				esc_html( $relativepath )
			);

			$output[] = array(
				'ID'           => $match_count,
				'linenum'      => sprintf(
					'[%s]',
					esc_html__( 'Filename matches search', 'string-locator' )
				),
				'linepos'      => '',
				'path'         => $path,
				'filename'     => $path_string,
				'filename_raw' => $relativepath,
				'editurl'      => ( current_user_can( 'edit_themes' ) ? $editurl : false ),
				'stringresult' => $file,
			);
		}

		$readfile = @fopen( $filename, 'r' );
		if ( $readfile ) {
			while ( ( $readline = fgets( $readfile ) ) !== false ) { // phpcs:ignore WordPress.CodeAnalysis.AssignmentInCondition.FoundInWhileCondition
				$string_preview_is_cut = false;
				$linenum ++;
				/**
				 * If our string is found in this line, output the line number and other data
				 */
				if ( ( ! $regex && stristr( $readline, $string ) ) || ( $regex && preg_match( $string, $readline, $match, PREG_OFFSET_CAPTURE ) ) ) {
					/**
					 * Prepare the visual path for the end user
					 * Removes path leading up to WordPress root and ensures consistent directory separators
					 */
					$relativepath = str_replace(
						array(
							ABSPATH,
							'\\',
							'/',
						),
						array(
							'',
							DIRECTORY_SEPARATOR,
							DIRECTORY_SEPARATOR,
						),
						$path
					);
					$match_count ++;

					if ( $regex ) {
						$str_pos = $match[0][1];
					} else {
						$str_pos = stripos( $readline, $string );
					}

					/**
					 * Create the URL to take the user to the editor
					 */
					$editurl = $this->create_edit_link( $path, $linenum, $str_pos );

					$string_preview = $readline;
					if ( strlen( $string_preview ) > ( strlen( $string ) + $this->excerpt_length ) ) {
						$string_location = strpos( $string_preview, $string );

						$string_location_start = $string_location - $this->excerpt_length;
						if ( $string_location_start < 0 ) {
							$string_location_start = 0;
						}

						$string_location_end = ( strlen( $string ) + ( $this->excerpt_length * 2 ) );
						if ( $string_location_end > strlen( $string_preview ) ) {
							$string_location_end = strlen( $string_preview );
						}

						$string_preview        = substr( $string_preview, $string_location_start, $string_location_end );
						$string_preview_is_cut = true;
					}

					if ( $regex ) {
						$string_preview = preg_replace( preg_replace( '/\/(.+)\//', '/($1)/', $string ), '<strong>$1</strong>', esc_html( $string_preview ) );
					} else {
						$string_preview = preg_replace( '/(' . $string . ')/i', '<strong>$1</strong>', esc_html( $string_preview ) );
					}
					if ( $string_preview_is_cut ) {
						$string_preview = sprintf(
							'&hellip;%s&hellip;',
							$string_preview
						);
					}

					$path_string = sprintf(
						'<a href="%s">%s</a>',
						esc_url( $editurl ),
						esc_html( $relativepath )
					);

					$output[] = array(
						'ID'           => $match_count,
						'linenum'      => $linenum,
						'linepos'      => $str_pos,
						'path'         => $path,
						'filename'     => $path_string,
						'filename_raw' => $relativepath,
						'editurl'      => ( current_user_can( 'edit_themes' ) ? $editurl : false ),
						'stringresult' => $string_preview,
					);
				}
			}

			fclose( $readfile );
		} else {
			/**
			 * The file was unreadable, give the user a friendly notification
			 */
			$output[] = array(
				'linenum'      => '#',
				// translators: 1: Filename.
				'filename'     => esc_html( sprintf( __( 'Could not read file: %s', 'string-locator' ), $filename ) ),
				'stringresult' => '',
			);
		}

		return $output;
	}

	function ajax_scan_path( $path ) {
		$files = array();

		$paths = new RecursiveIteratorIterator(
			new RecursiveDirectoryIterator( $path ),
			RecursiveIteratorIterator::SELF_FIRST
		);

		foreach ( $paths as $name => $location ) {
			if ( is_dir( $location->getPathname() ) ) {
				continue;
			}

			$files[] = $location->getPathname();
		}

		return $files;
	}
}
