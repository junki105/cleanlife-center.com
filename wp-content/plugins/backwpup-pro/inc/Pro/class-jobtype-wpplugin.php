<?php
/**
 *
 */
class BackWPup_Pro_JobType_WPPlugin extends BackWPup_JobType_WPPlugin {


	/**
	 * @param $job_settings
	 */
	public function wizard_page( array $job_settings ) {

		esc_html_e( 'Nothing to configure', 'backwpup' );

	}

	/**
	 * @param $job_settings
	 *
	 * @return array
	 */
	public function wizard_save( array $job_settings ) {

		$job_settings[ 'pluginlistfile' ] = sanitize_file_name( get_bloginfo( 'name' ) ) . '.pluginlist.%Y-%m-%d';
		$job_settings[ 'pluginlistfilecompression' ] = '' ;

		return $job_settings;
	}

}
