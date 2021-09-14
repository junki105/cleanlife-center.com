<?php
// Exit if accessed directly
if (! defined('WPACU_PRO_DIR')) {
	exit;
}

// This is the URL our updater / license checker pings. This should be the URL of the site with EDD installed
define('WPACU_PRO_PLUGIN_STORE_URL', 'https://gabelivan.com');

// The name of your product. This should match the download name in EDD exactly
define('WPACU_PRO_PLUGIN_STORE_ITEM_NAME', 'Asset CleanUp Pro: Performance WordPress Plugin');

// The ID of the product from the store
define('WPACU_PRO_PLUGIN_STORE_ITEM_ID', 17193);

function wpassetcleanup_pro_plugin_updater()
{
	// retrieve the license key from the DB
	$license_key = trim(get_option( WPACU_PLUGIN_ID . '_pro_license_key'));

	// setup the updater
	new \WpAssetCleanUpPro\PluginUpdater(WPACU_PRO_PLUGIN_STORE_URL, WPACU_PLUGIN_FILE, array(
			'version' 	=> WPACU_PRO_PLUGIN_VERSION,         // current version number
			'license' 	=> $license_key, 		             // license key
			'item_id'   => WPACU_PRO_PLUGIN_STORE_ITEM_ID,   // item ID from the store
			'author' 	=> 'Gabriel Livan',                  // author of this plugin
			'url'       => home_url(),
			'beta'		=> false
		)
	);
}

add_action('admin_init', 'wpassetcleanup_pro_plugin_updater', 0);
