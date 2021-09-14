<?php
namespace WpAssetCleanUpPro\OptimiseAssets;

use WpAssetCleanUp\Main;
use WpAssetCleanUp\Misc;
use WpAssetCleanUp\OptimiseAssets\MinifyCss;
use WpAssetCleanUp\OptimiseAssets\OptimizeCss;
use WpAssetCleanUp\Plugin;

/**
 * Class CriticalCssPro
 * @package WpAssetCleanUpPro\OptimiseAssets
 */
class CriticalCssPro
{
	/**
	 *
	 */
	const CRITICAL_CSS_MARKER = '<meta data-name=wpacu-delimiter data-content="ASSET CLEANUP CRITICAL CSS" />';

	/**
	 * This will be later filled with custom post types & custom taxonomies (if any)
	 *
	 * @var string[]
	 */
	public static $allKeyPages = array(
		'homepage', 'posts', 'pages', 'media', 'category', 'tag', 'search', 'author', 'date', '404_not_found'
	);

	/**
	 * CriticalCssPro constructor.
	 */
	public function __construct()
	{
	    // Dashboard's management: "CSS & JS Manager" -> "Manage Critical CSS"
        $this->initInAdmin();

        // Show the critical CSS in the front-end view (for regular visitors)
        $this->initInFrontend();
	}

	/**
	 *
	 */
	public function initInAdmin()
    {
	    if ( ! is_admin() ) {
	        return; // Not within any admin page, so stop here
	    }

		$wpacuSubPage = ( array_key_exists( 'wpacu_sub_page', $_GET ) && $_GET['wpacu_sub_page'] );

		if ( $wpacuSubPage === 'manage_critical_css' ) {
			add_action( 'admin_init', function() {
				self::$allKeyPages = CriticalCssPro::fillAllKeyPages( self::$allKeyPages );
			}, 1 );
		}

	    add_action('admin_init', array($this, 'updateCriticalCss'), 10, 1);
    }

	/**
	 *
	 */
	public function initInFrontend()
    {
        if ( is_admin() ) {
            return; // Not within any frontend page, stop here
        }

	    // Show any critical CSS signature in the front-end view?
	    add_action('wp_head', static function() {
		    if ( Plugin::preventAnyFrontendOptimization() || Main::isTestModeActive() || ( Main::instance()->settings['critical_css_status'] === 'off' ) || ! has_filter('wpacu_critical_css') ) {
		        return;
		    }
		    echo self::CRITICAL_CSS_MARKER; // Add the marker that will be later replaced with the critical CSS
	    }, -PHP_INT_MAX);

	    // 1) Alter the HTML source to prepare it for the critical CSS
	    add_filter('wpacu_alter_source_for_critical_css', array($this, 'alterHtmlSourceForCriticalCss'));

	    // 2) Print the critical CSS
	    add_filter('wpacu_critical_css', array($this, 'showAnyCriticalCss'));
    }

	/**
	 * @param $htmlSource
	 *
	 * @return mixed
	 */
	public static function alterHtmlSourceForCriticalCss($htmlSource)
	{
		// The marker needs to be there
		if (strpos($htmlSource, self::CRITICAL_CSS_MARKER) === false) {
			return $htmlSource;
		}

		$criticalCssData = apply_filters('wpacu_critical_css', array('content' => false, 'minify' => false));

		if ( ! (isset($criticalCssData['content']) && $criticalCssData['content']) ) {
			// No critical CSS set? Return the HTML source as it is with the critical CSS location marker stripped
			return str_replace(self::CRITICAL_CSS_MARKER, '', $htmlSource);
		}

		$keepRenderBlockingList = ( isset( $criticalCssData['keep_render_blocking'] ) && $criticalCssData['keep_render_blocking'] ) ? $criticalCssData['keep_render_blocking'] : array();

		// If just a string was added (one in the list), convert it as an array with one item
		if (! is_array($keepRenderBlockingList)) {
			$keepRenderBlockingList = array($keepRenderBlockingList);
		}

		$doCssMinify            = isset( $criticalCssData['minify'] ) && $criticalCssData['minify']; // leave no room for any user errors in case the 'minify' parameter is unset by mistake
		$criticalCssContent     = OptimizeCss::maybeAlterContentForCssFile( $criticalCssData['content'], $doCssMinify, array( 'alter_font_face' ) );

		$criticalCssStyleTag = '<style '.Misc::getStyleTypeAttribute().' id="wpacu-critical-css">'.$criticalCssContent.'</style>';

		/*
		 * By default the page will have the critical CSS applied as well as non-render blocking LINK tags (non-critical)
		 * For development purposes only, you can append:
		 * 1) /?wpacu_only_critical_css to ONLY load the critical CSS
		 * 2) /?wpacu_no_critical_css to ONLY load the non-render blocking LINK tags (non-critical)
		 * For a cleaner load, &wpacu_no_admin_bar can be added to avoid loading the top admin bar
		*/
		if (array_key_exists('wpacu_only_critical_css', $_GET)) {
			// For debugging purposes: preview how the page would load only with the critical CSS loaded (all LINK/STYLE CSS tags are stripped)
			$htmlSource = preg_replace('#<link[^>]*(stylesheet|(as(\s+|)=(\s+|)(|"|\')style(|"|\')))[^>]*(>)#Umi', '', $htmlSource);
			$htmlSource = preg_replace('@(<style[^>]*?>).*?</style>@si', '', $htmlSource);
			$htmlSource = str_replace(Misc::preloadAsyncCssFallbackOutput(true), '', $htmlSource);
		} else {
			// Convert render-blocking LINK CSS tags into non-render blocking ones
			$cleanerHtmlSource = preg_replace( '/<!--(.|\s)*?-->/', '', $htmlSource );
			$cleanerHtmlSource = preg_replace( '@<(noscript)[^>]*?>.*?</\\1>@si', '', $cleanerHtmlSource );

			preg_match_all( '#<link[^>]*(stylesheet|(as(\s+|)=(\s+|)(|"|\')style(|"|\')))[^>]*(>)#Umi',
				$cleanerHtmlSource, $matchesSourcesFromTags, PREG_SET_ORDER );

			if ( empty( $matchesSourcesFromTags ) ) {
				return $htmlSource;
			}

			foreach ( $matchesSourcesFromTags as $results ) {
				$matchedTag = $results[0];

				if (! empty($keepRenderBlockingList) && preg_match('#('.implode('|', $keepRenderBlockingList).')#Usmi', $matchedTag)) {
					continue;
				}

				// Marked for no alteration or for loading based on the media query match? Then, it's already non-render blocking and it has to be skipped!
				if (preg_match('#data-wpacu-skip([=>/ ])#i', $matchedTag)
				    || strpos($matchedTag, 'data-wpacu-apply-media-query=') !== false) {
					continue;
				}

				if ( strpos ($matchedTag, 'data-wpacu-skip-preload=\'1\'') !== false  ) {
					continue; // skip async preloaded (for debugging purposes or when it is not relevant)
				}

				if ( preg_match( '#rel(\s+|)=(\s+|)([\'"])preload([\'"])#i', $matchedTag ) ) {
					if ( strpos( $matchedTag, 'data-wpacu-preload-css-basic=\'1\'' ) !== false ) {
						$htmlSource = str_replace( $matchedTag, '', $htmlSource );
					} elseif ( strpos( $matchedTag, 'data-wpacu-preload-it-async=\'1\'' ) !== false ) {
						continue; // already async preloaded
					}
				} elseif ( preg_match( '#rel(\s+|)=(\s+|)([\'"])stylesheet([\'"])#i', $matchedTag ) ) {
					$matchedTagAlteredForPreload = str_ireplace(
						array(
							'<link ',
							'rel=\'stylesheet\'',
							'rel="stylesheet"',
							'id=\'',
							'id="',
							'data-wpacu-to-be-preloaded-basic=\'1\''
						),
						array(
							'<link rel=\'preload\' as=\'style\' data-wpacu-preload-it-async=\'1\' ',
							'onload="this.onload=null;this.rel=\'stylesheet\'"',
							'onload="this.onload=null;this.rel=\'stylesheet\'"',
							'id=\'wpacu-preload-',
							'id="wpacu-preload-',
							''
						),
						$matchedTag
					);

					$htmlSource = str_replace( $matchedTag, $matchedTagAlteredForPreload, $htmlSource );
				}
			}
		}

		// For debugging purposes: preview how the page would load without critical CSS & all the non-render blocking CSS files loaded
		// It should show a flash of unstyled content: https://en.wikipedia.org/wiki/Flash_of_unstyled_content
		if (array_key_exists('wpacu_no_critical_css', $_GET)) {
			$criticalCssStyleTag = '';
		}

		return str_replace(
			self::CRITICAL_CSS_MARKER,
			$criticalCssStyleTag . Misc::preloadAsyncCssFallbackOutput(),
			$htmlSource
		);
	}

	/**
	 * @param $args
	 *
	 * @return mixed
	 */
	public function showAnyCriticalCss($args)
	{
	    // Do not continue if critical CSS is globally deactivated
		if (Main::instance()->settings['critical_css_status'] === 'off') {
		    return $args;
        }

		$criticalCssLocationKey = false; // default value until any location is detected (e.g. homepage)

		if (Misc::isHomePage()) {
			$criticalCssLocationKey = 'homepage'; // Main page of the website when just the default site URL is loaded
		} elseif (is_singular()) {
			if (get_post_type() === 'post') { // "Posts" -> "All Posts" -> "View"
				$criticalCssLocationKey = 'posts';
			} elseif (get_post_type() === 'page') { // "Pages" -> "All Pages" -> "View"
				$criticalCssLocationKey = 'pages';
			} elseif (is_attachment()) {
				$criticalCssLocationKey = 'media'; // "Media" -> "Library" -> "View" (rarely used, but added it just in case)
			} else {
				global $post;

				if ( isset( $post->post_type ) && $post->post_type ) {
					$criticalCssLocationKey = 'custom_post_type_' . $post->post_type;
				}
			}
		} elseif (is_category()) {
		    $criticalCssLocationKey = 'category'; // "Posts" -> "Categories" -> "View"
		} elseif (is_tag()) {
		    $criticalCssLocationKey = 'tag'; // "Posts" -> "Tags" -> "View"
		} elseif (is_tax()) {
            global $wp_query;
            $object = $wp_query->get_queried_object();

            if ( isset( $object->taxonomy ) && $object->taxonomy ) {
                $criticalCssLocationKey = 'custom_taxonomy_' . $object->taxonomy;
            }
		} elseif (is_search()) {
			$criticalCssLocationKey = 'search'; // /?s=[keyword_here] in the front-end view
		} elseif (is_author()) {
			$criticalCssLocationKey = 'author'; // /author/demo/ in the front-end view
        } elseif (is_date()) {
			$criticalCssLocationKey = 'date'; // e.g. /2020/10/ in the front-end view
		} elseif (is_404()) {
			$criticalCssLocationKey = '404_not_found'; // e.g. /a-page-slug-that-is-non-existent/
		}

		if (! $criticalCssLocationKey) {
			return $args; // there's no critical CSS to apply on the current page as no critical CSS is set for it
		}

		$allCriticalCssOptions = self::getAllCriticalCssOptions($criticalCssLocationKey);

		if ( ! (isset($allCriticalCssOptions['enable']) && $allCriticalCssOptions['enable']) ) {
			return $args;  // there's no critical CSS to apply on the current page because it's disabled for the current page (location key)
		}

		$criticalCssContentJson = get_option(WPACU_PLUGIN_ID . '_critical_css_location_key_' . $criticalCssLocationKey);
		$criticalCssContentArray = @json_decode($criticalCssContentJson, true);

		// Issues with decoding the JSON content? Do not apply any critical CSS
		if (Misc::jsonLastError() !== JSON_ERROR_NONE) {
			return $args;
		}

		if (isset($allCriticalCssOptions['show_method'], $criticalCssContentArray['content_minified']) && $allCriticalCssOptions['show_method'] === 'minified' && $criticalCssContentArray['content_minified']) {
			$args['content'] = stripslashes($criticalCssContentArray['content_minified']); // serve minified as instructed
		} elseif (isset($criticalCssContentArray['content_original']) && $criticalCssContentArray['content_original']) {
			$args['content'] = stripslashes($criticalCssContentArray['content_original']); // serve the original content which could be already minified
		}

		return $args;
	}

	/**
	 * @param $criticalCssLocationKey
	 *
	 * @return array|mixed
	 */
	public static function getAllCriticalCssOptions($criticalCssLocationKey)
	{
		$criticalCssConfigDbListJson = get_option(WPACU_PLUGIN_ID . '_critical_css_config');

		if ($criticalCssConfigDbListJson) {
			$criticalCssConfigDbList = @json_decode($criticalCssConfigDbListJson, true);

			// Issues with decoding the JSON file? Return an empty list
			if (Misc::jsonLastError() !== JSON_ERROR_NONE) {
				return array();
			}

			// Are there any critical CSS options for the targeted location?
			if ( isset( $criticalCssConfigDbList[$criticalCssLocationKey] ) && ! empty( $criticalCssConfigDbList[$criticalCssLocationKey] ) ) {
				return $criticalCssConfigDbList[$criticalCssLocationKey];
			}
		}

		return array();
	}

	/**
	 * @param $criticalCssConfig
	 *
	 * @return array
	 */
	public static function getAllEnabledLocations($criticalCssConfig)
	{
		foreach (self::$allKeyPages as $locationKey) {
			if ( is_string($locationKey) && isset( $criticalCssConfig[$locationKey]['enable'] ) && $criticalCssConfig[$locationKey]['enable'] ) {
				$allEnabledLocations[] = $locationKey;
			}
		}

		return $allEnabledLocations;
	}

	/**
	 * @param $allPossibleKeys
	 */
	public static function fillAllKeyPages($allPossibleKeys)
	{
		// Any custom post types
		$postTypes = get_post_types( array( 'public' => true ) );

		foreach ( $postTypes as $postType ) {
			if ( ! in_array($postType, $allPossibleKeys) ) {
				$allPossibleKeys['custom_post_type'] = $postType;
			}
		}

		// Any custom taxonomies
		$taxonomies = get_taxonomies(array( 'public' => true ) );

		foreach ( $taxonomies as $taxonomy ) {
			if ( ! in_array($taxonomy, $allPossibleKeys) ) {
				$allPossibleKeys['custom_taxonomy'] = $taxonomy;
			}
		}

		return $allPossibleKeys;
	}

	/**
	 * @param $postTypes
	 *
	 * @return mixed
	 */
	public static function filterCustomPostTypesList($postTypes)
	{
		foreach (array_keys($postTypes) as $postTypeKey) {
			if (in_array($postTypeKey, array('post', 'page', 'attachment'))) {
				unset($postTypes[$postTypeKey]); // no default post types
			}

			// Polish existing values
			if ($postTypeKey === 'product' && Misc::isPluginActive('woocommerce/woocommerce.php')) {
				$postTypes[$postTypeKey] = 'product &#10230; WooCommerce';
			}
		}

		return $postTypes;
	}

	/**
	 * @param $postTypes
	 *
	 * @return mixed
	 */
	public static function filterCustomTaxonomyList($taxonomyList)
	{
		foreach (array_keys($taxonomyList) as $taxonomy) {
			if (in_array($taxonomy, array('category', 'post_tag', 'post_format'))) {
				unset($taxonomyList[$taxonomy]); // no default post types
			}

			// Polish existing values
			if ($taxonomy === 'product_cat' && Misc::isPluginActive('woocommerce/woocommerce.php')) {
				$taxonomyList[$taxonomy] = 'product_cat &#10230; Product\'s Category in WooCommerce';
			}
		}

		return $taxonomyList;
	}

	/**
	 * @param $postTypesList
	 * @param $chosenPostType
     * @param $criticalCssConfig
	 */
	public static function buildCustomPostTypesListLinks($postTypesList, $chosenPostType, $criticalCssConfig)
	{
		?>
		<ul id="wpacu_custom_pages_nav_links">
			<?php
			foreach ($postTypesList as $postTypeKey => $postTypeValue) {
			    $liClass = ($chosenPostType === $postTypeKey) ? 'wpacu-current' : '';
			    $navLink = admin_url('admin.php?page=wpassetcleanup_assets_manager&wpacu_sub_page=manage_critical_css&wpacu_for=custom-post-types&wpacu_current_post_type='.$postTypeKey);
			    $wpacuStatus = (isset($criticalCssConfig['custom_post_type_'.$postTypeKey]['enable']) && $criticalCssConfig['custom_post_type_'.$postTypeKey]['enable']) ? 'wpacu-on' : 'wpacu-off';
			?>
                <li class="<?php echo $liClass; ?>">
                    <a href="<?php echo $navLink; ?>"><?php echo $postTypeValue; ?><span data-wpacu-custom-page-type="<?php echo $postTypeKey; ?>_post_type" class="wpacu-circle-status <?php echo $wpacuStatus; ?>"></span></a>
                </li>
			<?php
			}
			?>
		</ul>
		<?php
	}

	/**
	 * @param $taxonomyList
	 * @param $chosenTaxonomy
	 * @param $criticalCssConfig
	 */
	public static function buildTaxonomyListLinks($taxonomyList, $chosenTaxonomy, $criticalCssConfig)
	{
		?>
        <ul id="wpacu_custom_pages_nav_links">
			<?php
			foreach ($taxonomyList as $taxonomyKey => $taxonomyValue) {
				$liClass = ($chosenTaxonomy === $taxonomyKey) ? 'wpacu-current' : '';
				$navLink = admin_url('admin.php?page=wpassetcleanup_assets_manager&wpacu_sub_page=manage_critical_css&wpacu_for=custom-taxonomy&wpacu_current_taxonomy='.$taxonomyKey);
				$wpacuStatus = (isset($criticalCssConfig['custom_taxonomy_'.$taxonomyKey]['enable']) && $criticalCssConfig['custom_taxonomy_'.$taxonomyKey]['enable']) ? 'wpacu-on' : 'wpacu-off';
				?>
                <li class="<?php echo $liClass; ?>">
                    <a href="<?php echo $navLink; ?>"><?php echo $taxonomyValue; ?><span data-wpacu-custom-page-type="<?php echo $taxonomyKey; ?>_taxonomy" class="wpacu-circle-status <?php echo $wpacuStatus; ?>"></span></a>
                </li>
				<?php
			}
			?>
        </ul>
		<?php
	}

	/**
	 * @param $criticalCssConfig
	 * @param $type
	 *
	 * @return bool
	 */
	public static function isEnabledForAtLeastOnePageType($criticalCssConfig, $type)
    {
        foreach ($criticalCssConfig as $locationConfigKey => $locationConfigValue) {
	        if (strpos($locationConfigKey, $type.'_') === 0 && isset($locationConfigValue['enable']) && $locationConfigValue['enable']) {
                return true;
            }
        }

        return false;
    }

	/**
	 *
	 */
	public function updateCriticalCss()
	{
		if ( ! Misc::getVar('post', 'wpacu_critical_css_submit') ) {
			return;
		}

		$mainKeyForm = WPACU_PLUGIN_ID . '_critical_css';

		check_admin_referer('wpacu_critical_css_update', 'wpacu_critical_css_nonce');

		$locationKey = isset($_POST[$mainKeyForm]['location_key']) ? $_POST[$mainKeyForm]['location_key'] : false;

		if (! $locationKey) {
			return;
		}

		$enable     = isset($_POST[$mainKeyForm]['enable'])      ? $_POST[$mainKeyForm]['enable']  : false;
		$content    = isset($_POST[$mainKeyForm]['content'])     ? $_POST[$mainKeyForm]['content'] : '';
		$showMethod = isset($_POST[$mainKeyForm]['show_method']) ? $_POST[$mainKeyForm]['show_method'] : 'original';

		$optionToUpdate = WPACU_PLUGIN_ID . '_critical_css_config';

		$existingListEmpty = array();
		$existingListJson  = get_option($optionToUpdate);

		$existingListData = Main::instance()->existingList($existingListJson, $existingListEmpty);
		$existingList = $existingListData['list'];

		if ($enable && $content) {
			$existingList[$locationKey]['enable'] = true;
		} elseif (! $enable) {
			$existingList[$locationKey]['enable'] = false;
		}

		$existingList[$locationKey]['show_method'] = $showMethod;

		Misc::addUpdateOption($optionToUpdate, json_encode(Misc::filterList($existingList)));

		$optionToUpdateForCssContent = WPACU_PLUGIN_ID . '_critical_css_location_key_'.$locationKey;

		if ($content) {
			$contentToSaveArray = array();
			$contentOriginal = $content;

			$contentToSaveArray['content_original'] = $contentOriginal;

			if ($showMethod === 'minified') {
				$contentToSaveArray['content_minified'] = MinifyCss::applyMinification($contentOriginal, true);

				if ($contentToSaveArray['content_minified'] === $contentToSaveArray['content_original']) {
					// No change? The content is already minified and there's no point in saving duplicate contents
					unset($contentToSaveArray['content_minified']);
				}
			}

			$optionValue = json_encode($contentToSaveArray);
			Misc::addUpdateOption($optionToUpdateForCssContent, $optionValue);
		} else {
			delete_option($optionToUpdateForCssContent);
		}
	}
}
