<?php
/**
 *
 */
abstract class BackWPup_Pro_Wizards {

	public $info = array();

	/**
	 * with steps has the wizard to to
	 *
	 * @param array $wizard_settings
	 *
	 * @return
	 */
	abstract public function get_steps( array $wizard_settings );

	/**
	 * Initiate Wizard Settings
	 *
	 * @param array $wizard_settings
	 *
	 * @return array
	 */
	public function initiate( array $wizard_settings ) {

		return $wizard_settings;
	}

	/**
	 * called on page admin_print_styles
	 *
	 * @param array $wizard_settings
	 */
	public function admin_print_styles( array $wizard_settings ) {

	}

	/**
	 * called on page admin_print_scripts
	 *
	 * @param array $wizard_settings
	 */
	public function admin_print_scripts( array $wizard_settings ) {

	}

	/**
	 * called on page
	 *
	 * @param array $wizard_settings
	 *
	 * @return
	 */
	abstract public function page( array $wizard_settings );

	/**
	 * called on page inline_js
	 *
	 * @param array $wizard_settings
	 */
	public function inline_js( array $wizard_settings ) {

	}

	/**
	 * called on page load to save form data
	 *
	 * @param array $wizard_settings
	 *
	 * @return
	 */
	abstract public function save( array $wizard_settings );

	/**
	 * called if last button clicked
	 *
	 * @param array $wizard_settings
	 *
	 * @return
	 */
	abstract public function execute( array $wizard_settings );

	/**
	 * executed if cancel button clicked
	 *
	 * @param array $wizard_settings
	 */
	public function cancel( array $wizard_settings ) {

	}

	/**
	 * The name of the last button (execute button)
	 *
	 * @param $wizard_settings
	 * @return string
	 */
	abstract public function get_last_button_name( array $wizard_settings ) ;


	/**
	 * Should the wizard run step by step or can yup between steps
	 *
	 * @param $wizard_settings
	 * @return bool
	 */
	public function is_step_by_step( array $wizard_settings ) {

		return TRUE;
	}


	/**
	 * Set Pre configurations
	 *
	 * @param $id
	 * @return array
	 */
	public function get_pre_configurations( $id = NULL ) {

		// every configuration must have a name field in array
		$pre_configurations = array();

		if ( empty( $pre_configurations ) ) {
			return FALSE;
		}

		if ( $id == NULL ) {
			$pre_configurations_names = array();
			foreach ( $pre_configurations as $id => $values ) {
				$pre_configurations_names[ $id ] = $values[ 'name' ];
			}

			return $pre_configurations_names;
		} else {
			return $pre_configurations[ $id ];
		}

	}
}
