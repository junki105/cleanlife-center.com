<?php
namespace WpAssetCleanUpPro;

use WpAssetCleanUp\Main;
use WpAssetCleanUp\Menu;
use WpAssetCleanUp\Misc;
use WpAssetCleanUp\CleanUp;
use WpAssetCleanUp\ObjectCache;
use WpAssetCleanUp\OptimiseAssets\MinifyJs;
use WpAssetCleanUp\OptimiseAssets\OptimizeCommon;
use WpAssetCleanUp\OptimiseAssets\OptimizeCss;
use WpAssetCleanUp\Plugin;

/**
 * It handles the moving from HEAD to BODY (and vice-versa) of the Stylesheets
 * as well as converts stylesheet LINK tags from BODY into deferred loading CSS
 *
 * Class Positions
 * @package WpAssetCleanUpPro
 */
class Positions
{
	/**
	 * Printed in BODY
	 */
	const DEL_STYLES_MOVED_FROM_HEAD_TO_BODY = '<span style="display: none;" data-name=wpacu-generator data-content="ASSET CLEANUP STYLES MOVED FROM HEAD TO BODY"></span>';

	/**
	 * Printed in HEAD
	 */
	const DEL_STYLES_MOVED_FROM_BODY_TO_HEAD = '<meta name="wpacu-generator" content="ASSET CLEANUP STYLES MOVED FROM BODY TO HEAD">';

	/**
	 * Printed in BODY (after opening tag)
	 */
	const DEL_MAYBE_PLACE_JQUERY_RIGHT_AFTER_BODY = '<span style="display: none;" data-name=wpacu-generator data-content="ASSET CLEANUP JQUERY NEW LOCATION"></span>';

	/**
	 *
	 */
	public function init()
	{
		if (self::preventMoving()) {
			return;
		}

		// Any positions changed?
		add_filter('wpacu_pro_get_position_new', array($this, 'getAssetPositionNew'), 10, 2);
		add_action('wpacu_pro_mark_styles_to_load_in_new_position', array($this, 'markStylesToLoadInNewPosition'), 10, 1);
		add_filter('wpacu_pro_append_styles_moved_to_new_position',  array($this, 'appendStylesMovedToNewPosition')); // filter HTML source
		add_filter('wpacu_pro_new_positions_assets', array($this, 'filterNewPositionsList'), 10, 1); // filter list retrieved from the database
		add_action('wpacu_pro_mark_scripts_to_load_in_new_position', array($this, 'markScriptsToLoadInNewPosition'));

		add_filter('wpacu_pro_maybe_move_jquery_after_body_tag', array($this, 'maybeMovejQueryAfterBodyTag'));

		// Any CSS already loaded in the footer?
		add_filter('wpacu_pro_defer_footer_styles', array($this, 'deferFooterStyles')); // filter HTML source
	}

	/**
	 * Signatures in case there are position changes for the styles
	 * If not, the HTML signatures will be just be removed
	 */
	public static function setSignatures()
	{
		add_action('wp_head', static function() {
			if ( Plugin::preventAnyFrontendOptimization() || Main::instance()->preventAssetsSettings() || self::preventMoving() ) {
				return;
			}

			echo self::DEL_STYLES_MOVED_FROM_BODY_TO_HEAD;
		});

		add_action('wp_footer', static function() {
			if ( Plugin::preventAnyFrontendOptimization() || Main::instance()->preventAssetsSettings() || self::preventMoving() ) {
				return;
			}

			echo self::DEL_STYLES_MOVED_FROM_HEAD_TO_BODY;
		});
	}

	/**
	 * @param $htmlSource
	 *
	 * @return mixed|void
	 */
	public static function doChanges($htmlSource)
	{
		$htmlSource = apply_filters('wpacu_pro_append_styles_moved_to_new_position', $htmlSource);

		/*
		 * Extra Measures to remove the CSS link tags from their original location
		 * in case they were already copied to their new one
		 */
		// Remove Styled from their original location as they were already copied to the new location
		// This is done in case replacing the tag didn't work as it could have been altered by caching plugins
		$cleanStylesIds = ObjectCache::wpacu_cache_get('wpacu_styles_positions_handle_ids');

		if (! empty($cleanStylesIds)) {
			foreach ($cleanStylesIds as $cleanStyleId => $cleanStyleSrc) {
				$htmlSource = CleanUp::cleanLinkTagFromHtmlSource("id='".$cleanStyleId."'", $htmlSource);
				$htmlSource = CleanUp::cleanLinkTagFromHtmlSource('id="'.$cleanStyleId.'"', $htmlSource);
				$htmlSource = CleanUp::cleanLinkTagFromHtmlSource($cleanStyleSrc, $htmlSource);
			}
		}

		// Rename the changed tags to avoid the deletion above (these are the moved tags)
		return str_replace('<assetcleanuplink', '<link', $htmlSource);
	}

	/**
	 * Get the list of any position changes for the assets
	 *
	 * @param $filtered bool
	 * If set to false, it will return the settings from the database as they are
	 * The filtered version might have styles removed in case "Optimize CSS Delivery" from WP Rocket is enabled
	 *
	 * @return array|mixed|object
	 */
	public function getAssetsPositions($filtered = true)
	{
		$newPositionsAssets = array('styles' => array(), 'scripts' => array());

		$newPositionsListJson = get_option(WPACU_PLUGIN_ID . '_global_data');

		if ($newPositionsListJson) {
			$newPositionsList = @json_decode($newPositionsListJson, true);

			// Issues with decoding the JSON file? Return an empty list
			if (Misc::jsonLastError() !== JSON_ERROR_NONE) {
				return $newPositionsAssets;
			}

			if ($filtered) {
				$newPositionsList = apply_filters('wpacu_pro_new_positions_assets', $newPositionsList);
			}

			// Are new positions set for styles and scripts?
			foreach (array('styles', 'scripts') as $assetKey) {
				if ( isset( $newPositionsList[$assetKey]['positions'] ) && ! empty( $newPositionsList[$assetKey]['positions'] ) ) {
					$newPositionsAssets[$assetKey] = $newPositionsList[$assetKey]['positions'];
				}
			}
		}

		/*
		 * On page request, for testing purposes CSS/JS can be moved from HEAD to BODY and vice-versa
		   e.g. /?wpacu_css_move_to_body=handle-here,another-handle | /?wpacu_js_move_to_body=handle-here,another-handle
		        /?wpacu_css_move_to_head=handle-here,another-handle | /?wpacu_js_move_to_head=handle-here,another-handle
			* Note: A single handle can be used; Multiple handle names are separated by comma
		*/
		foreach (array('head', 'body') as $wpacuChosenPosition) {
			foreach (array('css', 'js') as $assetExt) {
				if ($wpacuCssMoveToNewPositionHandles = Misc::getVar('get', 'wpacu_'.$assetExt.'_move_to_' . $wpacuChosenPosition)) {
					$assetType = ($assetExt === 'css') ? 'styles' : 'scripts';

					if (strpos($wpacuCssMoveToNewPositionHandles, ',') !== false) {
						foreach (explode(',', $wpacuCssMoveToNewPositionHandles) as $wpacuCssMoveToBodyHandle) {
							if (trim($wpacuCssMoveToBodyHandle)) {
								$newPositionsAssets[$assetType][$wpacuCssMoveToBodyHandle] = $wpacuChosenPosition;
							}
						}
					} else {
						$newPositionsAssets[$assetType][$wpacuCssMoveToNewPositionHandles] = $wpacuChosenPosition;
					}
				}
			}
		}

		return $newPositionsAssets;
	}

	/**
	 * @param $dataAssetObj
	 * @param string $for ('styles' or 'scripts')
	 *
	 * @return object
	 */
	public function getAssetPositionNew($dataAssetObj, $for = 'styles')
	{
		// Get the assets positions from the database (before any filtering)
		$assetsPositions = $this->getAssetsPositions(false);

		// Was the default position changed?
		if (isset($assetsPositions[$for][$dataAssetObj->handle]) && in_array($assetsPositions[$for][$dataAssetObj->handle], array('head', 'body'))) {
			$dataAssetObj->position_new = $assetsPositions[$for][$dataAssetObj->handle];
		}

		return $dataAssetObj;
	}

	/**
	 * @param $unloadedList
	 *
	 * @return void
	 */
	public function markStylesToLoadInNewPosition($unloadedList)
	{
		if (array_key_exists('wpacu_no_css_position_change', $_GET)) {
			return;
		}

		global $wp_styles;

		// Are there any styles that have their location changed from HEAD to BODY?
		$assetsPositions = $this->getAssetsPositions();

		// Keep only the styles that are loaded (not selected for unload in any way)
		if (! empty($unloadedList)) {
			foreach ($unloadedList as $unloadedStyleHandle) {
				unset($assetsPositions['styles'][$unloadedStyleHandle]);
			}
		}

		if ( ! (isset($assetsPositions['styles']) && ! empty($assetsPositions['styles']))) {
			return;
		}

		$cdnUrls = OptimizeCommon::getAnyCdnUrls();
		$cdnUrlForCss = isset($cdnUrls['css']) ? $cdnUrls['css'] : false;

		$wpStylesRegistered = $wp_styles->registered;

		$stylesNewPositionsTags = $stylesNewPositionsHandleIds = array();

		// For debugging purposes - prevent a CSS file from being moved from one location to another
		$wpacuNoCssPositionForHandles = array();
		if (isset($_GET['wpacu_no_css_position_change_for'])) {
			$wpacuNoCssPositionForList = $_GET['wpacu_no_css_position_change_for'];
			if ( strpos( $wpacuNoCssPositionForList, ',' ) !== false ) {
				// With comma? Could be something like /?wpacu_no_css_position_change_for=handle-one,handle-two
				foreach ( explode( ',', $wpacuNoCssPositionForList ) as $wpacuNoCssPositionForHandle ) {
					if ( trim( $wpacuNoCssPositionForHandle ) ) {
						$wpacuNoCssPositionForHandles[] = $wpacuNoCssPositionForHandle;
					}
				}
			} else {
				$wpacuNoCssPositionForHandles[] = $wpacuNoCssPositionForList;
			}
		}

		foreach ($assetsPositions['styles'] as $styleHandle => $styleNewPosition) {
			if (in_array($styleHandle, $wpacuNoCssPositionForHandles)) {
				continue;
			}

			if (isset(Main::instance()->wpAllStyles['registered'][$styleHandle]->src)) {
				ob_start();
				$wp_styles->do_item($styleHandle);
				$htmlTag = trim(ob_get_clean());

				if (isset($wpStylesRegistered[$styleHandle])) {
					$optimizeValues = OptimizeCss::maybeOptimizeIt($wpStylesRegistered[$styleHandle]);
					ObjectCache::wpacu_cache_set('wpacu_maybe_optimize_it_css_'.$styleHandle, $optimizeValues);

					if (! empty($optimizeValues) && isset($optimizeValues[1]) && is_file(rtrim(ABSPATH, '/') . $optimizeValues[1])) {
						// Make sure the source URL gets updated even if it starts with // (some plugins/theme strip the protocol when enqueuing CSS files)
						$siteUrlNoProtocol = str_replace(array('http://', 'https://'), '//', site_url());

						$sourceUrlList = array(
							site_url() . $optimizeValues[0], // with protocol
							$siteUrlNoProtocol . $optimizeValues[0] // without protocol
						); // array

						if ($cdnUrlForCss) {
							// Does it have a CDN?
							$sourceUrlList[] = OptimizeCommon::cdnToUrlFormat($cdnUrlForCss, 'rel') . $optimizeValues[0];
						}

						// Any rel hardcoded (not enqueued) tag? You never know
						// e.g. <link src="/wp-content/themes/my-theme/style.css"></script>
						if (strpos($optimizeValues[2], '/') === 0 && strpos($optimizeValues[2], '//') !== 0) {
							$sourceUrlList[] = $optimizeValues[2];
						}

						// If no CDN is set, it will return site_url() as a prefix
						$optimizeUrl = OptimizeCommon::cdnToUrlFormat($cdnUrlForCss, 'raw') . $optimizeValues[1]; // string

						$htmlTag = OptimizeCss::updateOriginalToOptimizedTag($htmlTag, $sourceUrlList, $optimizeUrl);
					}
				}

				$stylesNewPositionsTags[$styleNewPosition][] = $htmlTag;
				$stylesNewPositionsHandleIds[$styleHandle.'-css'] = Main::instance()->wpAllStyles['registered'][$styleHandle]->src;

				// We will dequeue it to avoid other plugins/scripts to consider it loaded (the initial way)
				// But keep it registered to use its information later on in order to add it to the BODY
				wp_dequeue_style($styleHandle);
			}
		}

		ObjectCache::wpacu_cache_set('wpacu_styles_positions_tags', $stylesNewPositionsTags);
		ObjectCache::wpacu_cache_set('wpacu_styles_positions_handle_ids', $stylesNewPositionsHandleIds);
	}

	/**
	 * @param $linkData
	 * @param $indexData
	 * @param $extractHref
	 *
	 * @return string
	 */
	public static function convertLinkTagToJsDeferCode($linkData, $indexData, $extractHref = true)
	{
		$vI = ($indexData + 1);

		$extraLinkOutputs = $linkIntegrityOutput = $linkCrossOriginOutput = '';

		// $linkData is a LINK tag
		if ($extractHref) {
			preg_match_all('#href=(["\'])' . '(.*)' . '(["\'])#Usmi', $linkData, $outputMatches);
			$linkHref = trim($outputMatches[2][0], '"\'');

			preg_match_all('#media=(["\'])' . '(.*)' . '(["\'])#Usmi', $linkData, $outputMatches);
			$linkMedia = isset($outputMatches[2][0]) ? trim($outputMatches[2][0], '"\'') : 'all';

			// Defer CSS Loaded in the <BODY> (Footer) - clear signature
			$linkMedia = str_replace('wpacu-moved-from-head-to-body-', '', $linkMedia);

			if (stripos($linkData, 'integrity') !== false) {
				preg_match_all('#integrity=(["\'])' . '(.*)' . '(["\'])#Usmi', $linkData, $outputMatches);

				if (isset($outputMatches[2][0])) {
					$linkIntegrity       = trim($outputMatches[2][0], '"\'');
					$linkIntegrityOutput = 'wpacuLinkTag' . $vI . ".integrity = '" . $linkIntegrity . "';\n";
				}
			}

			if (stripos($linkData, 'crossorigin') !== false) {
				preg_match_all('#crossorigin=(["\'])' . '(.*)' . '(["\'])#Usmi', $linkData, $outputMatches);

				if (isset($outputMatches[2][0])) {
					$linkCrossOrigin       = trim($outputMatches[2][0], '"\'');
					$linkCrossOriginOutput = 'wpacuLinkTag' . $vI . ".crossOrigin = '" . $linkCrossOrigin . "';\n";
				}
			}

			$extraLinkOutputs = $linkIntegrityOutput . $linkCrossOriginOutput;
		} else {
			$linkHref = $linkData;
			$linkMedia = 'all';
		}

		$typeAttr = Misc::getScriptTypeAttribute();

		$outputJs = <<<JS
document.addEventListener('DOMContentLoaded', function() {
	var wpacuLinkTag{$vI} = document.createElement('link');
	wpacuLinkTag{$vI}.rel = 'stylesheet';
	wpacuLinkTag{$vI}.href = '{$linkHref}';
	wpacuLinkTag{$vI}.type = 'text/css';
	wpacuLinkTag{$vI}.media = '{$linkMedia}';
	wpacuLinkTag{$vI}.property = 'stylesheet';
	{$extraLinkOutputs}
	
	var wpacuUa = window.navigator.userAgent;
	
	if ((wpacuUa.indexOf('MSIE ') > 0 || wpacuUa.indexOf('Trident/') > 0 || wpacuUa.indexOf('Edge/') > 0) || typeof document.documentMode !== 'undefined') {
	    document.body.appendChild(wpacuLinkTag{$vI}); /* Internet Explorer Support */
	} else {
		document.body.prepend(wpacuLinkTag{$vI}); /* Other browsers */
	}
});
JS;
		$finalOutput  = '<script '.$typeAttr.'>';
		$finalOutput .= Menu::userCanManageAssets() ? "\n".$outputJs."\n" : MinifyJs::applyMinification($outputJs);
		$finalOutput .= '</script>';

		return $finalOutput;
	}

	/**
	 * @param $htmlTagAlt
	 *
	 * @return mixed
	 */
	public static function extractFromGeneratedCssLinkTag($htmlTagAlt)
	{
		if (strpos($htmlTagAlt, '<style id=') !== false) {
			list ($linkTag, $inlineCode) = explode('<style id=', $htmlTagAlt);
			return array('link_tag' => trim($linkTag), 'inline' => "\n".'<style id='.$inlineCode);
		}

		// Most of the enqueued CSS doesn't have inline code associated with it
		return array('link_tag' => $htmlTagAlt, 'inline' => '');
	}

	/**
	 * @param $htmlSource
	 *
	 * @return mixed
	 */
	public function appendStylesMovedToNewPosition($htmlSource)
	{
		$stylesPositionsTags = ObjectCache::wpacu_cache_get('wpacu_styles_positions_tags');

		if (! empty($stylesPositionsTags)) {
			foreach ($stylesPositionsTags as $newPosition => $htmlTags) {
				$toReplace = '';

				if ($newPosition === 'head') {
					$toReplace = self::DEL_STYLES_MOVED_FROM_BODY_TO_HEAD;
					$idPrefix = 'wpacu-moved-from-body-to-head-';
				} elseif ($newPosition === 'body') {
					$toReplace = self::DEL_STYLES_MOVED_FROM_HEAD_TO_BODY;
					$idPrefix = 'wpacu-moved-from-head-to-body-';
				}

				if ($toReplace) {
					if ($newPosition === 'head') {
						foreach ($htmlTags as $htmlTag) {
							$cssGeneratedCode = self::extractFromGeneratedCssLinkTag($htmlTag);

							$linkTag = $cssGeneratedCode['link_tag'];

							// If "Optimize CSS Delivery" is enabled in WP Rocket (if active), then a critical CSS will be generated
							// and all CSS will be preloaded via async; make sure this moved one is preloaded too to avoid render-blocking resources
							if (OptimizeCss::isWpRocketOptimizeCssDeliveryEnabled() && ! Menu::userCanManageAssets()) {
								$linkTag = apply_filters('wpacu_preload_css_async_tag', $linkTag);
							}

							// Move existing one to the new location
							$htmlTagAlt = str_replace('<link', '<assetcleanuplink', $linkTag);
							$htmlTagAlt = str_replace(array('id="', "id='"), array('id="' . $idPrefix, "id='" . $idPrefix), $htmlTagAlt);
							$htmlSource = str_replace($toReplace, $htmlTagAlt . $cssGeneratedCode['inline'] . $toReplace, $htmlSource);

							$htmlSource = str_replace($htmlTag, '', $htmlSource);
						}
					} else {
						foreach ($htmlTags as $htmlTag) {
							$cssGeneratedCode = self::extractFromGeneratedCssLinkTag($htmlTag);

							$linkTag = $cssGeneratedCode['link_tag'];

							// If "Optimize CSS Delivery" is enabled in WP Rocket (if active), then a critical CSS will be generated
							// and all CSS will be preloaded via async; make sure this moved one is preloaded too to avoid render-blocking resources
							if (OptimizeCss::isWpRocketOptimizeCssDeliveryEnabled() && ! Menu::userCanManageAssets()) {
								$linkTag = apply_filters('wpacu_preload_css_async_tag', $linkTag);
							}

							// Move existing one to the new location
							$htmlTagAlt = str_replace('<link', '<assetcleanuplink', $linkTag);
							$htmlTagAlt = str_replace(array('id="', "id='"), array('id="' . $idPrefix, "id='" . $idPrefix), $htmlTagAlt);

							$cssBodyTagReplaceWith = $htmlTagAlt . $cssGeneratedCode['inline'] . $toReplace."\n";

							$htmlSource = str_replace($toReplace, $cssBodyTagReplaceWith, $htmlSource);

							// Remove it from its initial location to avoid any duplicate block of code
							$htmlSource = str_replace($htmlTag, '', $htmlSource);
						}
					}
				}
			}
		}

		// Remove HTML signatures (for cleaning up the resulting HTML source code) if there are still any left
		return str_replace(array(self::DEL_STYLES_MOVED_FROM_BODY_TO_HEAD, self::DEL_STYLES_MOVED_FROM_HEAD_TO_BODY), '', $htmlSource);
	}

	/**
	 * @param $newPositionsList
	 *
	 * @return mixed
	 */
	public function filterNewPositionsList($newPositionsList)
	{
		// If "Optimize CSS delivery" from "File Optimization" in WP Rocket is active
		// then prevent Asset CleanUp (Pro) from moving CSS files from their initial location
		// to avoid duplicated CSS files loading
		if (Misc::isPluginActive('wp-rocket/wp-rocket.php') && function_exists('get_rocket_option') && get_rocket_option('async_css')) {
			unset($newPositionsList['styles']);
		}

		return $newPositionsList;
	}

	/**
	 * Move SCRIPT tags to their new location
	 * Fortunately, WordPress has its own way of doing it
	 *
	 * @return void
	 */
	public function markScriptsToLoadInNewPosition()
	{
		if (array_key_exists('wpacu_no_js_position_change', $_GET)) {
			return;
		}

		$assetsPositions = $this->getAssetsPositions();

		if (isset($assetsPositions['scripts']) && ! empty($assetsPositions['scripts'])) {
			foreach ($assetsPositions['scripts'] as $scriptHandle => $scriptNewPosition) {
				$scriptNewPositionInt = ($scriptNewPosition === 'body') ? 1 : 0;
				wp_scripts()->add_data($scriptHandle, 'group', $scriptNewPositionInt);

				// jQuery Special Case Actions
				// 1) Make sure the library is moved to the BODY if requested (if WordPress version >= 5.2, move jQuery right after the opening BODY tag for earlier triggering)
				// 2) Make sure 'admin-bar' loads after jQuery library because it contains jQuery code
				if ($scriptHandle === 'jquery-core') {
					// Attempt to move it right after <body> if it was moved to the footer
					if ($scriptNewPositionInt === 1) {
						add_action('wp_body_open', static function() {
							global $wp_scripts;

							ob_start();
							$wp_scripts->do_item('jquery-migrate');
							$htmljQueryMigrateScriptTag = trim(ob_get_clean());

							ob_start();
							$wp_scripts->do_item('jquery-core');
							echo $htmljQueryMigrateScriptTag; // includes jQuery Migrate, it will be striped if it is not meant to be loaded (e.g. chosen for unload)
							$htmljQueryScriptTag = trim(ob_get_clean());

							ObjectCache::wpacu_cache_add('wpacu_jquery_script_tag', $htmljQueryScriptTag);
							ObjectCache::wpacu_cache_add('wpacu_jquery_migrate_script_tag', $htmljQueryMigrateScriptTag);

							echo self::DEL_MAYBE_PLACE_JQUERY_RIGHT_AFTER_BODY;
						}, 1);
					}

					// Set its position (HEAD or BODY)
					wp_scripts()->add_data('jquery', 'group', $scriptNewPositionInt);

					// If jQuery is moved, then jQuery Migrate will be moved as well as it needs to load right after jQuery
					wp_scripts()->add_data('jquery-migrate', 'group', $scriptNewPositionInt);

					// Load it after jQuery
					global $wp_scripts;
					$script = $wp_scripts->query('admin-bar');

					if ($script !== false) {
						$script->deps[] = 'jquery';
					}
				}
			}
		}
	}

	/**
	 * @param $htmlSource
	 *
	 * @return mixed
	 */
	public function maybeMovejQueryAfterBodyTag($htmlSource)
	{
		if ((strpos($htmlSource, self::DEL_MAYBE_PLACE_JQUERY_RIGHT_AFTER_BODY) !== false)
		    && ($jQueryScriptTag = ObjectCache::wpacu_cache_get('wpacu_jquery_script_tag'))) {

			// is jQuery Unloaded? Stop here as jQuery Migrate shouldn't be loaded either
			if (defined('WPACU_JQUERY_UNLOADED')) {
				return str_replace(self::DEL_MAYBE_PLACE_JQUERY_RIGHT_AFTER_BODY, '', $htmlSource);
			}

			$jqueryMigrateNotLoaded = false;

			if (defined('WPACU_JQUERY_MIGRATE_UNLOADED')) {
				$jqueryMigrateNotLoaded = true;
			}

			if (isset(Main::instance()->wpAllScripts['registered']['jquery']->deps)) {
				$jQueryDeps = isset(Main::instance()->wpAllScripts['registered']['jquery']->deps) ? Main::instance()->wpAllScripts['registered']['jquery']->deps : array();

				if (! in_array('jquery-migrate', $jQueryDeps)) {
					$jqueryMigrateNotLoaded = true;
				}
			}

			if ($jqueryMigrateNotLoaded) {
				$jQueryMigrateScriptTag = ObjectCache::wpacu_cache_get('wpacu_jquery_migrate_script_tag');
				$jQueryScriptTag = str_replace($jQueryMigrateScriptTag, '', $jQueryScriptTag);
			}

			$htmlSource = str_replace(
				array($jQueryScriptTag, self::DEL_MAYBE_PLACE_JQUERY_RIGHT_AFTER_BODY),
				array('', trim($jQueryScriptTag)."\n"),
				$htmlSource
			);
		} else {
			$htmlSource = str_replace(self::DEL_MAYBE_PLACE_JQUERY_RIGHT_AFTER_BODY, '', $htmlSource);
		}

		return $htmlSource;
	}

	/**
	 * @param $htmlSource
	 *
	 * @return mixed
	 */
	public function deferFooterStyles($htmlSource)
	{
		$deferCssLoadedBodyOption = Main::instance()->settings['defer_css_loaded_body'];

		if ($deferCssLoadedBodyOption === 'no' || Misc::isWpRocketMinifyHtmlEnabled()) {
			// "No, leave the stylesheet LINK tags from the BODY as they are without any alteration" was chosen
			// OR, "Minify HTML" is enabled in WP Rocket
			return $htmlSource;
		}

		if ($deferCssLoadedBodyOption === 'moved' && strpos($htmlSource, 'wpacu-moved-from-head-to-body') === false) {
			// Defer the Moved ones only
			// However, there has to at least one moved, otherwise stop here and save resources
			return $htmlSource;
		}

		// Have a smaller HTML source to fetch from as it would increase the speed of the DOMDocument parser
		preg_match_all( '#<link[^>]*rel=([\'"])stylesheet([\'"])[^>]*wpacu-moved-from-head-to-body.*>#Usmi', $htmlSource, $matchedTags );

		if ( ! (isset($matchedTags[0]) && ! empty($matchedTags[0])) ) {
			return $htmlSource;
		}

		$indexData = 1000;

		foreach ($matchedTags[0] as $htmlTag) {
			if (preg_match('#data-wpacu-skip([=>/ ])#i', $htmlTag)
			    || strpos($htmlTag, 'data-wpacu-apply-media-query=') !== false) {
				continue; // no point in moving it as it was marked for no changes and the media query load was applied
			}

			// Only LINK tags with rel='stylesheet' are deferred
			$cssGeneratedCode = self::extractFromGeneratedCssLinkTag($htmlTag);

			$htmlSource = str_replace(
				$htmlTag,
				self::convertLinkTagToJsDeferCode($htmlTag, $indexData) .
				'<noscript>' . trim($htmlTag) . '</noscript>' . $cssGeneratedCode['inline'] . "\n",
				$htmlSource
			);

			$indexData++;
		}

		return $htmlSource;
	}

	/**
	 * @return bool
	 */
	public static function preventMoving()
	{
		if (defined('WPACU_ALLOW_ONLY_UNLOAD_RULES') && WPACU_ALLOW_ONLY_UNLOAD_RULES) {
			return true;
		}

		return false;
	}

	}
