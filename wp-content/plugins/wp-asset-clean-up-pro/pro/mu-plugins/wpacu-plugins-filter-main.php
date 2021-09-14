<?php
if (! defined('WP_PLUGIN_DIR') || ! isset($_SERVER['REQUEST_URI'])) {
	return;
}
// For debugging purposes
if (array_key_exists('wpacu_clean_load', $_GET)) {
	// Autoptimize
	$_GET['ao_noptimize'] = $_REQUEST['ao_noptimize'] = '1';

	// LiteSpeed Cache
	if ( ! defined( 'LITESPEED_DISABLE_ALL' ) ) {
		define('LITESPEED_DISABLE_ALL', true);
	}
}

if (! defined('WPACU_PLUGIN_ID')) {
	define( 'WPACU_PLUGIN_ID', 'wpassetcleanup' ); // unique prefix (same plugin ID name for 'lite' and 'pro')
}

if (! defined('WPACU_MU_FILTER_PLUGIN_DIR')) {
	define( 'WPACU_MU_FILTER_PLUGIN_DIR', __DIR__ );
}

/* [START] Filter Plugins Hook */
function wpacuFilterActivePlugins($activePlugins) {
	// When these debugging query strings are used, do not filter any active plugins and load them all
	if (array_key_exists('wpacu_no_plugin_unload', $_GET) || array_key_exists('wpacu_no_load', $_GET)) {
		return $activePlugins;
	}

	// [START - Own Asset CleanPro AJAX calls]
	// Only valid if the constant is defined as some themes are calling automatically functions from other functions
	// e.g. if the theme calls "the_field" without checking if the function (belonging to Advanced Custom Fields) exists
	// then the following filtering will trigger an error, so if the admin decides to enable it, he/she needs to be careful and test it properly
	if (defined('WPACU_SKIP_OTHER_ACTIVE_PLUGINS_ON_ADMIN_AJAX_CALL') && WPACU_SKIP_OTHER_ACTIVE_PLUGINS_ON_ADMIN_AJAX_CALL !== false) {
		$isWpacuOwnAjaxCall = false;
		include_once WPACU_MU_FILTER_PLUGIN_DIR . '/_if-wpacu-own-ajax-calls.php';
		if ( $isWpacuOwnAjaxCall ) {
			return $activePlugins; // only the "Asset CleanUp Pro" plugin should be triggered (no other plugin is relevant in this case)
		}
	}
	// [END - Own Asset CleanPro AJAX calls]

	// This a file that emulates some native WordPress functions that are not available since some calls
	// to verify if te user is logged-in (e.g. for front-end view rules) are not available in MU plugins
	$pluggableFile = WPACU_MU_FILTER_PLUGIN_DIR.'/pluggable-custom.php';

	// It needs to have 'wpacu_filter_plugins', 'wpacu_only_load_plugins' parameters passed to the query string
	$isFilterRequestedViaQueryString = isset($_GET['wpacu_filter_plugins']) || isset($_GET['wpacu_only_load_plugins']);

	// This list is empty by default and it might be filled depending on the rules set in "Plugins Manager"
	// for both /wp-admin/ and the front-end view (for guest visitors)
	$activePluginsToUnload = array();

	// [START - Filter Plugins within the Dashboard]
	if ( ! array_key_exists('wpacu_no_dash_plugin_unload', $_GET) ) {
		$wpacuAllowPluginFilterWithinDashboard = defined( 'WPACU_ALLOW_DASH_PLUGIN_FILTER' ) && WPACU_ALLOW_DASH_PLUGIN_FILTER && is_admin() && ( strpos( $_SERVER['REQUEST_URI'], '/admin-ajax.php' ) === false );

		if ( $wpacuAllowPluginFilterWithinDashboard ) {
			// The user is inside the Dashboard; calls to /wp-admin/admin-ajax.php are excluded
			// Filter $activePlugins loaded within the Dashboard for the targeted pages
			include_once '_filter-from-dash/main-filter-dash.php';
		}
	}
	// [END - Filter Plugins within the Dashboard]

	// [START - Filter Plugins within the frontend view]
	if ( ! is_admin() ) {
		// Do not filter any plugins for REST calls
		$restUrlPrefix      = function_exists( 'rest_get_url_prefix' ) ? rest_get_url_prefix() : 'wp-json';
		$wpacuIsRestRequest = ( strpos( $_SERVER['REQUEST_URI'], '/' . $restUrlPrefix . '/' ) !== false );

		// Do not unload any plugins if an AJAX call is made to any front-end view as some plugins like WooCommerce and Gravity Forms
		// are using index.php?[query string here] type of calls and we don't want to deactivate the plugins in this instance
		// e.g. when the plugin should be unloaded on the homepage view, but not the AJAX call made from a "Checkout" or "Contact" page, etc.
		$wpacuIsAjaxRequest = ( ! empty( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ) === 'xmlhttprequest' );

		if ( $wpacuIsRestRequest || $wpacuIsAjaxRequest || is_bool( $activePlugins ) || empty( $activePlugins ) ) {
			return $activePlugins;
		}

		require_once '_filter-from-front/main-filter-front.php';
	}
	// [END - Filter Plugins within the frontend view]

	// If there are any plugins in $activePluginsToUnload, then $activePlugins will be filtered to avoid loading the targeted plugins
	if ( ! empty($activePluginsToUnload) ) {
		$GLOBALS['wpacu_filtered_plugins'] = $activePluginsToUnload;
		$activePlugins = array_diff($activePlugins, $activePluginsToUnload);
	}

	// Return final list of active plugins (filtered or not)
	return $activePlugins;
}

add_filter('option_active_plugins', 'wpacuFilterActivePlugins', 1, 1);
add_filter('site_option_active_sitewide_plugins', 'wpacuFilterActivePlugins', 1, 1);
/* [END] Filter Plugins Hook */

