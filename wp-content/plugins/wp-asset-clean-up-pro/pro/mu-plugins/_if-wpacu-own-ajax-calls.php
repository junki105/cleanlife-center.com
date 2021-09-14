<?php
if (! isset($activePlugins)) {
	exit;
}

// Are there specific plugin AJAX (admin/ajax-admin.php) calls? Only trigger Asset CleanUp (Pro) plugin as loading other plugins is useless (save resources)
$wpacuIsAjaxRequest = (! empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');
if ( isset( $_POST['action'], $_SERVER['REQUEST_URI'] ) && $wpacuIsAjaxRequest
     && strpos( $_POST['action'], 'wpassetcleanup_' ) !== false
     && strpos( $_SERVER['REQUEST_URI'], '/admin-ajax.php' ) !== false
	 && is_admin() // extra check to make sure /admin/admin-ajax.php is accessed
) {
	$isWpacuOwnAjaxCall = true;

	if (strpos($_POST['action'], '_clear_cache') !== false) {
		// Leave other plugins loaded when the caching is cleared via AJAX calls
		// e.g. If "Cache Enabler" is enabled, its caching is cleared after Asset CleanUp cache is cleared
		return;
	}

	foreach ($activePlugins as $activePlugin) {
		if ($activePlugin === 'wp-asset-clean-up-pro/wpacu.php' || $activePlugin === 'wp-asset-clean-up/wpacu.php') {
			$activePlugins = array(
				'wp-asset-clean-up/wpacu.php',
				'wp-asset-clean-up-pro/wpacu.php'
			);
		}
	}
}
