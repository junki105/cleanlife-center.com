<?php
/*
 * Plugin Name: Asset CleanUp Pro: Page Speed Booster
 * Plugin URI: https://www.gabelivan.com/items/wp-asset-cleanup-pro/
 * Version: 1.1.9.3
 * Description: Prevent Chosen Scripts & Styles from loading to reduce HTTP Requests and get faster page load | Add "async" & "defer" attributes to loaded JS | Combine/Minify CSS/JS files
 * Author: Gabe Livan
 * Author URI: http://www.gabelivan.com/
 * Text Domain: wp-asset-clean-up
 * Domain Path: /languages
*/

define('WPACU_PRO_PLUGIN_VERSION', '1.1.9.3');

// Make sure the Lite constant is defined in case other plugins (such as Oxygen Builder) use it
if (! defined('WPACU_PLUGIN_VERSION')) {
	define('WPACU_PLUGIN_VERSION', WPACU_PRO_PLUGIN_VERSION);
}

// Exit if accessed directly
if (! defined('ABSPATH')) {
    exit;
}

if (! defined('WPACU_PLUGIN_ID')) {
	define( 'WPACU_PLUGIN_ID', 'wpassetcleanup' ); // unique prefix (same plugin ID name for 'lite' and 'pro')
}

if ( ! defined('WPACU_PLUGIN_TITLE') ) {
	define( 'WPACU_PLUGIN_TITLE', 'Asset CleanUp Pro' ); // a short version of the plugin name
}

if (! defined('WPACU_EARLY_TRIGGERS_CALLED')) {
	require_once __DIR__ . '/early-triggers.php';
}

if (assetCleanUpNoLoad()) {
	return; // do not continue
}

// Is "Lite" version enabled and it wasn't prevented from loading due to an error?
// Do not run both plugins if the constant "WPACU_PLUGIN_CLASSES_PATH" was already set: the Lite version should be kept as doormant if the Pro one is enabled
if (defined('WPACU_PLUGIN_CLASSES_PATH')) {
	return;
}

define('WPACU_PRO_NO_LITE_NEEDED', true); // no LITE parent plugin needed anymore (since 1.0.3)

define('WPACU_PLUGIN_FILE',        __FILE__);
define('WPACU_PLUGIN_BASE',        plugin_basename(WPACU_PLUGIN_FILE));

define('WPACU_ADMIN_PAGE_ID_START', WPACU_PLUGIN_ID . '_getting_started');

// Do not load the plugin if the PHP version is below 5.6
// If PHP_VERSION_ID is not defined, then PHP version is below 5.2.7, thus the plugin is not usable

$wpacuWrongPhp = ((! defined('PHP_VERSION_ID')) || (defined('PHP_VERSION_ID') && PHP_VERSION_ID < 50600));

if (! defined('WPACU_WRONG_PHP_VERSION')) {
	define( 'WPACU_WRONG_PHP_VERSION', ( ( $wpacuWrongPhp ) ? 'true' : 'false' ) );
}

if ($wpacuWrongPhp && is_admin()) { // Dashboard
    add_action('admin_notices', function() {
	    /**
	     * Print the message to the user after the plugin was deactivated
	     */
	    echo '<div class="error is-dismissible"><p>'.

	         sprintf(
		         __('%1$s requires %2$s PHP version installed. You have %3$s.', 'wp-asset-clean-up'),
		         '<strong>'.WPACU_PLUGIN_TITLE.'</strong>',
		         '<span style="color: green;"><strong>5.6+</strong></span>',
		         '<strong>'.PHP_VERSION.'</strong>'
	         ) . ' '.
	         __('If your website is compatible with PHP 7+ (e.g. you can check with your developers or contact the hosting company), it\'s strongly recommended to upgrade to a newer PHP version for a better performance.', 'wp-asset-clean-up').' '.
	         __('Thus, the plugin will not trigger on the front-end view to avoid any possible errors.', 'wp-asset-clean-up').

	         '</p></div>';

	    if (array_key_exists('active', $_GET)) {
		    unset($_GET['activate']);
	    }
    });
} elseif ($wpacuWrongPhp) { // Front
    return;
}

define('WPACU_PLUGIN_DIR',                  __DIR__);
define('WPACU_PLUGIN_CLASSES_PATH',         WPACU_PLUGIN_DIR.'/classes/');
define('WPACU_PLUGIN_URL',                  plugins_url('', WPACU_PLUGIN_FILE));
define('WPACU_PLUGIN_FEATURE_REQUEST_URL', 'https://www.gabelivan.com/asset-cleanup-pro-feature-request/');

// Global Values
define('WPACU_LOAD_ASSETS_REQ_KEY', WPACU_PLUGIN_ID . '_load');

$wpacuGetLoadedAssetsAction = ((isset($_REQUEST[WPACU_LOAD_ASSETS_REQ_KEY]) && $_REQUEST[WPACU_LOAD_ASSETS_REQ_KEY])
                               || (isset($_REQUEST['action']) && $_REQUEST['action'] === WPACU_PLUGIN_ID.'_get_loaded_assets'));
define('WPACU_GET_LOADED_ASSETS_ACTION', $wpacuGetLoadedAssetsAction);

require_once WPACU_PLUGIN_DIR.'/wpacu-load.php';

$isDashboardManageAssets = array_key_exists('page', $_GET) && ($_GET['page'] === WPACU_PLUGIN_ID . '_assets_manager');
$isDashboardCriticalCssPage = ( array_key_exists( 'wpacu_sub_page', $_GET ) && $_GET['wpacu_sub_page'] === 'manage_critical_css' );
$isDashboardPluginsPage = ( array_key_exists( 'wpacu_sub_page', $_GET ) && (strpos($_GET['wpacu_sub_page'], 'manage_plugins_') === 0) );

// In which situations should the composer libraries be loaded?
// Only load them when necessary
if (WPACU_GET_LOADED_ASSETS_ACTION === true ||
    ! is_admin() ||
    (is_admin() && ($isDashboardManageAssets || $isDashboardCriticalCssPage || $isDashboardPluginsPage))) {
	add_action('init', static function() {
		// "Smart Slider 3" & "WP Rocket" compatibility fix | triggered ONLY when the assets are fetched
		if ( ! function_exists('get_rocket_option') && class_exists( 'NextendSmartSliderWPRocket' ) ) {
			function get_rocket_option($option) { return ''; }
		}
	});

	add_action('parse_query', static function() { // very early triggering to set WPACU_ALL_ACTIVE_PLUGINS_LOADED
		if (defined('WPACU_ALL_ACTIVE_PLUGINS_LOADED')) { return; } // only trigger it once in this action
		define('WPACU_ALL_ACTIVE_PLUGINS_LOADED', true);
		\WpAssetCleanUp\Plugin::preventAnyFrontendOptimization();
	}, 1);

	require_once WPACU_PLUGIN_DIR . '/vendor/autoload.php';
}

// No plugin changes are needed when a feed is loaded
add_action('setup_theme', static function() {
	// Only in the front-end view and when a request URI is there (e.g. not triggering the WP environment via an SSH terminal)
	if ( ! isset($_SERVER['REQUEST_URI']) || is_admin() ) {
		return;
	}

	global $wp_rewrite;

	if (isset($wp_rewrite->feed_base) &&
	    $wp_rewrite->feed_base &&
	    strpos($_SERVER['REQUEST_URI'], '/'.$wp_rewrite->feed_base) !== false) {
		$currentPageUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . parse_url(site_url(), PHP_URL_HOST) . $_SERVER['REQUEST_URI'];

		$cleanCurrentPageUrl = $currentPageUrl;
		if (strpos($currentPageUrl, '?') !== false) {
			list($cleanCurrentPageUrl) = explode('?', $currentPageUrl);
		}

		// /{feed_slug_here}/ or /{feed_slug_here}/atom/
		if ($cleanCurrentPageUrl === site_url().'/'.$wp_rewrite->feed_base.'/'
		    || $cleanCurrentPageUrl === site_url().'/'.$wp_rewrite->feed_base.'/atom/') {
			\WpAssetCleanUp\Plugin::preventAnyFrontendOptimization();
		}
	}
});

// [wpacu_pro]
define('WPACU_PRO_DIR',          WPACU_PLUGIN_DIR.'/pro/');
define('WPACU_PRO_CLASSES_PATH', WPACU_PRO_DIR.'classes/');

// Trigger premium functions
// namespace: WpAssetCleanUpPro
require_once WPACU_PRO_DIR.'wpacu-pro-load.php';
// [/wpacu_pro]

