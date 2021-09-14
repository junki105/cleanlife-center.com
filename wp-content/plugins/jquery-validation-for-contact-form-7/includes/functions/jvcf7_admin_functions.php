<?php
function jvcf7_create_menu() {
	add_menu_page( 'jQuery Validation For CF 7', 'jQuery Validation For CF 7 Lite', 'manage_options', 'jvcf7', 'jvcf7_interface', 'dashicons-yes-alt');
}

function jvcf7_interface(){
	$jvcf7_tabs = array(
				'settings' 				=> array('name'=>'Settings','path'=>'jvcf7_settings.php'),
				'instructions' 			=> array('name'=>"Instructions",'path'=>'jvcf7_instructions.php'),
				'pro_version' 			=> array('name'=>"Pro Version",'path'=>'jvcf7_pro_version.php')
	);

	$jvcf7_tabs = apply_filters( 'jvcf7_tabs_filter', $jvcf7_tabs);
	
	include JVCF7_FILE_PATH.'includes/views/jvcf7_main.php';
}


function jvcf7_admin_assets(){
	wp_register_style('jvcf7_admin_css', plugins_url('jquery-validation-for-contact-form-7/includes/assets/css/jvcf7_admin.css'));
  	wp_enqueue_style('jvcf7_admin_css');
  	wp_enqueue_script('jvcf7_jquery_validate', plugins_url('jquery-validation-for-contact-form-7/includes/assets/js/jquery.validate.min.js'), array('jquery'), $GLOBALS['jvcf7_current_version'], true);
}

function jvcf7_plugin_activation(){

	add_option('jvcf7_install_date', date('Y-m-d'));
	add_option('jvcf7_current_version', $GLOBALS['jvcf7_current_version']);

	$jvcf7_default_settings 	= $GLOBALS['jvcf7_default_settings'];
	
	foreach ($jvcf7_default_settings as $option_name => $option_value) {
		add_option($option_name, $option_value);
	}
}

function jvcf7_get_options(){
	$jvcf7_default_settings = $GLOBALS['jvcf7_default_settings'];
		
	foreach ($jvcf7_default_settings as $option_name => $option_value) {
		$GLOBALS['jvcf7_default_settings'][$option_name] = get_option($option_name, $option_value);
	}
}

function jvcf7_save_options(){
	$all_fields = $_POST;
	unset($all_fields['save-jvcf7-options']); // REMOVE submit field
	foreach ($all_fields as $fieldname => $fieldvalue) {
		update_option($fieldname,stripslashes($fieldvalue));
	}

	jvcf7_get_options();

	$return['status']   = 'ok';
	$return['body'] 	= 'Settings Saved';
	return $return;
}