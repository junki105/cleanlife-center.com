<?php
/**
 * Plugin Name: PublishPress Capabilities Pro
 * Plugin URI: https://publishpress.com/
 * Description: Manage WordPress role definitions, per-site or network-wide. Organizes post capabilities by post type and operation.
 * Author: PublishPress
 * Author URI: https://publishpress.com/
 * Version: 2.0.3
 * Text Domain: capabilities-pro
 * Domain Path: /languages/
 * Min WP Version: 4.9.7
 * Requires PHP: 5.6.20
 * License: GPLv3
 *
 * Copyright (c) 2021 PublishPress
 *
 * ------------------------------------------------------------------------------
 * Based on Capability Manager
 * Author: Jordi Canals
 * Copyright (c) 2009, 2010 Jordi Canals
 * ------------------------------------------------------------------------------
 *
 * @package 	capabilities-pro
 * @author		PublishPress
 * @copyright   Copyright (C) 2021 PublishPress
 * @license		GNU General Public License version 3
 * @link		https://publishpress.com/
 * @version 	2.0.3
 */

if (!defined('CAPSMAN_VERSION')) {
	define('CAPSMAN_VERSION', 			  	'2.0.3');
	define('PUBLISHPRESS_CAPS_VERSION',	  	'2.0.3');
	define('PUBLISHPRESS_CAPS_PRO_VERSION', '2.0.3');
	define('PUBLISHPRESS_CAPS_EDD_ITEM_ID', 44811);
}

foreach ((array)get_option('active_plugins') as $plugin_file) {
	if ( false !== strpos($plugin_file, 'capsman.php') ) {
		add_action('admin_notices', function() {
			$message = __( '<strong>Error:</strong> PublishPress Capabilities cannot function because another copy of Capability Manager is active.', 'capsman-enhanced' );
			echo '<div id="message" class="error fade" style="color: black">' . $message . '</div>';
		});
		return;
	}
}

if (defined('CME_FILE')) {
	return;
}

define ('CME_FILE', __FILE__);
define ('PUBLISHPRESS_CAPS_ABSPATH', __DIR__);

require_once (dirname(__FILE__) . '/includes/functions.php');

// ============================================ START PROCEDURE ==========

// Check required PHP version.
if ( version_compare(PHP_VERSION, '5.4.0', '<') ) {
	// Send an admin warning
	add_action('admin_notices', function() {
		$data = get_plugin_data(__FILE__);
		load_plugin_textdomain('capsman-enhanced', false, basename(dirname(__FILE__)) .'/languages');

		echo '<div class="error"><p><strong>' . __('Warning:', 'capsman-enhanced') . '</strong> '
			. sprintf(__('The active plugin %s is not compatible with your PHP version.', 'capsman-enhanced') .'</p><p>',
				'&laquo;' . $data['Name'] . ' ' . $data['Version'] . '&raquo;')
			. sprintf(__('%s is required for this plugin.', 'capsman-enhanced'), 'PHP-5 ')
			. '</p></div>';
	});
} else {
	global $pagenow;

	// redirect legacy URLs
	if (!empty($_REQUEST['page'])) {
		foreach(['capsman' => 'pp-capabilities', 'capsman-tool' => 'pp-capabilities-backup'] as $find => $replace) {
			if (isset($_REQUEST['page']) && ($find == $_REQUEST['page'])) {
				$location = str_replace("page=$find", "page=$replace", $_SERVER['REQUEST_URI']);
				header( "Location: $location", true);
				exit;
			}
		}
	}

	if (is_admin()) {
		load_plugin_textdomain('capsman-enhanced', false, basename(dirname(__FILE__)) .'/languages');
		load_plugin_textdomain('capabilities-pro', false, basename(dirname(__FILE__)) .'/includes-pro/languages');
	}
}

add_action( 'init', '_cme_init' );
add_action( 'plugins_loaded', '_cme_act_pp_active', 1 );

add_action( 'init', '_cme_cap_helper', 49 );  // Press Permit Cap Helper, registered at 50, will leave caps which we've already defined
//add_action( 'wp_loaded', '_cme_cap_helper_late_init', 99 );	// now instead adding registered_post_type, registered_taxonomy action handlers for latecomers
																// @todo: do this in PP Core also

// *** Pro includes ***
require_once (dirname(__FILE__) . '/includes-pro/load.php');

if (is_admin()) {
	// @todo: refactor
	global $capsman_admin;
	require_once (dirname(__FILE__) . '/includes/functions-admin.php');
	$capsman_admin = new PP_Capabilities_Admin_UI();

	require_once (dirname(__FILE__) . '/includes-pro/functions-admin.php');
	require_once (dirname(__FILE__) . '/includes-pro/admin-load.php');
	new \PublishPress\Capabilities\AdminFiltersPro();
}

if ( is_multisite() )
	require_once ( dirname(__FILE__) . '/includes/network.php' );
