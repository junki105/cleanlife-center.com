<?php
/*
Plugin Name: Jquery Validation For Contact Form 7 (Lite)
Plugin URI: http://dnesscarkey.com/jquery-validation/
Description: This plugin integrates jquery validation in contact form 7
Author: Dnesscarkey
Version: 5.1
Author URI: http://dnesscarkey.com/jquery-validation/
*/

define ('JVCF7_FILE_PATH', plugin_dir_path( __FILE__ ));
include JVCF7_FILE_PATH.'includes/jvcf7_config.php';
include JVCF7_FILE_PATH.'includes/functions/jvcf7_admin_functions.php';
include JVCF7_FILE_PATH.'includes/functions/jvcf7_client_functions.php';

add_action('admin_menu', 'jvcf7_create_menu');
add_action('admin_enqueue_scripts', 'jvcf7_admin_assets');
add_action('wp_enqueue_scripts', 'jvcf7_client_assets', 20);
add_action('init', 'jvcf7_get_options');

register_activation_hook( __FILE__, 'jvcf7_plugin_activation' );