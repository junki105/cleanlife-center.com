<?php
namespace WpAssetCleanUpPro;

use WpAssetCleanUp\Main;
use WpAssetCleanUp\Menu;
use WpAssetCleanUp\Misc;
use WpAssetCleanUp\ObjectCache;
use WpAssetCleanUp\OptimiseAssets\DynamicLoadedAssets;
use WpAssetCleanUp\Plugin;

/**
 * Class MainPro
 * @package WpAssetCleanUpPro
 */
class MainPro
{
	/**
	 * @var bool
	 */
	public $isTaxonomyEditPage = false;

	/**
	 * @var array
	 */
	public $asyncScripts = array();

	/**
	 * @var array
	 */
	public $deferScripts = array();

	/**
	 * @var array
	 */
	public $globalScriptsAttributes = array();

	/**
	 * @var bool
	 */
	public $scriptsAttributesChecked = false;

	/**
	 * @var array
	 */
	public $scriptsAttrsThisPage = array('async' => array(), 'defer' => array());

	/**
     * "not here (exception)" option
	 * @var array
	 */
	public $scriptsAttrsNoLoad = array('async' => array(), 'defer' => array());

	/**
	 * @var array
	 */
	public $settings = array();

	/**
	 * @var null
	 */
	private static $instance;

	/**
	 * @return null
	 */
	public static function instance()
	{
		if (static::$instance === null) {
			static::$instance = new static;
		}

		return static::$instance;
	}

	/**
	 *
	 */
	public function init()
	{
		// "Per Page" Unloaded Assets
		add_filter('wpacu_pro_get_assets_unloaded', array($this, 'getAssetsUnloaded'));

		add_filter('wpacu_pro_get_bulk_unloads', array($this, 'getBulkUnloads'));

		// This filter appends to the existing "all unloaded" list, assets belonging to the is_tax(), is_author() etc. group
		// This way, they will PRINT to the list of unloaded assets for management
		add_filter('wpacu_pro_filter_all_bulk_unloads', array($this, 'filterAllBulkUnloads'));

		// "async", "defer" attribute changes to show up in the management list
		add_filter('wpacu_pro_get_scripts_attributes_for_each_asset', array($this, 'getScriptsAttributesToPrintInList'));

		$positionsClass = new Positions();
		$positionsClass->init();

		// Filter before triggering the actual unloading
		// e.g. "wp_deregister_script", "wp_dequeue_script", "wp_deregister_style", "wp_dequeue_style"
		add_filter('wpacu_filter_styles',  array($this, 'filterStyles'));
		add_filter('wpacu_filter_scripts', array($this, 'filterScripts'));

		add_filter('wpacu_object_data', array($this, 'wpacuObjectData'));

		add_action('current_screen', array($this, 'currentScreen'));

		if (defined('WPACU_ALLOW_ONLY_UNLOAD_RULES') && WPACU_ALLOW_ONLY_UNLOAD_RULES) {
		    return; // stop here, do not do any alteration to the LINK/SCRIPT tags as only the unload rules are allowed
        }

		// Only valid for front-end pages
		if (! is_admin()) {
			add_filter('style_loader_tag', array($this, 'styleLoaderTag'), 10, 2);

			// Add async, defer (if selected) for the loaded scripts
			add_filter('script_loader_tag', array($this, 'scriptLoaderTag'), 10, 2);
		}
	}

	/**
	 * @param $assetsRemoved
	 *
	 * @return mixed|string
	 */
	public function getAssetsUnloaded($assetsRemoved)
	{
		$bulkType = false;

		// Was the request made from the Dashboard? The user (admin or guest) is NOT in the front-end view
		$isTaxonomyCheckWithinDashboard = isset($_REQUEST['tag_id']) && is_admin() && Main::instance()->settings['dashboard_show'];

		/*
		 * NOTE: This list does not include assets that are unloaded site-wide (on all pages)
		 * A 404 page will have the same unloaded assets, as it returns a 404 response (no matter which URL is requested)
		*/

		/*
		  * The code below is triggered ONLY in the front-end view
		  *
		  * Possible pages:
		  *
		  * 404 Page: Not Found (applies to any non-existent request)
		  * Default WordPress Search Page: Applies to any search request
		  * Date Archive Page: Applies to any date
		 *
		*/
		if ( is_404() || Main::isWpDefaultSearchPage() || is_date() || Main::isCustomPostTypeArchivePage() ) {
			$bulkUnloadJson = get_option( WPACU_PLUGIN_ID . '_bulk_unload' );

			@json_decode( $bulkUnloadJson );

			if ( empty( $bulkUnloadJson ) || ! ( Misc::jsonLastError() === JSON_ERROR_NONE ) ) {
				return $assetsRemoved;
			}

			$bulkUnload = json_decode( $bulkUnloadJson, true );

			if (is_404()) {
				$bulkType = '404';     // 404 (Not Found) WordPress page (located in 404.php)
			} elseif (Main::isWpDefaultSearchPage()) {
				$bulkType = 'search';  // Default WordPress Search Page
			} elseif (is_date()) {
				$bulkType = 'date';    // Show posts by date page
			} elseif ($customPostTypeObj = Main::isCustomPostTypeArchivePage()) {
			    $bulkType = 'custom_post_type_archive_' . $customPostTypeObj->name;
            }

			if (! $bulkType) {
				// Shouldn't reach this; it's added just in case there's any conditional missing above
				return $assetsRemoved;
			}

			return json_encode( array(
				'styles'  => isset($bulkUnload['styles'][$bulkType]) ? $bulkUnload['styles'][$bulkType] : array(),
				'scripts' => isset($bulkUnload['scripts'][$bulkType]) ? $bulkUnload['scripts'][$bulkType] : array()
			) );
		}

		// Taxonomy and Author pages check (Front-end View)
		$isTaxonomyView = is_category() || is_tag() || is_tax(); // Category, Tag & Any Custom Taxonomy

		if ( ! $isTaxonomyCheckWithinDashboard && ($isTaxonomyView || is_author()) ) {
			global $wp_query;
			$object = $wp_query->get_queried_object();

            /*
             * Taxonomy page: Could be 'category' (Default WordPress taxonomy), 'product_cat', 'post_tag' (for the tag page) etc.
            */
			if ( isset( $object->taxonomy ) || $isTaxonomyView ) {
				$term_id = $object->term_id;
				return get_term_meta( $term_id, '_' . WPACU_PLUGIN_ID . '_no_load', true );
			}

            /*
             * Author page (individual, not for all authors)
             */
			if ( is_author() ) {
				$author_id = $object->data->ID;
				return get_user_meta($author_id, '_' . WPACU_PLUGIN_ID . '_no_load', true);
			}
        }

		// Taxonomy check (Dashboard view)
		if ($isTaxonomyCheckWithinDashboard) {
			// The "tag_id" value is sent to the AJAX call (it's not the same as 'tag_ID' from the URL of the page)
			$term_id = (int)$_REQUEST['tag_id'];
			return get_term_meta($term_id, '_' . WPACU_PLUGIN_ID . '_no_load', true);
        }

		return $assetsRemoved;
	}

	/**
     * Get bulk unloads for taxonomy and author pages
     *
	 * @param array $data
	 *
	 * @return array
	 */
	public function getBulkUnloads($data = array())
	{
		global $wp_query;

		$object = $wp_query->get_queried_object();

		if (isset($object->taxonomy) && (! is_admin())) {
			// Front-end View
            $data['is_bulk_unloadable']        = true;
            $data['bulk_unloaded']['taxonomy'] = Main::instance()->getBulkUnload('taxonomy', $object->taxonomy);
            $data['bulk_unloaded_type']        = 'taxonomy';
		} elseif (isset($_REQUEST['wpacu_taxonomy']) && Main::instance()->settings['dashboard_show'] && is_admin()) {
		    // Dashboard View
			$data['is_bulk_unloadable']        = true;
			$data['bulk_unloaded']['taxonomy'] = Main::instance()->getBulkUnload('taxonomy', $_REQUEST['wpacu_taxonomy']);
			$data['bulk_unloaded_type']        = 'taxonomy';
        } elseif (is_author()) {
		    // Only in front-end view
			$data['is_bulk_unloadable']        = true;
			$data['bulk_unloaded']['author']   = Main::instance()->getBulkUnload('author');
			$data['bulk_unloaded_type']        = 'author';
		}

		return $data;
	}

	/**
	 * @param $list
	 *
	 * @return array
	 */
	public function filterStyles($list)
	{
		return $this->filterAssets($list, 'styles');
	}

	/**
	 * @param $list
	 *
	 * @return array
	 */
	public function filterScripts($list)
	{
		return $this->filterAssets($list, 'scripts');
	}

	/**
	 * @param $list
	 * @param $type ('styles' or 'scripts')
	 *
	 * @return array
	 */
	public function filterAssets($list, $type)
	{
		// Date, Search, 404 are not relevant here because they don't have an ID like taxonomy and author
        // e.g. the settings on date will be the same on any date

		// Only taxonomies and authors' pages (which are page archives) are relevant
		if (is_archive() && ! is_date()) {
			$bulkUnloads = $this->getBulkUnloads();

			foreach (array('taxonomy', 'author') as $bulkType) {
				if (isset($bulkUnloads['bulk_unloaded'][$bulkType][$type]) && (! empty($bulkUnloads['bulk_unloaded'][$bulkType][$type]))) {
					foreach ($bulkUnloads['bulk_unloaded'][$bulkType][$type] as $assetHandle) {
						$list[] = $assetHandle;
					}
				}
			}
		}

		return $list;
	}

	/**
	 * @param $currentUnloadedAll
	 *
	 * @return mixed
	 */
	public function filterAllBulkUnloads($currentUnloadedAll)
	{
		// Date, Search, 404 are not relevant here because they don't have an ID like taxonomy and author
		// e.g. the settings on date will be the same on any date

        // Only taxonomies and authors' pages (which are page archives) are relevant
		if (is_archive() && ! is_date()) {
			$bulkUnloads = $this->getBulkUnloads();

			foreach (array('styles', 'scripts') as $assetKeyType) {
				foreach (array('taxonomy', 'author') as $bulkType) {
					if ( isset( $bulkUnloads['bulk_unloaded'][$bulkType][ $assetKeyType ] ) && ( ! empty( $bulkUnloads['bulk_unloaded'][$bulkType][ $assetKeyType ] ) ) ) {
						foreach ( $bulkUnloads['bulk_unloaded'][$bulkType][ $assetKeyType ] as $style ) {
							$currentUnloadedAll[ $assetKeyType ][] = $style;
						}
					}
				}
			}
		}

		return $currentUnloadedAll;
	}

	/**
	 * @return bool
	 */
	public function isTaxonomyEditPage()
	{
		if (! $this->isTaxonomyEditPage) {
			$current_screen = \get_current_screen();

			if ( $current_screen->taxonomy !== null
			     && $current_screen->taxonomy
			     && ( strpos( $current_screen->id, 'edit' ) !== false ) ) {
				$this->isTaxonomyEditPage = true;
			}
		}

		return $this->isTaxonomyEditPage;
	}

	/**
	 *
	 */
	public function currentScreen()
    {
        // Do not show it if 'Hide "Asset CleanUp Pro: CSS & JavaScript Manager" meta box' is checked in 'Settings' -> 'Plugin Usage Preferences'
        // Or if the user has no right to view this (e.g. an editor that does not have admin rights, thus no business with any of the plugin's settings)
        if (Main::instance()->settings['hide_assets_meta_box'] || ! Menu::userCanManageAssets()) {
            return;
        }

	    $current_screen = \get_current_screen();

	    if ($current_screen->base === 'term' && isset($current_screen->taxonomy) && $current_screen->taxonomy !== '') {
		    add_action('admin_head', static function() {
		        // Make the CSS/JS List larger
		        ?>
                <style data-wpacu-admin-inline-css="1" <?php echo Misc::getStyleTypeAttribute(); ?>>
                    #edittag {
                        max-width: 96%;
                    }
                    tr.form-field[class*="term-"] > th {
                        width: 200px;
                    }
                    tr.form-field[class*="term-"] > td > * {
                        max-width: 550px;
                    }
                </style>
                <?php
            }, PHP_INT_MAX);

		    add_action ($current_screen->taxonomy . '_edit_form_fields', static function ($tag) {
		        if (! Main::instance()->settings['dashboard_show']) {
                    ?>
                    <tr class="form-field">
                        <th scope="row" valign="top"><label for="wpassetcleanup_list"><?php echo WPACU_PLUGIN_TITLE; ?>: CSS &amp; JavaScript Manager</label></th>
                        <td><?php echo sprintf(__('"Manage in the Dashboard?" is not enabled in the plugin\'s "%sSettings%s", thus, the list is not available.', 'wp-asset-clean-up'), '<a href="'.admin_url('admin.php?page=wpassetcleanup_settings').'">', '</a>'); ?></td>
                    </tr>
                    <?php
                    return;
                }
			    $domGetType = Main::instance()->settings['dom_get_type'];
                $fetchAssetsOnClick = Main::instance()->settings['assets_list_show_status'] === 'fetch_on_click';
			    ?>
                <tr class="form-field">
                    <th scope="row" valign="top"><label for="wpassetcleanup_list"><?php echo WPACU_PLUGIN_TITLE; ?>: CSS &amp; JavaScript Manager</label></th>
                    <td data-wpacu-taxonomy="<?php echo $tag->taxonomy; ?>">
                        <?php
                        $targetUrl = get_term_link($tag, $tag->taxonomy);

                        if ($targetUri = assetCleanUpHasNoLoadMatches($targetUrl)) {
                            ?>
                            <p class="wpacu_verified">
                                <strong>Target URL:</strong> <a target="_blank" href="<?php echo $targetUrl; ?>"><span><?php echo $targetUrl; ?></span></a>
                            </p>
	                        <?php
	                        $msg = sprintf(__('This taxonomy\'s URI <em>%s</em> is matched by one of the RegEx rules you have in <strong>"Settings"</strong> -&gt; <strong>"Plugin Usage Preferences"</strong> -&gt; <strong>"Do not load the plugin on certain pages"</strong>, thus %s is not loaded on that page and no CSS/JS are to be managed. If you wish to view the CSS/JS manager, please remove the matching RegEx rule and reload this page.', 'wp-asset-clean-up'), $targetUri, WPACU_PLUGIN_TITLE);
	                        ?>
                            <p class="wpacu-warning"
                               style="margin: 15px 0 0; padding: 10px; font-size: inherit; width: 99%;">
            <span style="color: red;"
                  class="dashicons dashicons-info"></span> <?php echo $msg; ?>
                            </p>
                            <?php
                        } else {
                        ?>
                            <input type="hidden"
                                   id="wpacu_ajax_fetch_assets_list_dashboard_view"
                                   name="wpacu_ajax_fetch_assets_list_dashboard_view"
                                   value="1" />
                            <?php
                            if ($fetchAssetsOnClick) {
                                ?>
                                <a style="margin: 10px 0; height: 34px; padding: 2px 16px 1px;" href="#" class="button button-secondary" id="wpacu_ajax_fetch_on_click_btn"><span style="font-size: 22px; vertical-align: middle;" class="dashicons dashicons-download"></span>&nbsp;Fetch CSS &amp; JavaScript Management List</a>
                                <?php
                            }
                            ?>
                            <div id="wpacu_fetching_assets_list_wrap" <?php if ($fetchAssetsOnClick) { echo 'style="display: none;"'; } ?>>
                                <div id="wpacu_meta_box_content">
                                    <?php
                                    if ($domGetType === 'direct') {
                                        $wpacuDefaultFetchListStepDefaultStatus   = '<img src="'.admin_url('images/spinner.gif').'" align="top" width="20" height="20" alt="" />&nbsp; Please wait...';
                                        $wpacuDefaultFetchListStepCompletedStatus = '<span style="color: green;" class="dashicons dashicons-yes-alt"></span> Completed';
                                        ?>
                                        <div id="wpacu-list-step-default-status" style="display: none;"><?php echo $wpacuDefaultFetchListStepDefaultStatus; ?></div>
                                        <div id="wpacu-list-step-completed-status" style="display: none;"><?php echo $wpacuDefaultFetchListStepCompletedStatus; ?></div>
                                        <div>
                                            <ul class="wpacu_meta_box_content_fetch_steps">
                                                <li id="wpacu-fetch-list-step-1-wrap"><strong>Step 1</strong>: <?php echo sprintf(__('Fetch the assets from <strong>%s</strong>', 'wp-asset-clean-up'), $targetUrl); ?>... <span id="wpacu-fetch-list-step-1-status"><?php echo $wpacuDefaultFetchListStepDefaultStatus; ?></span></li>
                                                <li id="wpacu-fetch-list-step-2-wrap"><strong>Step 2</strong>: Build the list of the fetched assets and print it... <span id="wpacu-fetch-list-step-2-status"></span></li>
                                            </ul>
                                        </div>
                                    <?php } else { ?>
                                        <div style="margin: 18px 0;">
                                            <img src="<?php echo admin_url('images/spinner.gif'); ?>" align="top" width="20" height="20" alt="" />&nbsp;
                                            <?php echo sprintf(__('Fetching the loaded scripts and styles for <strong>%s</strong>... Please wait...', 'wp-asset-clean-up'), $targetUrl); ?>
                                        </div>
                                    <?php } ?>

                                    <hr>
                                    <div style="margin-top: 20px;">
                                        <strong>Is the fetching taking too long? Please do the following:</strong>
                                        <ul style="margin-top: 8px; margin-left: 20px; padding: 0; list-style: disc;">
                                            <li>Check your internet connection and the actual page that is being fetched to see if it loads completely.</li>
                                            <li>If the targeted page loads fine and your internet connection is working fine, please try managing the assets in the front-end view by going to <em>"Settings" -&gt; "Plugin Usage Preferences" -&gt; "Manage in the Front-end"</em></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        <?php
                        }
                        ?>
                    </td>
                </tr>
			    <?php
		    });
	    }
    }

	/**
	 * @param $wpacu_object_data
	 *
	 * @return mixed
	 */
	public function wpacuObjectData($wpacu_object_data)
    {
	    if (is_admin() && $this->isTaxonomyEditPage() && Misc::getVar('get', 'tag_ID') && Misc::getVar('get', 'taxonomy')) {
		    $wpacu_object_data['tag_id']         = (int)Misc::getVar('get', 'tag_ID');
		    $wpacu_object_data['wpacu_taxonomy'] = Misc::getVar('get', 'taxonomy');
	    }

	    if (isset($wpacu_object_data['page_url']) && is_admin() && Misc::isHttpsSecure()) {
	        $wpacu_object_data['page_url'] = str_replace('http://', 'https://', $wpacu_object_data['page_url']);
	    }

	    return $wpacu_object_data;
    }

    /**
	 * @param $obj
	 * @param $format | 'for_print': Calculates the format in KB / MB  - 'raw': The actual size in bytes
	 * @return string
	 */
	public function getAssetFileSize($obj, $format = 'for_print')
	{
	    if (isset($obj->src) && $obj->src) {
		    $src = $obj->src;
		    $siteUrl = site_url();

		    // Starts with / but not with //
            // Or starts with ../ (very rare cases)
		    $isRelInternalPath = (strpos($src, '/') === 0 && strpos($src, '//') !== 0) || (strpos($src, '../') === 0);

		    // Source starts with '//' - check if the file exists
		    if (strpos($obj->src, '//') === 0) {
			    list ($urlPrefix) = explode('//', $siteUrl);
			    $srcToCheck = $urlPrefix . $obj->src;

			    $hostSiteUrl = parse_url($siteUrl, PHP_URL_HOST);
			    $hostSrc = parse_url($obj->src, PHP_URL_HOST);

			    $siteUrlAltered = str_replace(array($hostSiteUrl, $hostSrc), '{site_host}', $siteUrl);
			    $srcAltered = str_replace(array($hostSiteUrl, $hostSrc), '{site_host}', $srcToCheck);

			    $srcMaybeRelPath = str_replace($siteUrlAltered, '', $srcAltered);

			    $possibleStrips = array('?ver', '?cache=');

			    foreach ($possibleStrips as $possibleStrip) {
				    if ( strpos( $srcMaybeRelPath, $possibleStrip ) !== false ) {
					    list ( $srcMaybeRelPath ) = explode( $possibleStrip, $srcMaybeRelPath );
				    }
			    }

			    if (is_file(ABSPATH . $srcMaybeRelPath)) {
				    $fileSize = filesize(ABSPATH . $srcMaybeRelPath);

				    if ($format === 'raw') {
				    	return (int)$fileSize;
				    }

				    return Misc::formatBytes($fileSize);
			    }
		    }

		    // e.g. /?scss=1 (Simple Custom CSS Plugin)
		    if (str_replace($siteUrl, '', $src) === '/?sccss=1') {
                $customCss = DynamicLoadedAssets::getSimpleCustomCss();
                $sizeInBytes = mb_strlen($customCss);

			    if ($format === 'raw') {
				    return $sizeInBytes;
			    }

                return Misc::formatBytes($sizeInBytes);
		    }

		    // External file? Use a different approach
		    // Return a HTML code that will be parsed via AJAX through JavaScript
		    $isExternalFile = (! $isRelInternalPath &&
		                       (! (isset($obj->wp) && $obj->wp === 1))
		                       && strpos($src, $siteUrl) !== 0);

		    // e.g. /?scss=1 (Simple Custom CSS Plugin) From External Domain
		    // /?custom-css (JetPack Custom CSS)
		    $isLoadedOnTheFly = (strpos($src, '?sccss=1') !== false)
                                || (strpos($src, '?custom-css') !== false);

		    if ($isExternalFile || $isLoadedOnTheFly) {
			    return '<a class="wpacu-external-file-size" data-src="' . $src . '" href="#">🔗 Get File Size</a>'.
                        '<span style="display: none;"><img style="width: 20px; height: 20px;" alt="" align="top" width="20" height="20" src="'.includes_url('images/spinner-2x.gif').'"></span>';
		    }

		    // Local file? Core or from a plugin / theme?
            if (strpos($obj->src, $siteUrl) !== false) {
                // Local Plugin / Theme File
	            // Could be a Staging site that is having the Live URL in the General Settings
	            $src = ltrim(str_replace($siteUrl, '', $obj->src), '/');
            } elseif ((isset($obj->wp) && $obj->wp === 1) || $isRelInternalPath) {
                // Local WordPress Core File
	            $src = ltrim($obj->src, '/');
            }

            $srcAlt = $src;

            if (strpos($src, '../') === 0) {
	            $srcAlt = str_replace('../', '', $srcAlt);
            }

		    $pathToFile = ABSPATH . $srcAlt;

            if (strpos($pathToFile, '?ver') !== false) {
                list($pathToFile) = explode('?ver', $pathToFile);
            }

            // It can happen that the CSS/JS has extra parameters (rare cases)
            foreach (array('.css?', '.js?') as $needlePart) {
	            if (strpos($pathToFile, $needlePart) !== false) {
		            list($pathToFile) = explode('?', $pathToFile);
                }
            }

            if (is_file($pathToFile)) {
	            $sizeInBytes = filesize($pathToFile);

	            if ($format === 'raw') {
	            	return (int)$sizeInBytes;
	            }

	            return Misc::formatBytes($sizeInBytes);
            }

            return '<em>Error: Could not read '.$pathToFile.'</em>';
        }

        if ($obj->handle === 'jquery' && isset($obj->src) && ! $obj->src) {
	        return '"jquery-core" size';
        }

        // External or nothing to be shown (perhaps due to an error)
        return '';
	}

	/**
	 * @param array $data
	 *
	 * @return array
	 */
	public function getScriptAttributesToApplyOnCurrentPage($data = array())
    {
        if ($this->scriptsAttributesChecked || Plugin::preventAnyFrontendOptimization() || Main::instance()->preventAssetsSettings()) {
            return array('async' => $this->asyncScripts, 'defer' => $this->deferScripts);
        }

	    // Could be front-end view or Dashboard view
        // Various conditionals are set below as this method would be trigger on Front-end view (no AJAX call)
        // and from AJAX calls when a post / page / taxonomy or home page are managed within the Dashboard
	    if (isset($data['post_id'])) {
		    // AJAX Call (within the Dashboard)
		    $postId = $data['post_id'];
	    } else {
	        // Regular view (either front-end edit mode or visitor accessing the page)
            // Either page, the ID is fetched in the same way
	        $postId = Main::instance()->getCurrentPostId();
        }

        // Any global loaded attributes?
        $scriptGlobalAttributes = $this->getScriptGlobalAttributes();

	    $this->asyncScripts = $scriptGlobalAttributes['async'];
	    $this->deferScripts = $scriptGlobalAttributes['defer'];

	    $taxID = false;

	    global $wp_query;
	    $object = $wp_query->get_queried_object();

	    if (isset($object->taxonomy)) {
		    $taxID = $object->term_id;
	    } elseif (Main::instance()->settings['dashboard_show'] && is_admin() && isset($_REQUEST['tag_id'])) {
		    $taxID = $_REQUEST['tag_id'];
        }

        $isForSingularPage = (Main::instance()->settings['dashboard_show'] && $postId > 1) || Main::instance()->isSingularPage();
	    $isForFrontPage = (isset($data['wpacu_type']) && $data['wpacu_type'] === 'front_page') || Misc::isHomePage();

        if ($isForSingularPage) {
	        // Post, Page, Custom Post Type, Home page (static page selected as front page)
	        $list = get_post_meta($postId, '_' . WPACU_PLUGIN_ID . '_data', true);
        } elseif ($isForFrontPage) {
            // Home page (latest posts)
	        $list = get_option( WPACU_PLUGIN_ID . '_front_page_data');
        } elseif (is_404() || Main::isWpDefaultSearchPage() || is_date() || Main::isCustomPostTypeArchivePage()) {
            // 404 Not Found, Search Results, Date archive page, Custom Post Type archive page
	        $list = get_option( WPACU_PLUGIN_ID . '_global_data');
        } elseif ($taxID) {
            // Taxonomy page (e.g. category, tag pages)
            $list = get_term_meta($taxID, '_' . WPACU_PLUGIN_ID . '_data', true);
        } elseif (is_author()) {
            // Author pages (e.g /author/author-name-here/)§
	        $list = get_user_meta($object->data->ID, '_' . WPACU_PLUGIN_ID . '_data', true);
        }

        if (! (isset($list) && $list)) {
	        return array('async' => $this->asyncScripts, 'defer' => $this->deferScripts);
        }

        $targetKeyNoLoads = 'scripts_attributes_no_load';

	    $list = json_decode($list, ARRAY_A);

	    if (Misc::jsonLastError() === JSON_ERROR_NONE) {
	        if ($isForSingularPage || $isForFrontPage || $taxID || is_author()) {
		        $targetLocation        = isset($list['scripts']) ? $list['scripts'] : array();
		        $targetLocationNoLoads = isset($list[$targetKeyNoLoads]) ? $list[$targetKeyNoLoads] : array();
            } elseif (is_404()) {
	            $targetLocation        = isset($list['scripts']['404']) ? $list['scripts']['404'] : array();
		        $targetLocationNoLoads = isset($list[$targetKeyNoLoads]['404']) ? $list[$targetKeyNoLoads]['404'] : array();
	        } elseif (Main::isWpDefaultSearchPage()) {
	            $targetLocation        = isset($list['scripts']['search']) ? $list['scripts']['search'] : array();
		        $targetLocationNoLoads = isset($list[$targetKeyNoLoads]['search']) ? $list[$targetKeyNoLoads]['search'] : array();
	        } elseif (is_date()) {
		        $targetLocation        = isset($list['scripts']['date']) ? $list['scripts']['date'] : array();
		        $targetLocationNoLoads = isset($list[$targetKeyNoLoads]['date']) ? $list[$targetKeyNoLoads]['date'] : array();
	        } elseif ($customPostTypeObj = Main::isCustomPostTypeArchivePage()) {
	            $targetKey             = 'custom_post_type_archive_' . $customPostTypeObj->name;
                $targetLocation        = isset($list['scripts'][$targetKey]) ? $list['scripts'][$targetKey] : array();
		        $targetLocationNoLoads = isset($list[$targetKeyNoLoads][$targetKey]) ? $list[$targetKeyNoLoads][$targetKey] : array();
	        }

	        if (isset($targetLocation) && ! empty($targetLocation)) {
			    foreach ( $targetLocation as $asset => $values ) {
				    if ( isset( $values['attributes'] ) && ! empty( $values['attributes'] ) ) {
					    if ( in_array( 'async', $values['attributes'] ) ) {
						    $this->asyncScripts[] = $this->scriptsAttrsThisPage['async'][] = $asset;
					    }

					    if ( in_array( 'defer', $values['attributes'] ) ) {
						    $this->deferScripts[] = $this->scriptsAttrsThisPage['defer'][] = $asset;
					    }
				    }
			    }
		    }

		    // Any load exceptions? "not here (exception)" option
		    if (isset($targetLocationNoLoads) && ! empty($targetLocationNoLoads)) {
			    foreach ($targetLocationNoLoads as $handle => $values) {
				    if (in_array('async', $values)) {
					    $this->scriptsAttrsNoLoad['async'][] = $handle;
				    }

				    if (in_array('defer', $values)) {
					    $this->scriptsAttrsNoLoad['defer'][] = $handle;
				    }
			    }
		    }
	    }

	    $this->scriptsAttributesChecked = true;

	    if ($wpacuLoadJsAsyncHandles = Misc::getVar('get', 'wpacu_js_async')) {
		    if (strpos($wpacuLoadJsAsyncHandles, ',') !== false) {
			    foreach (explode(',', $wpacuLoadJsAsyncHandles) as $wpacuLoadJsAsyncHandle) {
				    if (trim($wpacuLoadJsAsyncHandle)) {
					    $this->asyncScripts[] = $wpacuLoadJsAsyncHandle;
				    }
			    }
		    } else {
			    $this->asyncScripts[] = $wpacuLoadJsAsyncHandles;
		    }
	    }

	    if ($wpacuLoadJsDeferHandles = Misc::getVar('get', 'wpacu_js_defer')) {
	        if (strpos($wpacuLoadJsDeferHandles, ',') !== false) {
	            foreach (explode(',', $wpacuLoadJsDeferHandles) as $wpacuLoadJsDeferHandle) {
	                if (trim($wpacuLoadJsDeferHandle)) {
		                $this->deferScripts[] = $wpacuLoadJsDeferHandle;
	                }
                }
            } else {
		        $this->deferScripts[] = $wpacuLoadJsDeferHandles;
	        }
	    }

	    return array('async' => $this->asyncScripts, 'defer' => $this->deferScripts);
    }

	/**
     * This fetches the list of applied attributes (defer, async) that will be used
     * on the scripts management list
     *
	 * @param $data
	 *
	 * @return mixed
	 */
	public function getScriptsAttributesToPrintInList($data)
    {
	    // Dashboard view? Fetch the attributes as it's on AJAX mode view (via Dashboard)
        if (! Main::instance()->isFrontendEditView) {
	        $this->scriptsAttrsThisPage = $this->getScriptAttributesToApplyOnCurrentPage($data);
        }

	    // If on front-end view getScriptAttributesToApplyOnCurrentPage() was already called
	    // and $this->scriptsAttrsThisPage populated within method getScriptAttributesToApplyOnCurrentPage()

        $data['scripts_attributes'] = array(
	        'everywhere'       => $this->getScriptGlobalAttributes(),
            'on_this_page'     => $this->scriptsAttrsThisPage,
            'not_on_this_page' => $this->scriptsAttrsNoLoad
        );

        return $data;
    }

	/**
	 * @return array
	 */
	public function getScriptGlobalAttributes()
    {
        if (! empty($this->globalScriptsAttributes)) {
            return $this->globalScriptsAttributes;
        }

	    $list = get_option( WPACU_PLUGIN_ID . '_global_data');

	    $asyncGlobalScripts = $deferGlobalScripts = array();

	    // Empty list, no attributes to apply
	    if (! $list) {
		    $this->globalScriptsAttributes = array('async' => $asyncGlobalScripts, 'defer' => $deferGlobalScripts);
		    return $this->globalScriptsAttributes;
        }

        $list = json_decode($list, ARRAY_A);

	    // Is it in a valid JSON format and global attributes (applied everywhere) are stored there?
        if (Misc::jsonLastError() === JSON_ERROR_NONE && (isset($list['scripts']['everywhere']) && !empty($list['scripts']['everywhere']))) {
            foreach ($list['scripts']['everywhere'] as $asset => $values) {
                if (isset($values['attributes']) && ! empty($values['attributes'])) {
                    if (in_array('async', $values['attributes'])) {
                        $asyncGlobalScripts[] = $asset;
                    }

                    if (in_array('defer', $values['attributes'])) {
                        $deferGlobalScripts[] = $asset;
                    }
                }
            }
        }

	    $this->globalScriptsAttributes = array('async' => $asyncGlobalScripts, 'defer' => $deferGlobalScripts);

	    return $this->globalScriptsAttributes;
    }

	/**
	 * @return array
	 */
	public static function getMediaQueriesLoad()
	{
	    if ($handleData = ObjectCache::wpacu_cache_get('wpacu_media_queries_load')) {
	        return $handleData;
	    }

		$handleData = array('styles' => array(), 'scripts' => array());
		$globalKey = 'media_queries_load';

		$handleDataListJson = get_option(WPACU_PLUGIN_ID . '_global_data');

		if ($handleDataListJson) {
			$handleDataList = @json_decode($handleDataListJson, true);

			// Issues with decoding the JSON file? Return an empty list
			if (Misc::jsonLastError() !== JSON_ERROR_NONE) {
				ObjectCache::wpacu_cache_add('wpacu_media_queries_load', $handleData);
				return $handleData;
			}

			// Are new positions set for styles and scripts?
			foreach (array('styles', 'scripts') as $assetKey) {
				if ( isset( $handleDataList[$assetKey][$globalKey] ) && ! empty( $handleDataList[$assetKey][$globalKey] ) ) {
					$handleData[$assetKey] = $handleDataList[$assetKey][$globalKey];
				}
			}
		}

		ObjectCache::wpacu_cache_add('wpacu_media_queries_load', $handleData);

		return $handleData;
	}

	/**
	 * @param $tag
	 * @param $handle
	 * @return mixed
	 */
	public function styleLoaderTag($tag, $handle)
	{
		$mediaQueriesLoad = self::getMediaQueriesLoad();

		if (isset($mediaQueriesLoad['styles'][$handle]['enable'], $mediaQueriesLoad['styles'][$handle]['value']) &&
		    $mediaQueriesLoad['styles'][$handle]['enable'] && $mediaQueriesLoad['styles'][$handle]['value']
		) {
			$reps = array( '<link ' => '<link data-wpacu-apply-media-query=\'' . esc_attr($mediaQueriesLoad['styles'][$handle]['value']) . '\' ' );
			$tag = str_replace( array_keys( $reps ), array_values( $reps ), $tag );
			ObjectCache::wpacu_cache_add_to_array('wpacu_css_media_queries_load_current_page', $handle);
		}

		return $tag;
	}

	/**
	 * @param $tag
	 * @param $handle
	 * @return mixed
	 */
	public function scriptLoaderTag($tag, $handle)
	{
		$attrs = $this->getScriptAttributesToApplyOnCurrentPage();

		foreach (array('async', 'defer') as $attrType) {
			if (in_array($handle, $attrs[$attrType]) && (! in_array($handle, $this->scriptsAttrsNoLoad[$attrType]))) {
				if (array_key_exists('wpacu_no_'.$attrType, $_GET)) {
					continue; // prevent adding any async/defer attributes for debugging purposes
				}
				$tag = str_replace(' src=', ' '.$attrType.'=\''.$attrType.'\' src=', $tag);
				ObjectCache::wpacu_cache_add_to_array('wpacu_js_media_queries_load_current_page', $handle);
			}
		}

		$mediaQueriesLoad = self::getMediaQueriesLoad();

		if (isset($mediaQueriesLoad['scripts'][$handle]['enable'], $mediaQueriesLoad['scripts'][$handle]['value']) &&
		    $mediaQueriesLoad['scripts'][$handle]['enable'] && $mediaQueriesLoad['scripts'][$handle]['value']
		) {
			$reps = array( '<script ' => '<script data-wpacu-apply-media-query=\'' . esc_attr($mediaQueriesLoad['scripts'][$handle]['value']) . '\' ' );
			$tag = str_replace( array_keys( $reps ), array_values( $reps ), $tag );
		}

		return $tag;
	}
}
