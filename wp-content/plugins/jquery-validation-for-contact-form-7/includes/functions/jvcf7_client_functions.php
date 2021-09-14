<?php
function jvcf7_client_assets(){
	wp_register_style('jvcf7_client_css', plugins_url('jquery-validation-for-contact-form-7/includes/assets/css/jvcf7_client.css'), array(), $GLOBALS['jvcf7_current_version']);
  	wp_enqueue_style('jvcf7_client_css');
  	wp_enqueue_script('jvcf7_jquery_validate', plugins_url('jquery-validation-for-contact-form-7/includes/assets/js/jquery.validate.min.js'), array('jquery'), $GLOBALS['jvcf7_current_version'], true);
  	wp_register_script('jvcf7_validation', plugins_url('jquery-validation-for-contact-form-7/includes/assets/js/jvcf7_validation.js'), '', $GLOBALS['jvcf7_current_version'], true);
  	$scriptData = jvcf7_get_data_for_client_script();
  	wp_localize_script( 'jvcf7_validation', 'scriptData', $scriptData );
  	wp_enqueue_script( 'jvcf7_validation' );  	
}

function jvcf7_get_data_for_client_script(){
	$scriptData = array(			
			'jvcf7_default_settings' 		=> $GLOBALS['jvcf7_default_settings']
	);
	return $scriptData;
}