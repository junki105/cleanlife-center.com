<?php
namespace WpAssetCleanUp;

use WpAssetCleanUp\OptimiseAssets\OptimizeCommon;
use WpAssetCleanUp\OptimiseAssets\OptimizeCss;
use WpAssetCleanUp\OptimiseAssets\OptimizeJs;

/**
 * Class Plugin
 * @package WpAssetCleanUp
 */
class Plugin
{
	/**
	 *
	 */
	const RATE_URL = 'https://wordpress.org/support/plugin/wp-asset-clean-up/reviews/#new-post';

	/**
	 * Plugin constructor.
	 */
	public function __construct()
	{
	    register_activation_hook(WPACU_PLUGIN_FILE, array($this, 'whenActivated'));
		register_deactivation_hook(WPACU_PLUGIN_FILE, array($this, 'whenDeactivated'));
	}

	/**
	 *
	 */
	public function init()
	{
	    // After fist time activation or in specific situations within the Dashboard
		add_action('admin_init', array($this, 'adminInit'));

		// Show "Settings"
		add_filter('plugin_action_links_'.WPACU_PLUGIN_BASE, array($this, 'actionLinks'));

		if (is_admin() && strpos($_SERVER['REQUEST_URI'], 'update-core.php') !== false) {
			add_action('admin_head', array($this, 'pluginIconUpdateCorePage'));
		}

		// Languages
		add_action('plugins_loaded', array($this, 'loadTextDomain'));

		// [wpacu_pro]
		// e.g. Plugin update failed notice instructions
		add_action('admin_footer', array($this, 'adminFooter'));
		// [/wpacu_pro]

		}

	/**
	 *
	 */
	public function loadTextDomain()
	{
		load_plugin_textdomain('wp-asset-clean-up',
			FALSE,
			basename(WPACU_PLUGIN_DIR) . '/languages/'
		);
	}

	/**
	 * Actions taken when the plugin is activated
	 */
	public function whenActivated()
	{
	    if (WPACU_WRONG_PHP_VERSION === 'true') {
		    $recordMsg = __( '"Asset CleanUp Pro" plugin has not been activated because the PHP version used on this server is below 5.6.',
			    'wp-asset-clean-up' );
		    deactivate_plugins( WPACU_PLUGIN_BASE );
		    error_log( $recordMsg );
		    wp_die($recordMsg);
	    }

		// Is the plugin activated for the first time?
		// Prepare for the redirection to the WPACU_ADMIN_PAGE_ID_START plugin page
		if (! get_transient(WPACU_PLUGIN_ID.'_do_activation_redirect_first_time')) {
			set_transient(WPACU_PLUGIN_ID.'_do_activation_redirect_first_time', 1);
			set_transient(WPACU_PLUGIN_ID . '_redirect_after_activation', 1, 15);
		}

		// Make a record when Asset CleanUp (Pro) is used for the first time
		self::triggerFirstUsage();

		/**
         * Note: Could be /wp-content/uploads/ if constant WPACU_CACHE_DIR was used
         *
		 * /wp-content/cache/asset-cleanup/
		 * /wp-content/cache/asset-cleanup/index.php
		 * /wp-content/cache/asset-cleanup/.htaccess
		 *
		 * /wp-content/cache/asset-cleanup/css/
         * /wp-content/cache/asset-cleanup/css/item/
		 * /wp-content/cache/asset-cleanup/css/index.php
         *
         * /wp-content/cache/asset-cleanup/js/
         * /wp-content/cache/asset-cleanup/js/item/
         * /wp-content/cache/asset-cleanup/js/index.php
         *
		 */
		self::createCacheFoldersFiles(array('css','js'));

		// Do not apply plugin's settings/rules on WooCommerce/EDD Checkout/Cart pages
		if (function_exists('wc_get_page_id')) {
			if ($wooCheckOutPageId = wc_get_page_id('checkout')) {
				Misc::doNotApplyOptimizationOnPage($wooCheckOutPageId);
			}

			if ($wooCartPageId = wc_get_page_id('cart')) {
				Misc::doNotApplyOptimizationOnPage($wooCartPageId);
			}
		}

		if (function_exists('edd_get_option') && $eddPurchasePage = edd_get_option('purchase_page', '')) {
			Misc::doNotApplyOptimizationOnPage($eddPurchasePage);
		}
	}

	/**
	 * Actions taken when the plugin is deactivated
	 */
	public function whenDeactivated()
    {
    	// Clear traces of the plugin which are re-generated once the plugin is enabled
	    // This is good when the admin wants to completely uninstall the plugin
        self::clearAllTransients();
	    self::removeCacheDirWithoutAssets();

	    // Clear other plugin's cache (if they are active)
        OptimizeCommon::clearOtherPluginsCache();
    }

	/**
	 * Removes all plugin's transients, this is usually done when the plugin is deactivated
	 */
	public static function clearAllTransients()
    {
	    global $wpdb;

	    // Remove all transients
	    $transientLikes = array(
		    '_transient_wpacu_',
		    '_transient_'.WPACU_PLUGIN_ID.'_'
	    );

	    $transientLikesSql = '';

	    foreach ($transientLikes as $transientLike) {
		    $transientLikesSql .= " option_name LIKE '".$transientLike."%' OR ";
	    }

	    $transientLikesSql = rtrim($transientLikesSql, ' OR ');

	    $sqlQuery = <<<SQL
SELECT option_name FROM `{$wpdb->prefix}options` WHERE {$transientLikesSql}
SQL;
	    $transientsToClear = $wpdb->get_col($sqlQuery);

	    foreach ($transientsToClear as $transientToClear) {
		    $transientNameToClear = str_replace('_transient_', '', $transientToClear);
		    delete_transient($transientNameToClear);
	    }
    }

	/**
	 * This is usually triggered when the plugin is deactivated
	 * If the caching directory doesn't have any CSS/JS left, it will clear itself
	 * The admin might want to clear all traces of the plugin
	 * If the plugin is re-activated, the caching directory will be re-created automatically
	 */
	public static function removeCacheDirWithoutAssets()
    {
	    $pathToCacheDir    = WP_CONTENT_DIR . OptimizeCommon::getRelPathPluginCacheDir();

	    if (! is_dir($pathToCacheDir)) {
	        return;
        }

	    $pathToCacheDirCss = WP_CONTENT_DIR . OptimizeCss::getRelPathCssCacheDir();
	    $pathToCacheDirJs  = WP_CONTENT_DIR . OptimizeJs::getRelPathJsCacheDir();

	    $allCssFiles = glob( $pathToCacheDirCss . '**/*.css' );
	    $allJsFiles  = glob( $pathToCacheDirJs . '**/*.js' );

	    // Only valid when there's no CSS or JS (not one single file) there
	    if ( count( $allCssFiles ) === 0 && count( $allJsFiles ) === 0 ) {
		    $dirItems = new \RecursiveDirectoryIterator( $pathToCacheDir );

		    $allDirs = array($pathToCacheDir);

		    // First, remove the files
		    foreach ( new \RecursiveIteratorIterator( $dirItems, \RecursiveIteratorIterator::SELF_FIRST,
				    \RecursiveIteratorIterator::CATCH_GET_CHILD ) as $item) {
		        if (is_dir($item)) {
		            $allDirs[] = $item;
                } else {
		            @unlink($item);
                }
		    }

		    usort($allDirs, static function($a, $b) {
			    return strlen($b) - strlen($a);
		    });

		    // Then, remove the empty dirs in descending order (up to the root)
            foreach ($allDirs as $dir) {
                Misc::rmDir($dir);
            }
	    }
    }

	/**
	 * @param $assetTypes
	 */
	public static function createCacheFoldersFiles($assetTypes)
	{
	    foreach ($assetTypes as $assetType) {
	        if ($assetType === 'css') {
		        $cacheDir = WP_CONTENT_DIR . OptimiseAssets\OptimizeCss::getRelPathCssCacheDir();
	        } elseif ($assetType === 'js') {
	            $cacheDir = WP_CONTENT_DIR . OptimiseAssets\OptimizeJs::getRelPathJsCacheDir();
            } else {
	            return;
            }

		    $emptyPhpFileContents = <<<TEXT
<?php
// Silence is golden.
TEXT;

		    $htAccessContents = <<<HTACCESS
<IfModule mod_autoindex.c>
Options -Indexes
</IfModule>
HTACCESS;

		    if ( ! is_dir( $cacheDir ) ) {
			    @mkdir( $cacheDir, 0755, true );
		    }

		    if ( ! is_file( $cacheDir . 'index.php' ) ) {
			    // /wp-content/cache/asset-cleanup/cache/(css|js)/index.php
			    FileSystem::file_put_contents( $cacheDir . 'index.php', $emptyPhpFileContents );
		    }

			if ( ! is_dir( $cacheDir . OptimizeCommon::$optimizedSingleFilesDir ) ) {
				// /wp-content/cache/asset-cleanup/cache/(css|js)/item/
				@mkdir( $cacheDir . OptimizeCommon::$optimizedSingleFilesDir, 0755 );
			}

			// For large inline STYLE & SCRIPT tags
			if ( ! is_dir( $cacheDir . OptimizeCommon::$optimizedSingleFilesDir.'/inline' ) ) {
				// /wp-content/cache/asset-cleanup/cache/(css|js)/item/inline/
			    @mkdir( $cacheDir . OptimizeCommon::$optimizedSingleFilesDir.'/inline', 0755 );
		    }

		    if ( ! is_file( $cacheDir . OptimizeCommon::$optimizedSingleFilesDir.'/inline/index.php' ) ) {
			    // /wp-content/cache/asset-cleanup/cache/(css|js)/item/inline/index.php
			    FileSystem::file_put_contents( $cacheDir . OptimizeCommon::$optimizedSingleFilesDir.'/inline/index.php', $emptyPhpFileContents );
		    }

		    $htAccessFilePath = dirname( $cacheDir ) . '/.htaccess';

		    if ( ! is_file( $htAccessFilePath ) ) {
			    // /wp-content/cache/asset-cleanup/.htaccess
			    FileSystem::file_put_contents( $htAccessFilePath, $htAccessContents );
		    }

		    if ( ! is_file( dirname( $cacheDir ) . '/index.php' ) ) {
			    // /wp-content/cache/asset-cleanup/index.php
			    FileSystem::file_put_contents( dirname( $cacheDir ) . '/index.php', $emptyPhpFileContents );
		    }
	    }

	    // Storage directory for JSON/TEXT files (information purpose)
		$storageDir = WP_CONTENT_DIR . OptimiseAssets\OptimizeCommon::getRelPathPluginCacheDir() . '_storage/';

		if ( ! is_dir($storageDir . OptimizeCommon::$optimizedSingleFilesDir) ) {
			@mkdir( $storageDir . OptimizeCommon::$optimizedSingleFilesDir, 0755, true );
		}

		// Storage directory for the most recent items (these ones are never deleted from the cache)
		$storageDirRecentItems = WP_CONTENT_DIR . OptimiseAssets\OptimizeCommon::getRelPathPluginCacheDir() . '_storage/_recent_items/';

		if ( ! is_dir($storageDirRecentItems) ) {
			@mkdir( $storageDirRecentItems, 0755, true );
		}

		$siteStorageCache = $storageDir.'/'.str_replace(array('https://', 'http://', '//'), '', site_url());

		if ( ! is_dir($storageDir) ) {
			@mkdir( $siteStorageCache, 0755, true );
		}
	}

	/**
	 *
	 */
	public function adminInit()
	{
		if (strpos($_SERVER['REQUEST_URI'], '/plugins.php') !== false && get_transient(WPACU_PLUGIN_ID . '_redirect_after_activation')) {
			// Remove it as only one redirect is needed (first time the plugin is activated)
			delete_transient(WPACU_PLUGIN_ID . '_redirect_after_activation');

			// Do the 'first activation time' redirection
			wp_redirect(admin_url('admin.php?page=' . WPACU_ADMIN_PAGE_ID_START));
			exit();
		}

        $triggerFirstUsage = (strpos($_SERVER['REQUEST_URI'], '/plugins.php') !== false ||
                              strpos($_SERVER['REQUEST_URI'], '/plugin-install.php') !== false ||
                              strpos($_SERVER['REQUEST_URI'], '/options-general.php') !== false ||
                              strpos($_SERVER['REQUEST_URI'], '/update-core.php') !== false);

		// No first usage timestamp set, yet? Set it now!
		if ($triggerFirstUsage) {
			self::triggerFirstUsage();
		}
	}

	/**
	 * @return bool
	 */
	public function isWpacuLiteActive()
	{
		$wpAdminPluginFile = ABSPATH . 'wp-admin/includes/plugin.php';

		if (is_file($wpAdminPluginFile)) {
			require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

			if (in_array('wp-asset-clean-up/wpacu.php', apply_filters('active_plugins', get_option('active_plugins', array())))) {
				return true;
			}
		}

		return false;
	}

	/**
	 * @param $links
	 *
	 * @return mixed
	 */
	public function actionLinks($links)
	{
		$links['getting_started'] = '<a href="admin.php?page=' . WPACU_PLUGIN_ID . '_getting_started">'.__('Getting Started', 'wp-asset-clean-up').'</a>';
		$links['settings']        = '<a href="admin.php?page=' . WPACU_PLUGIN_ID . '_settings">'.__('Settings', 'wp-asset-clean-up').'</a>';

		$licenseStatus  = get_option( WPACU_PLUGIN_ID . '_pro_license_status');

		$activateLicenseSvg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="20px" height="20px" style="position: absolute; left: 0; top: -2px; -ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 20 20"><path d="M10 2c4.42 0 8 3.58 8 8s-3.58 8-8 8-8-3.58-8-8 3.58-8 8-8zm1.13 9.38l.35-6.46H8.52l.35 6.46h2.26zm-.09 3.36c.24-.23.37-.55.37-.96 0-.42-.12-.74-.36-.97s-.59-.35-1.06-.35-.82.12-1.07.35-.37.55-.37.97c0 .41.13.73.38.96.26.23.61.34 1.06.34s.8-.11 1.05-.34z" fill="#c00"/><rect x="0" y="0" width="20" height="20" fill="rgba(0, 0, 0, 0)" /></svg>
SVG;

		$renewExpiredLicense = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" style="position: absolute; left: 0; top: -2px; -ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg); fill: green;" viewBox="0 0 20 20"><rect x="0" fill="none" width="20" height="20"/><g><path d="M10.2 3.28c3.53 0 6.43 2.61 6.92 6h2.08l-3.5 4-3.5-4h2.32c-.45-1.97-2.21-3.45-4.32-3.45-1.45 0-2.73.71-3.54 1.78L4.95 5.66C6.23 4.2 8.11 3.28 10.2 3.28zm-.4 13.44c-3.52 0-6.43-2.61-6.92-6H.8l3.5-4c1.17 1.33 2.33 2.67 3.5 4H5.48c.45 1.97 2.21 3.45 4.32 3.45 1.45 0 2.73-.71 3.54-1.78l1.71 1.95c-1.28 1.46-3.15 2.38-5.25 2.38z"/></g></svg>
SVG;

		if ($licenseStatus !== 'valid') {
		    if ($licenseStatus === 'expired') {
		        $links['renew_license'] = '<a data-wpacu-renewal-link="true" target="_blank" href="https://www.gabelivan.com/customer-dashboard/license-update/" style="font-weight: bold; color: green; position: relative; padding-left: 23px;">'.$renewExpiredLicense.' Renew expired license (save 15%)</a><div style="margin-top: 2px;"><small style="color: green; font-style: italic; font-weight: 400;">An activated license allows you to get the latest updates &amp; bug fixes from the Dashboard</small></div>';
		    } elseif($licenseStatus === 'disabled') {
			    $links['disabled_license'] = '<div style="margin-top: 2px;"><small style="color: #32373c; font-style: italic; font-weight: 400;">It looks like the license has been disabled. It usually happens when a refund has been issued.</small><br /><small style="color: #32373c; font-style: italic; font-weight: 400;"> If you believe this is a mistake and the license should be active, <a target="_blank" href="https://www.gabelivan.com/contact/">please write a support ticket</a>.</small></div>';
		    } else {
		        // Default (for inactive)
			    $links['activate_license'] = '<a href="admin.php?page=' . WPACU_PLUGIN_ID . '_license" style="font-weight: bold; color: darkred; position: relative; padding-left: 23px;">' . $activateLicenseSvg . ' Activate License</a><div style="margin-top: 2px;"><small style="color: darkred; font-style: italic; font-weight: 400;">An activated license allows you to get the latest updates &amp; bug fixes from the Dashboard</small></div>';
		    }
		}

		return $links;
	}

	/**
	 * Make a record when Asset CleanUp (Pro) is used for the first time (if it's not there already)
	 */
	public static function triggerFirstUsage()
	{
		// No first usage timestamp set, yet? Set it now!
		if (! get_option(WPACU_PLUGIN_ID.'_first_usage')) {
			Misc::addUpdateOption(WPACU_PLUGIN_ID . '_first_usage', time());
		}
	}

	// [wpacu_pro]
	/**
	 * Replaces default plugin icon ('Dashicons' type) with the actual Asset CleanUp Pro icon
	 */
	public function pluginIconUpdateCorePage()
	{
		?>
        <style <?php echo Misc::getStyleTypeAttribute(); ?>>
            .wp-asset-clean-up-pro.plugin-title .dashicons.dashicons-admin-plugins {
                position: relative;
            }

            .wp-asset-clean-up-pro.plugin-title .dashicons.dashicons-admin-plugins::before {
                content: '';
                position: absolute;
                background: transparent url('https://ps.w.org/wp-asset-clean-up/assets/icon-256x256.png') no-repeat 0 0;
                height: 100%;
                left: 0;
                top: 0;
                width: 100%;
                background-size: cover;
                max-width: 60px;
                max-height: 60px;
                box-shadow: 0 0 0 0 transparent;
            }
        </style>

        <script type="text/javascript">
            jQuery(document).ready(function($) {
                /* Append the right class to the plugin row so the CSS above would take effect */
                $('input[value="wp-asset-clean-up-pro/wpacu.php"]').parent().next().addClass('wp-asset-clean-up-pro');
            });
        </script>
		<?php
	}

	/**
	 *
	 */
	public function adminFooter()
    {
	    $isPluginsAdminPage = is_admin() && isset($_SERVER['REQUEST_URI']) && (strpos($_SERVER['REQUEST_URI'], '/plugins.php') !== false);

	    if ( ! $isPluginsAdminPage ) {
	        return;
        }

		$wpUpdatesUrl = admin_url( 'update-core.php' );
		$wpacuPluginUpdateAlternativeMsg = <<<HTML
<span style="display: none;" id="wpacu-try-alt-plugin-update">&nbsp;&nbsp;Please try one of the following, depending on the error you got:<br/>
<span style="display: block; margin-bottom: 11px; margin-top: 11px;">&#10141; <strong>"Plugin update failed" error:</strong>&nbsp;Go to <a target="_blank" href="{$wpUpdatesUrl}">"Dashboard" &#187; "Updates"</a>, tick the corresponding plugin checkbox and use the "Update Plugins" button. This will reload the page and there are higher chances the plugin will update, thus avoiding any timeout.</span>
<span style="display: block; margin-bottom: 10px;">&#10141; <strong>"Unauthorized" error: It is likely that you are trying to update the plugin for a website that is not active in the system, although it is marked as "active" on your end (e.g. you moved it from Staging to Live and it remained marked as active in the records). Please go to <a target="_blank" href="https://www.gabelivan.com/customer-dashboard/">Customer Dashboard</a> -&gt; Purchase History -&gt; View Licenses</strong> to manage the active websites or deactivate the license from the website you made the import from and re-activate it here, on the current website.</span>
</span>
HTML;
		echo $wpacuPluginUpdateAlternativeMsg;

		$wpacuProDataPlugin = WPACU_PLUGIN_BASE;
		$wpacuProDataPluginBase = substr(strrchr(WPACU_PLUGIN_BASE, '/'), 1);
		?>
        <script type="text/javascript">
            jQuery(document).ready( function($) {
                $(document).ajaxComplete(function(event, xhr, settings) {
                    var $wpacuTryAltPluginUpdateElement = $('#wpacu-try-alt-plugin-update'),
                        $wpacuPluginUpdateFailedElement = $('tr.plugin-update-tr[data-plugin="<?php echo $wpacuProDataPlugin; ?>"]')
                                                            .find('.update-message.notice-error > p');

                    if ($wpacuPluginUpdateFailedElement.length > 0 && settings.url.indexOf('admin-ajax.php') !== -1
                        && xhr.responseText.indexOf('<?php echo $wpacuProDataPluginBase; ?>') !== -1
                        && xhr.responseText.indexOf('errorMessage') !== -1
                    ) {
                        setTimeout(function() {
                            $wpacuPluginUpdateFailedElement.append($wpacuTryAltPluginUpdateElement);
                            $wpacuTryAltPluginUpdateElement.show();
                        }, 100);
                    }
                });
            });
        </script>
		<?php
    }
    // [/wpacu_pro]

	/**
     * This works like /?wpacu_no_load with a fundamental difference:
     * It needs to be triggered through a very early 'init' / 'setup_theme' action hook after all plugins are loaded, thus it can't be used in /early-triggers.php
     * e.g. in situations when the page is an AMP one, prevent any changes to the HTML source by Asset CleanUp (Pro)
     *
	 * @return bool
	 */
	public static function preventAnyFrontendOptimization()
    {
        // Only relevant if all the plugins are already loaded
	    // and in the front-end view
        if (! defined('WPACU_ALL_ACTIVE_PLUGINS_LOADED') || is_admin()) {
            return false;
        }

        // Perhaps the editor from "Pro" (theme.co) is on
	    if (apply_filters('wpacu_prevent_any_frontend_optimization', false)) {
	        if (! defined('WPACU_PREVENT_ANY_FRONTEND_OPTIMIZATION')) {
		        define( 'WPACU_PREVENT_ANY_FRONTEND_OPTIMIZATION', true );
	        }

		    return true;
	    }

        if (defined('WPACU_PREVENT_ANY_FRONTEND_OPTIMIZATION')) {
	        return WPACU_PREVENT_ANY_FRONTEND_OPTIMIZATION;
        }

	    // e.g. /amp/ - /amp? - /amp/? - /?amp or ending in /amp
	    $isAmpInRequestUri = ((isset($_SERVER['REQUEST_URI']) && (preg_match('/(\/amp$|\/amp\?)|(\/amp\/|\/amp\/\?)/', $_SERVER['REQUEST_URI']))) || (array_key_exists('amp', $_GET)));

	    // Is it an AMP endpoint?
	    if ( ($isAmpInRequestUri && Misc::isPluginActive('accelerated-mobile-pages/accelerated-mobile-pages.php')) // "AMP for WP – Accelerated Mobile Pages"
	         || ($isAmpInRequestUri && Misc::isPluginActive('amp/amp.php')) // "AMP – WordPress plugin"
	         || (function_exists('is_wp_amp') && Misc::isPluginActive('wp-amp/wp-amp.php') && is_wp_amp()) // "WP AMP — Accelerated Mobile Pages for WordPress and WooCommerce" (Premium plugin)
	    ) {
		    define('WPACU_PREVENT_ANY_FRONTEND_OPTIMIZATION', true);
		    return true; // do not print anything on an AMP page
	    }

	    // Some pages are AMP but their URI does not end in /amp
	    if ( Misc::isPluginActive('accelerated-mobile-pages/accelerated-mobile-pages.php')
            || Misc::isPluginActive('amp/amp.php')
            || Misc::isPluginActive('wp-amp/wp-amp.php')
        ) {
		    define('WPACU_DO_EXTRA_CHECKS_FOR_AMP', true);
	    }

	    if (array_key_exists('wpacu_clean_load', $_GET)) {
		    define('WPACU_PREVENT_ANY_FRONTEND_OPTIMIZATION', true);
	        return true;
        }

	    define('WPACU_PREVENT_ANY_FRONTEND_OPTIMIZATION', false);
	    return false;
    }
}
