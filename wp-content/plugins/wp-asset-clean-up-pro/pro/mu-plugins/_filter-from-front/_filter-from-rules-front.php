<?php
if (! isset($activePlugins, $activePluginsToUnload, $wpacuIsTestMode, $pluggableFile)) {
	exit;
}

$pluginsRulesDbListJson = get_option('wpassetcleanup_global_data');

if ($pluginsRulesDbListJson) {
	$pluginsRulesDbList = @json_decode( $pluginsRulesDbListJson, true );

	// Are there any valid load exceptions / unload RegExes? Fill $activePluginsToUnload
	if ( isset( $pluginsRulesDbList[ 'plugins' ] ) && ! empty( $pluginsRulesDbList[ 'plugins' ] ) ) {
		$pluginsRules = $pluginsRulesDbList[ 'plugins' ];

		// Unload site-wide
		foreach ($pluginsRules as $pluginPath => $pluginRule) {
			if (! in_array($pluginPath, $activePlugins)) {
				// Only relevant if the plugin is active
				// Otherwise it's unloaded (inactive) anyway
				continue;
			}

			// 'status' refers to the Unload Status (any option that was chosen)
			if (isset($pluginRule['status']) && ! empty($pluginRule['status'])) {
				if ( ! is_array($pluginRule['status']) ) {
					$pluginRule['status'] = array($pluginRule['status']); // from v1.1.8.3
				}

				// Are there any load exceptions?
				$isLoadExceptionRegExMatch = isset($pluginRule['load_via_regex']['enable'], $pluginRule['load_via_regex']['value'])
				                        && $pluginRule['load_via_regex']['enable'] && wpacuPregMatchInput($pluginRule['load_via_regex']['value'], $_SERVER['REQUEST_URI']);

				if ( $isLoadExceptionRegExMatch ) {
					continue; // Skip to the next plugin as this one has a load exception matching the condition
				}

				// Should the plugin be always loaded as a if the user is logged-in? (priority over the same rule for unloading)
				$isLoadExceptionIfLoggedInEnable = isset($pluginRule['load_logged_in']['enable']) && $pluginRule['load_logged_in']['enable'];

				// Unload the plugin if the user is logged-in?
				$isUnloadIfLoggedInEnable = in_array('unload_logged_in', $pluginRule['status']);

				if (($isLoadExceptionIfLoggedInEnable || $isUnloadIfLoggedInEnable) && ! defined('WPACU_PLUGGABLE_LOADED')) {
					require_once $pluggableFile;
					define('WPACU_PLUGGABLE_LOADED', true);
				}

				if ($isLoadExceptionIfLoggedInEnable && function_exists('wpacu_is_user_logged_in') && wpacu_is_user_logged_in()) {
					continue; // Do not unload it (priority)
				}

				// Unload the plugin if the user is logged-in?
				if ($isUnloadIfLoggedInEnable && (function_exists('wpacu_is_user_logged_in') && wpacu_is_user_logged_in())) {
					$activePluginsToUnload[] = $pluginPath; // Add it to the unload list
				}

				if ( in_array('unload_site_wide', $pluginRule['status']) ) {
					$activePluginsToUnload[] = $pluginPath; // Add it to the unload list
				} elseif ( in_array('unload_via_regex', $pluginRule['status']) ) {
					$isUnloadRegExMatch = isset($pluginRule['unload_via_regex']['value']) && wpacuPregMatchInput($pluginRule['unload_via_regex']['value'], $_SERVER['REQUEST_URI']);
					if ($isUnloadRegExMatch) {
						$activePluginsToUnload[] = $pluginPath; // Add it to the unload list
					}
				}
			}
		}
	}
}

require_once WPACU_MU_FILTER_PLUGIN_DIR.'/_common/_plugin-load-exceptions-via-query-string.php';
