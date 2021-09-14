<?php

/**
 * Class BackWPup_Pro_Wizard_SystemTest
 */
class BackWPup_Pro_Wizard_SystemTest extends BackWPup_Pro_Wizards {

	const ID = 'SYSTEMTEST';
	const CAPABILITY = 'backwpup';

	private $tests_runner;

	public function __construct( $version, BackWPup_System_Tests_Runner $tests_runner ) {

		$this->tests_runner = $tests_runner;
		$this->info = array(
			'ID' => self::ID,
			'name' => esc_html__( 'System Test', 'backwpup' ),
			'description' => esc_html__( 'Wizard to test if BackWPup can work properly', 'backwpup' ),
			'URI' => esc_html__( 'http://backwpup.com', 'backwpup' ),
			'author' => 'Inpsyde GmbH',
			'authorURI' => esc_html__( 'http://inpsyde.com', 'backwpup' ),
			'version' => $version,
			'cap' => 'backwpup',
		);
	}

	public function get_last_button_name( array $wizard_settings ) {

		return __( 'Run tests', 'backwpup' );
	}

	public function get_steps( array $wizard_settings ) {

		$steps = array();
		$steps[0] = array(
			'id' => 'ENV',
			'name' => __( 'Environment', 'backwpup' ),
			'description' => __( 'System Environment', 'backwpup' ),
		);

		return $steps;
	}

	public function page( array $wizard_settings ) {

		if ( $wizard_settings['wizard']['step'] === 'ENV' ) {

			echo '<p>' . esc_html__( 'Test if BackWPup can work without problems.', 'backwpup' ) . '</p>';
		}

	}

	public function inline_js( array $wizard_settings ) {

	}

	public function save( array $wizard_settings ) {

		return $wizard_settings;
	}

	public function execute( array $wizard_settings ) {

		$this->tests_runner->run();
	}
}
