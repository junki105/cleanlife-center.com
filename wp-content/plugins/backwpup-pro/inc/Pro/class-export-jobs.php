<?php
/**
 * Class to Export BackWPup Jobs
 */
class BackWPup_Pro_Export_Jobs {

	/**
	 *
	 */
	public static function page_jobs_get_bulk_actions($actions) {

		$actions[ 'export' ] = __( 'Export', 'backwpup' );

		return $actions;
	}

	/**
	 *
	 */
	public static function page_jobs_actions($actions, $jobid, $active) {

		if ( ! $active ) {
			$actions[ 'export' ] = '<a href="' . wp_nonce_url( network_admin_url( 'admin.php' ) . '?page=backwpupjobs&action=export&jobs[]=' . $jobid, 'bulk-jobs' ) . '">' . __( 'Export','backwpup' ) . '</a>';
		}
		return $actions;
	}

	/**
	 *
	 */
	public static function page_jobs_load( $current_action ) {
		global $wpdb;

		if ($current_action != 'export' ) {
			return;
		}
		check_admin_referer( 'bulk-jobs' );

		if ( empty( $_GET[ 'jobs' ] ) && ! is_array( $_GET[ 'jobs' ] ) )
			return;

		//Send export
		header( "Pragma: public" );
		header( "Expires: 0" );
		header( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header( "Content-Type: text/xml" );
		header( "Content-Type: application/force-download" );
		header( "Content-Type: application/octet-stream" );
		header( "Content-Disposition: attachment; filename=" . sanitize_file_name( get_bloginfo( 'name' ) ) . "_BackWPup.xml;" );
		echo "<?xml version=\"1.0\" encoding=\"" . get_bloginfo( 'charset' ) . "\"?>" . PHP_EOL;
		echo "<backwpup_xml_export version=\"" . BackWPup::get_plugin_data( 'Version' ) . "\" plugin=\"BackWPup\" url=\"http://backwpup.com\">" . PHP_EOL;
		echo "\t<config>" . PHP_EOL;
		//get config options
		/* @var wpdb $wpdb */
		if ( is_multisite() ) {
			$option_names = $wpdb->get_col( "SELECT meta_key FROM " . $wpdb->sitemeta . " WHERE meta_key LIKE 'backwpup_cfg_%'" );
		}else {
			$option_names = $wpdb->get_col( "SELECT option_name FROM " . $wpdb->options . " WHERE option_name LIKE 'backwpup_cfg_%'" );
		}
		if ( ! empty( $option_names ) ) {
			foreach ( $option_names as $option_name ) {
				$option_value = get_site_option( $option_name );
				$option_name = str_replace( 'backwpup_cfg_', '', $option_name);
				if ( is_string( $option_value ) )
					$option_value = BackWPup_Encryption::decrypt( $option_value );
				if ( ! empty( $option_value ) )
					echo "\t\t<" . $option_name . "><![CDATA[" . maybe_serialize( $option_value ) . "]]></" . $option_name . ">" . PHP_EOL;
			}
		}
		echo "\t</config>" . PHP_EOL;
		foreach ( $_GET[ 'jobs' ] as $jobid ) {
			echo "\t<job id=\"" . $jobid . "\">" . PHP_EOL;
			foreach ( BackWPup_Option::get_job( $jobid ) as $key => $option ) {
				if ( $key == "activetype" )
					$option = '';
				if ( $key == "logfile" || $key == "starttime" or
					$key == "lastbackupdownloadurl" || $key == "lastruntime" or
					$key == "lastrun"
				)
					continue;
				if ( is_string( $option )  )
					$option = BackWPup_Encryption::decrypt( $option );
				if ( ! empty( $option ) )
					echo "\t\t<" . $key . "><![CDATA[" . maybe_serialize( $option )  . "]]></" . $key . ">" . PHP_EOL;

			}
			echo "\t</job>" . PHP_EOL;
		}
		echo "</backwpup_xml_export>";
		die();
	}

}
