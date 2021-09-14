<?php
if (! isset($activePlugins, $activePluginsToUnload, $pluggableFile, $isFilterRequestedViaQueryString)) {
	exit;
}

// Any /?wpacu_filter_plugins=[...] /?wpacu_only_load_plugins=[...] requests
if ($isFilterRequestedViaQueryString) {
	$wpacuAllowPluginFilterViaQueryStringForGuests = defined( 'WPACU_FILTER_PLUGINS_VIA_QUERY_STRING_FOR_GUESTS' ) && WPACU_FILTER_PLUGINS_VIA_QUERY_STRING_FOR_GUESTS;

	if ( $wpacuAllowPluginFilterViaQueryStringForGuests ) {
		// Non-logged visitors can also do the query string filtering
		include_once WPACU_MU_FILTER_PLUGIN_DIR . '/_common/_filter-via-query-string.php';
	} else {
		// Only the admin can do the query string filtering (default)
		if ( ! defined( 'WPACU_PLUGGABLE_LOADED' ) ) { require_once $pluggableFile; define( 'WPACU_PLUGGABLE_LOADED', true ); }

		if ( function_exists( 'wpacu_current_user_can' ) && wpacu_current_user_can( 'administrator' ) ) {
			include_once WPACU_MU_FILTER_PLUGIN_DIR . '/_common/_filter-via-query-string.php';
		}
	}
}

// Is "Test Mode" enabled and the user is a guest (not admin)? Do not continue with any filtering
// No rules will be triggered including any in "Plugins Manager" as the MU plugin is part of Asset CleanUp Pro
$wpacuSettingsJson = get_option('wpassetcleanup_settings');
$wpacuSettingsDbList = @json_decode($wpacuSettingsJson, true);
$wpacuIsTestMode = isset($wpacuSettingsDbList['test_mode']) && $wpacuSettingsDbList['test_mode'];

if ($wpacuIsTestMode) {
	if (! defined('WPACU_PLUGGABLE_LOADED')) { require_once $pluggableFile; define('WPACU_PLUGGABLE_LOADED', true); }

	if ( ! wpacu_current_user_can('administrator') ) {
		// Return the list as it is (no unloading)
		return $activePlugins;
	}
}

if (! defined('WPACU_EARLY_TRIGGERS_CALLED')) {
	require_once dirname(dirname(WPACU_MU_FILTER_PLUGIN_DIR)) . '/early-triggers.php';
}

if (assetCleanUpNoLoad() || ($wpacuSettingsNoLoadPatternMatched = assetCleanUpHasNoLoadMatches())) {
	if ( isset($wpacuSettingsNoLoadPatternMatched) && $wpacuSettingsNoLoadPatternMatched && isset( $_REQUEST['wpassetcleanup_load'] ) && $_REQUEST['wpassetcleanup_load'] ) {
		$msg = sprintf(__('This page\'s URL is matched by one of the RegEx rules you have in <em>"Settings"</em> -&gt; <em>"Plugin Usage Preferences"</em> -&gt; <em>"Do not load the plugin on certain pages"</em>, thus %s is not loaded on that page and no CSS/JS are to be managed. If you wish to view the CSS/JS manager, please remove the matching RegEx rule and reload this page.', 'wp-asset-clean-up'), WPACU_PLUGIN_TITLE);
		exit($msg);
	}

	// Do not load Asset CleanUp Pro at all due to the rules from /early-triggers.php OR due to the request in the settings (e.g. via /?wpacu_no_load query string)
	// As a result, no other plugin rules (e.g from "Plugins Manager") should be triggered either
	// Stop here with the plugin filtering
	return array_diff($activePlugins, array('wp-asset-clean-up-pro/wpacu.php'));
}

// Is "Test Mode" disabled OR enabled but the admin is viewing the page? Continue
// Fetch the existing rules (unload, load exceptions, etc.)
include_once __DIR__ . '/_filter-from-rules-front.php';
