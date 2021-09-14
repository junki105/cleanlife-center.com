<?php
// create custom plugin settings menu
add_action('admin_menu', 'jvcf7p_create_menu');

function jvcf7p_create_menu() {
	add_menu_page( 'Jquery Validation For Contact Form 7 Pro', 'JQ Validation For Contact Form Pro', 'manage_options', 'jvcf7p_settings_page', 'jvcf7p_settings_page', '');
	add_submenu_page( 'jvcf7p_settings_page', 'License &amp; Settings', 'License, Settings & Instructions', 'manage_options', 'jvcf7p_settings_page', 'jvcf7p_settings_page');
	add_submenu_page( 'jvcf7p_settings_page', 'Error Message', 'Error Message', 'manage_options', 'jvcf7p_error_msg_page', 'jvcf7p_error_msg_page');
	add_submenu_page( 'jvcf7p_settings_page', 'Custom Code Validation', 'Custom Code Validation', 'manage_options', 'jvcf7p_custom_code_page', 'jvcf7p_custom_code_page');
	add_action('admin_init', 'register_jvcf7p_settings');
}

function register_jvcf7p_settings() {
	register_setting('jvcf7p-settings-group', 'jvcf7p_show_label_error');
	register_setting('jvcf7p-settings-group', 'jvcf7p_highlight_error_field');
	register_setting('jvcf7p-settings-group', 'jvcf7p_invalid_field_design');
	
	//For validation error message. 
	
	register_setting('jvcf7p-error-settings-group', 'jvcf7p_msg_required');
	register_setting('jvcf7p-error-settings-group', 'jvcf7p_msg_email');
	register_setting('jvcf7p-error-settings-group', 'jvcf7p_msg_url');
	register_setting('jvcf7p-error-settings-group', 'jvcf7p_msg_date');
	register_setting('jvcf7p-error-settings-group', 'jvcf7p_msg_dateISO');
	register_setting('jvcf7p-error-settings-group', 'jvcf7p_msg_number');
	register_setting('jvcf7p-error-settings-group', 'jvcf7p_msg_digits');	
	register_setting('jvcf7p-error-settings-group', 'jvcf7p_msg_alpha_numeric');
	register_setting('jvcf7p-error-settings-group', 'jvcf7p_msg_letters_only');
	register_setting('jvcf7p-error-settings-group', 'jvcf7p_msg_letters_space');	
	register_setting('jvcf7p-error-settings-group', 'jvcf7p_msg_creditcard');
	register_setting('jvcf7p-error-settings-group', 'jvcf7p_msg_phoneUS');
	register_setting('jvcf7p-error-settings-group', 'jvcf7p_msg_equalTo');
	register_setting('jvcf7p-error-settings-group', 'jvcf7p_msg_extension');
	register_setting('jvcf7p-error-settings-group', 'jvcf7p_msg_require_from_group');
	register_setting('jvcf7p-error-settings-group', 'jvcf7p_msg_maxlength');
	register_setting('jvcf7p-error-settings-group', 'jvcf7p_msg_minlength');
	register_setting('jvcf7p-error-settings-group', 'jvcf7p_msg_rangelength');
	register_setting('jvcf7p-error-settings-group', 'jvcf7p_msg_range');
	register_setting('jvcf7p-error-settings-group', 'jvcf7p_msg_max');
	register_setting('jvcf7p-error-settings-group', 'jvcf7p_msg_min');
	register_setting('jvcf7p-error-settings-group', 'jvcf7p_msg_iban');
	register_setting('jvcf7p-error-settings-group', 'jvcf7p_msg_bic');
	register_setting('jvcf7p-error-settings-group', 'jvcf7p_msg_custom_code');
	
} 

function jvcf7p_settings_page() {	
	global $optionValues;
	$jvcf7p_show_label_error 						= $optionValues['jvcf7p_show_label_error'];
	@$jvcf7p_highlight_error_field 					= $optionValues['jvcf7p_highlight_error_field'];
	
	include('includes/jvcf7p_header.php');
	jvcf7p_license_page();
	include('includes/jvcf7p_settings.php');
	include('includes/jvcf7p_instructions.php');
	include('includes/jvcf7p_footer.php');	
}

function jvcf7p_error_msg_page(){
	global $optionValues;
	include('includes/jvcf7p_header.php');
	jvcf7p_license_page();
	include('includes/jvcf7p_error_msg.php');
	include('includes/jvcf7p_footer.php');
}

function jvcf7p_custom_code_page(){
	//global $optionValues;
	include('includes/jvcf7p_header.php');
	jvcf7p_license_page();
	include('includes/jvcf7p_custom_code.php');
	include('includes/jvcf7p_footer.php');
}