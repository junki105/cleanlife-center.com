<?php
if (! isset($activePlugins, $activePluginsToUnload)) {
	exit;
}

// [START - Make exception and load the plugin for debugging purposes]
if (isset($_GET['wpacu_load_plugins']) && $_GET['wpacu_load_plugins']) {
	$loadPluginsStrings  = array();
	$loadPluginsRequests = trim( $_GET['wpacu_load_plugins'], ' ,' );

	// Load plugins on page request (if unloaded) for testing purposes
	if ( strpos( $loadPluginsRequests, ',' ) !== false ) {
		// With comma? Could be something like /?wpacu_load_plugins=cache,woocommerce that will load plugins containing "cache" and "woocommerce" if they were unloaded for debugging purposes
		foreach ( explode( ',', $loadPluginsRequests ) as $filterLoadPluginString ) {
			if ( trim( $filterLoadPluginString ) ) {
				$loadPluginsStrings[] = $filterLoadPluginString;
			}
		}
	} else {
		// Without any comma? Could be something like /?wpacu_load_plugins=cache that will forcefully load all plugins containing "cache"
		$loadPluginsStrings[] = $loadPluginsRequests;
	}

	foreach ( $activePlugins as $activePlugin ) {
		// Does the plugin name/path match anything from the query string?
		// Either one if no comma was used, or multiple of them
		foreach ( $loadPluginsStrings as $loadPluginsString ) {
			if ( strpos( $activePlugin, $loadPluginsString ) !== false ) {
				$targetPluginKey = array_search( $activePlugin, $activePluginsToUnload );
				unset( $activePluginsToUnload[ $targetPluginKey ] );
			}
		}
	}
}
// [END - Make exception and load the plugin for debugging purposes]
