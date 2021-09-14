<?php
namespace WpAssetCleanUp;

use WpAssetCleanUp\OptimiseAssets\OptimizeCommon;
use WpAssetCleanUp\OptimiseAssets\OptimizeJs;

/**
 * Class HardcodedAssets
 * @package WpAssetCleanUp
 */
class HardcodedAssets
{
	/**
	 *
	 */
	public static function init()
	{
		add_action( 'init', static function() {
			if (Main::instance()->isGetAssetsCall) {
				// Case 1: An AJAX call is made from the Dashboard
				self::initBufferingForAjaxCallFromTheDashboard();
			} elseif (self::useBufferingForEditFrontEndView()) {
				// Case 2: The logged-in admin manages the assets from the front-end view
				self::initBufferingForFrontendManagement();
			// [wpacu_pro]
			} else {
				// Case 3 (most common): Viewed by the guest ONLY if there is at least one hardcoded asset marked for unload
				self::initLateAlterationForGuestView();
			}
			// [/wpacu_pro]
		});
	}

	/**
	 *
	 */
	public static function initBufferingForAjaxCallFromTheDashboard()
	{
		ob_start();

		add_action('shutdown', static function() {
			$htmlSource = '';

			// We'll need to get the number of ob levels we're in, so that we can iterate over each, collecting
			// that buffer's output into the final output.
			$htmlSourceLevel = ob_get_level();

			for ($wpacuI = 0; $wpacuI < $htmlSourceLevel; $wpacuI++) {
				$htmlSource .= ob_get_clean();
			}

			$anyHardCodedAssets = HardcodedAssets::getAll($htmlSource); // Fetch all for this type of request

			$htmlSource = str_replace('{wpacu_hardcoded_assets}', $anyHardCodedAssets, $htmlSource);

			echo $htmlSource;
		}, 0);
	}

	// [wpacu_pro]
	/**
	 * Late call in case hardcoded CSS/JS loaded later needs to be stripped (e.g. from plugins such as "Smart Slider 3" that loads non-enqueued files)
	 */
	public static function initLateAlterationForGuestView()
	{
		// Not for logged-in admin managing the assets
		if (self::useBufferingForEditFrontEndView()) {
			return;
		}

		// Do not do any changes if "Test Mode" is Enabled and the user is a guest
		if (! Menu::userCanManageAssets() && Main::instance()->settings['test_mode']) {
			return;
		}

		// No hardcoded assets marked for unload? No point in continuing, save resources!
		$anyHardCodedAssetsMarkedForUnload = self::getHardcodedUnloadList();

		if (empty($anyHardCodedAssetsMarkedForUnload)) {
			return;
		}

		ob_start();

		add_action('shutdown', static function() {
			$htmlSource = '';

			// We'll need to get the number of ob levels we're in, so that we can iterate over each, collecting
			// that buffer's output into the final output.
			$htmlSourceLevel = ob_get_level();

			for ($wpacuI = 0; $wpacuI < $htmlSourceLevel; $wpacuI++) {
				$htmlSource .= ob_get_clean();
			}

			$anyHardCodedAssetsList = HardcodedAssets::getAll( $htmlSource, false, true );

			if (! empty($anyHardCodedAssetsList)) {
				$htmlSource = HardcodedAssets::maybeStripHardcodedAssets( $htmlSource, $anyHardCodedAssetsList );
			}

			echo $htmlSource;
		}, 0);
	}
	// [/wpacu_pro]

	/**
	 *
	 */
	public static function initBufferingForFrontendManagement()
	{
		// Used to print the hardcoded CSS/JS
		ob_start();

		add_action('shutdown', static function() {
			if (! defined('NEXTEND_SMARTSLIDER_3_URL_PATH')) {
				ob_flush();
			}

			$htmlSource = '';

			// We'll need to get the number of ob levels we're in, so that we can iterate over each, collecting
			// that buffer's output into the final output.
			$htmlSourceLevel = ob_get_level();

			for ($wpacuI = 0; $wpacuI < $htmlSourceLevel; $wpacuI++) {
				$htmlSource .= ob_get_clean();
			}

			echo OptimizeCommon::alterHtmlSource($htmlSource);

			}, 0);
	}

	/**
	 * @return bool
	 */
	public static function useBufferingForEditFrontEndView()
	{
		// The logged-in admin needs to be outside the Dashboard (in the front-end view)
		// "Manage in the Front-end" is enabled in "Settings" -> "Plugin Usage Preferences"
		return (Main::instance()->frontendShow() && ! is_admin() && Menu::userCanManageAssets() && ! Main::instance()->isGetAssetsCall);
	}

	/**
	 * @param $htmlSource
	 * @param bool $encodeIt - if set to "false", it's mostly for testing purposes
	 * @param bool $isGuestVisit
	 *
	 * @return string|array
	 */
	public static function getAll($htmlSource, $encodeIt = true, $isGuestVisit = false)
	{
		$htmlSourceAlt = CleanUp::removeHtmlComments($htmlSource, true);

		$collectLinkStyles = true; // default
		$collectScripts    = true; // default

		// [wpacu_pro]
		// Performance improvement
		// If the visit comes from a guest visitor (not logged-in as admin) and no request to fetch the assets is made
		// Then do not fetch all the hardcoded assets, but only the ones for which at least one marked for unload (e.g. for inline SCRIPTs) exists
		if ($isGuestVisit) {
			$hardCodedAssetsMarkedForUnload = self::getHardcodedUnloadList();

			/*
			 * Possible keys:
			 * wpacu_hardcoded_links
			 * wpacu_hardcoded_styles
			 * wpacu_hardcoded_scripts_src
			 * wpacu_hardcoded_scripts_inline
			 */
			if (! isset($hardCodedAssetsMarkedForUnload['wpacu_hardcoded_links']) && ! isset($hardCodedAssetsMarkedForUnload['wpacu_hardcoded_styles'])) {
				$collectLinkStyles = false; // No LINKs or STYLEs marked for unload
			}

			if (! isset($hardCodedAssetsMarkedForUnload['wpacu_hardcoded_scripts_src']) && ! isset($hardCodedAssetsMarkedForUnload['wpacu_hardcoded_scripts_inline'])) {
				$collectScripts = false; // No SCRIPTs of any kind marked for unload
			}

			// Are both set to false? No point in moving forward! Stop here and save resources
			if (! $collectLinkStyles && ! $collectScripts) {
				return array();
			}
		}
		// [/wpacu_pro]

		$hardCodedAssets = array(
			'link_and_style_tags'        => array(), // LINK (rel="stylesheet") & STYLE (inline)
			'script_src_and_inline_tags' => array(), // SCRIPT (with "src" attribute) & SCRIPT (inline)
		);

		if ($collectLinkStyles) {
			/*
			* [START] Collect Hardcoded LINK (stylesheet) & STYLE tags
			*/
			preg_match_all( '#(?=(?P<link_tag><link[^>]*stylesheet[^>]*(>)))|(?=(?P<style_tag><style[^>]*?>.*</style>))#Umsi',
				$htmlSourceAlt, $matchesSourcesFromTags, PREG_SET_ORDER );

			if ( ! empty( $matchesSourcesFromTags ) ) {
				// Only the hashes are set
				// For instance, 'd1eae32c4e99d24573042dfbb71f5258a86e2a8e' is the hash for the following script:
				/*
				* <style media="print">#wpadminbar { display:none; }</style>
				 */
				$stripsSpecificStylesHashes = array(
					'5ead5f033961f3b8db362d2ede500051f659dd6d',
					'25bd090513716c34b48b0495c834d2070088ad24'
				);

				// Sometimes, the hash checking might failed (if there's a small change to the JS content)
				// Consider using a fallback verification by checking the actual content
				$stripsSpecificStylesContaining = array(
					'<style media="print">#wpadminbar { display:none; }</style>'
				);

				foreach ( $matchesSourcesFromTags as $matchedTagIndex => $matchedTag ) {
					// LINK "stylesheet" tags (if any)
					if ( isset( $matchedTag['link_tag'] ) && trim( $matchedTag['link_tag'] ) !== '' && ( trim( strip_tags( $matchedTag['link_tag'] ) ) === '' ) ) {
						$matchedTagOutput = trim( $matchedTag['link_tag'] );

						if ( strpos( $matchedTagOutput, 'data-wpacu-style-handle=' ) !== false ) {
							// Skip the LINK with src that was enqueued properly and keep the hardcoded ones
							continue;
						}

						$hardCodedAssets['link_and_style_tags'][] = $matchedTagOutput;
					}

					// STYLE inline tags (if any)
					if ( isset( $matchedTag['style_tag'] ) && trim( $matchedTag['style_tag'] ) !== '' ) {
						$matchedTagOutput = trim( $matchedTag['style_tag'] );

						/*
						 * Strip certain STYLE tags irrelevant for the list (e.g. related to the WordPress Admin Bar, etc.)
						*/
						if ( in_array( sha1( $matchedTagOutput ), $stripsSpecificStylesHashes ) ) {
							continue;
						}

						foreach ( $stripsSpecificStylesContaining as $cssContentTargeted ) {
							if ( strpos( $matchedTagOutput, $cssContentTargeted ) !== false ) {
								continue;
							}
						}

						// Do not add to the list elements such as Emojis (not relevant for hard-coded tags)
						if ( strpos( $matchedTagOutput, 'img.wp-smiley' )  !== false
						     && strpos( $matchedTagOutput, 'img.emoji' )   !== false
						     && strpos( $matchedTagOutput, '!important;' ) !== false ) {
							continue;
						}

						if ( (strpos( $matchedTagOutput, 'data-wpacu-own-inline-style=' ) !== false) ||
						     (strpos( $matchedTagOutput, 'data-wpacu-inline-css-file=')   !== false) ) {
							// remove plugin's own STYLE tags as they are not relevant in this context
							continue;
						}

						foreach ( wp_styles()->done as $cssHandle ) {
							if ( strpos( $matchedTagOutput,
									'<style id=\'' . trim( $cssHandle ) . '-inline-css\'' ) !== false ) {
								// Do not consider the STYLE added via WordPress with wp_add_inline_style() as it's not hardcoded
								continue 2;
							}
						}

						$hardCodedAssets['link_and_style_tags'][] = $matchedTagOutput;
					}
				}
			}
			/*
			* [END] Collect Hardcoded LINK (stylesheet) & STYLE tags
			*/
		}

		if ($collectScripts) {
			/*
			* [START] Collect Hardcoded SCRIPT (src/inline)
			*/
			preg_match_all( '@<script[^>]*?>.*?</script>@si', $htmlSourceAlt, $matchesScriptTags, PREG_SET_ORDER );

			if ( isset( wp_scripts()->done ) && ! empty( wp_scripts()->done ) ) {
				$allInlineAssocWithJsHandle = array();

				foreach ( wp_scripts()->done as $assetHandle ) {
					// Now, go through the list of inline SCRIPTs associated with an enqueued SCRIPT (with "src" attribute)
					// And make sure they do not show to the hardcoded list, since they are related to the handle and they are stripped when the handle is dequeued
					$anyInlineAssocWithJsHandle = OptimizeJs::getInlineAssociatedWithScriptHandle( $assetHandle, wp_scripts()->registered, 'handle' );
					if ( ! empty( $anyInlineAssocWithJsHandle ) ) {
						foreach ( $anyInlineAssocWithJsHandle as $jsInlineTagContent ) {
							if ( trim( $jsInlineTagContent ) === '' ) {
								continue;
							}

							$allInlineAssocWithJsHandle[] = trim($jsInlineTagContent);
						}
					}
				}

				$allInlineAssocWithJsHandle = array_unique($allInlineAssocWithJsHandle);
				}

			// Go through the hardcoded SCRIPT tags
			if ( isset( $matchesScriptTags ) && ! empty( $matchesScriptTags ) ) {
				// Only the hashes are set
				// For instance, 'd1eae32c4e99d24573042dfbb71f5258a86e2a8e' is the hash for the following script:
				/*
				 * <script>
				(function() {
					var request, b = document.body, c = 'className', cs = 'customize-support', rcs = new RegExp('(^|\\s+)(no-)?'+cs+'(\\s+|$)');
						request = true;
					b[c] = b[c].replace( rcs, ' ' );
					// The customizer requires postMessage and CORS (if the site is cross domain)
					b[c] += ( window.postMessage && request ? ' ' : ' no-' ) + cs;
				}());
				</script>
				 */
				$stripsSpecificScriptsHashes = array(
					'd1eae32c4e99d24573042dfbb71f5258a86e2a8e',
					'1a8f46f9f33e5d95919620df54781acbfa9efff7'
				);

				// Sometimes, the hash checking might failed (if there's a small change to the JS content)
				// Consider using a fallback verification by checking the actual content
				$stripsSpecificScriptsContaining = array(
					'// The customizer requires postMessage and CORS (if the site is cross domain)',
					'b[c] += ( window.postMessage && request ? \' \' : \' no-\' ) + cs;',
					"(function(){var request,b=document.body,c='className',cs='customize-support',rcs=new RegExp('(^|\\s+)(no-)?'+cs+'(\\s+|$)');request=!0;b[c]=b[c].replace(rcs,' ');b[c]+=(window.postMessage&&request?' ':' no-')+cs}())",
					'document.body.className = document.body.className.replace( /(^|\s)(no-)?customize-support(?=\s|$)/, \'\' ) + \' no-customize-support\''
				);

				foreach ( $matchesScriptTags as $matchedTag ) {
					if ( isset( $matchedTag[0] ) && $matchedTag[0] && strpos( $matchedTag[0], '<script' ) === 0 ) {
						$matchedTagOutput = trim( $matchedTag[0] );

						/*
						 * Strip certain SCRIPT tags irrelevant for the list (e.g. related to WordPress Customiser, Admin Bar, etc.)
						*/
						if ( in_array( sha1( $matchedTagOutput ), $stripsSpecificScriptsHashes ) ) {
							continue;
						}

						foreach ( $stripsSpecificScriptsContaining as $jsContentTargeted ) {
							if ( strpos( $matchedTagOutput, $jsContentTargeted ) !== false ) {
								continue;
							}
						}

						if ( strpos( $matchedTagOutput, 'window._wpemojiSettings' ) !== false
						     && strpos( $matchedTagOutput, 'twemoji' ) !== false ) {
							continue;
						}

						// Check the type and only allow SCRIPT tags with type='text/javascript' or no type at all (it default to 'text/javascript')
						$matchedTagInner    = strip_tags( $matchedTagOutput );
						$matchedTagOnlyTags = str_replace( $matchedTagInner, '', $matchedTagOutput );
						preg_match_all( '#type=(["\'])' . '(.*)' . '(["\'])#Usmi', $matchedTagOnlyTags,
							$outputMatches );
						$scriptType = isset( $outputMatches[2][0] ) ? trim( $outputMatches[2][0],
							'"\'' ) : 'text/javascript';

						if ( strpos( $scriptType, 'text/javascript' ) === false ) {
							continue;
						}

						if ( strpos( $matchedTagOutput, 'data-wpacu-script-handle=' ) !== false ) {
							// skip the SCRIPT with src that was enqueued properly and keep the hardcoded ones
							continue;
						}

						if ( (strpos( $matchedTagOutput, 'data-wpacu-own-inline-script=' ) !== false) ||
						     (strpos( $matchedTagOutput, 'data-wpacu-inline-js-file=' )    !== false) ) {
							// skip plugin's own SCRIPT tags as they are not relevant in this context
							continue;
						}

						if ( strpos( $matchedTagOutput, 'wpacu-preload-async-css-fallback' ) !== false ) {
							// skip plugin's own SCRIPT tags as they are not relevant in this context
							continue;
						}

						$hasSrc = false;

						if (strpos($matchedTagOnlyTags, ' src=') !== false) {
							$hasSrc = true;
						}

						if ( ! $hasSrc && ! empty( $allInlineAssocWithJsHandle ) ) {
							preg_match_all("'<script[^>]*?>(.*?)</script>'si", $matchedTagOutput, $matchesFromTagOutput);
							$matchedTagOutputInner = isset($matchesFromTagOutput[1][0]) && trim($matchesFromTagOutput[1][0])
								? trim($matchesFromTagOutput[1][0]) : false;

							$matchedTagOutputInnerCleaner = $matchedTagOutputInner;

							$stripStrStart = '/* <![CDATA[ */';
							$stripStrEnd   = '/* ]]> */';

							if (strpos($matchedTagOutputInnerCleaner, $stripStrStart) === 0
							    && Misc::endsWith($matchedTagOutputInnerCleaner, '/* ]]> */')) {
								$matchedTagOutputInnerCleaner = substr($matchedTagOutputInnerCleaner, strlen($stripStrStart));
								$matchedTagOutputInnerCleaner = substr($matchedTagOutputInnerCleaner, 0, -strlen($stripStrEnd));
								$matchedTagOutputInnerCleaner = trim($matchedTagOutputInnerCleaner);
							}

							if (in_array($matchedTagOutputInnerCleaner, $allInlineAssocWithJsHandle)) {
								continue;
							}

							}

						$hardCodedAssets['script_src_and_inline_tags'][] = trim( $matchedTag[0] );
					}
				}
			}
			/*
			* [END] Collect Hardcoded SCRIPT (src/inline)
			*/
		}

		$tagsWithinConditionalComments = self::extractHtmlFromConditionalComments( $htmlSourceAlt );

		if (Main::instance()->isGetAssetsCall) {
			// AJAX call within the Dashboard
			$hardCodedAssets['within_conditional_comments'] = $tagsWithinConditionalComments;
		}

		if ($encodeIt) {
			return base64_encode( json_encode( $hardCodedAssets ) );
		}

		return $hardCodedAssets;
	}

	// [wpacu_pro]
	/**
	 * @param $htmlSource
	 * @param array $anyHardCodedAssets
	 * @param bool|array $hardcodedMarkedForUnloadList
	 *
	 * @return string|string[]
	 */
	public static function maybeStripHardcodedAssets($htmlSource, $anyHardCodedAssets = array(), $hardcodedMarkedForUnloadList = false)
	{
		// No hardcoded assets were found on this page, thus any hardcoded assets chosen to be unloaded are irrelevant to be checked
		if (empty($anyHardCodedAssets)) {
			return $htmlSource;
		}

		$handlesInfo = Main::getHandlesInfo();
		if ($hardcodedMarkedForUnloadList === false) {
			$hardcodedMarkedForUnloadList = self::getHardcodedUnloadList();
		}

		// Go through the unloaded CSS/JS and strip them from the HTML code
		if (! empty($hardcodedMarkedForUnloadList)) {
			foreach ($hardcodedMarkedForUnloadList as $hardCodedType => $hardcodedHandles) {
				foreach ($hardcodedHandles as $hardcodedHandle) {
					// This has to be turned off; sometimes it's used for loading the scripts marked for unload for debugging purposes
					$preventHardCodedCssUnloading = array_key_exists( 'wpacu_no_hd_css_unload', $_GET );

					// STYLEs and LINKs ("stylesheet")
					if ( (! $preventHardCodedCssUnloading) && in_array($hardCodedType, array('wpacu_hardcoded_links', 'wpacu_hardcoded_styles'))
					    && isset($handlesInfo['styles'][$hardcodedHandle]['output']) && $handlesInfo['styles'][$hardcodedHandle]['output'] )
					{
						$htmlSourceBefore = $htmlSource;
						$htmlSource = str_replace( $handlesInfo['styles'][ $hardcodedHandle ]['output'], '', $htmlSource );

						if ($htmlSource === $htmlSourceBefore) { // No change? Perhaps it was altered (e.g. minified or had white space stripped)
							$htmlSource = str_replace( self::alternativeValuesIfMinified($handlesInfo['styles'][ $hardcodedHandle ]['output'] ), '', $htmlSource );

							if ( $htmlSource === $htmlSourceBefore && isset( $handlesInfo['styles'][ $hardcodedHandle ]['output_min'] ) && $handlesInfo['styles'][ $hardcodedHandle ]['output_min'] ) {
								$htmlSource = str_replace( $handlesInfo['styles'][ $hardcodedHandle ]['output_min'], '', $htmlSource );
							}
						}
					}

					// This has to be turned off; sometimes it's used for loading the scripts marked for unload for debugging purposes
					$preventHardCodedJsUnloading = array_key_exists( 'wpacu_no_hd_js_unload', $_GET );

					// SCRIPTs ("src" and inline)
					if ( (! $preventHardCodedJsUnloading) && in_array($hardCodedType, array('wpacu_hardcoded_scripts_src', 'wpacu_hardcoded_scripts_inline'))
					    && isset($handlesInfo['scripts'][$hardcodedHandle]['output']) && $handlesInfo['scripts'][$hardcodedHandle]['output'] )
					{
						$htmlSourceBefore = $htmlSource;
						$htmlSource = str_replace( $handlesInfo['scripts'][ $hardcodedHandle ]['output'], '', $htmlSource );

						if ($htmlSource === $htmlSourceBefore) { // No change? Perhaps it was altered (e.g. minified or had white space stripped)
							$htmlSource = str_replace( self::alternativeValuesIfMinified( $handlesInfo['scripts'][ $hardcodedHandle ]['output'] ), '', $htmlSource );

							if ( $htmlSource === $htmlSourceBefore && isset( $handlesInfo['scripts'][ $hardcodedHandle ]['output_min'] ) && $handlesInfo['scripts'][ $hardcodedHandle ]['output_min'] ) {
								$htmlSource = str_replace( $handlesInfo['scripts'][ $hardcodedHandle ]['output_min'], '', $htmlSource );
							}
						}
					}
				}
			}
		}

		return $htmlSource;
	}
	// [/wpacu_pro]

	// [wpacu_pro]
	/**
	 * To be printed when the CSS/JS list is managed
	 *
	 * @param $dataRowObj
	 * @param $data
	 *
	 * @return array
	 */
	public static function wpacuGenerateHardcodedStyleData( $dataRowObj, $data )
	{
		$assetType = 'styles';

		$dataHH = $data;

		$dataHH['row']        = array();
		$dataHH['row']['obj'] = $dataRowObj;

		$active = ( isset( $dataHH['current'][$assetType] ) && in_array( $dataHH['row']['obj']->handle, $dataHH['current'][$assetType] ) );

		$dataHH['row']['class']   = $active ? 'wpacu_not_load' : '';
		$dataHH['row']['checked'] = $active ? 'checked="checked"' : '';

		/*
		 * $data['row']['is_group_unloaded'] is only used to apply a red background in the style's area to point out that the style is unloaded
		 *               is set to `true` if either the asset is unloaded everywhere or it's unloaded on a group of pages (such as all pages belonging to 'page' post type)
		*/
		$dataHH['row']['global_unloaded'] = $dataHH['row']['is_post_type_unloaded'] = $dataHH['row']['is_load_exception_per_page'] = $dataHH['row']['is_group_unloaded'] = $dataHH['row']['is_regex_unload_match'] = false;

		// Mark it as unloaded - Everywhere
		if ( isset($dataHH['global_unload'][$assetType]) &&
		     is_array($dataHH['global_unload'][$assetType]) &&
		     in_array($dataHH['row']['obj']->handle, $dataHH['global_unload'][$assetType]) ) {
			$dataHH['row']['global_unloaded'] = $dataHH['row']['is_group_unloaded'] = true;
		}

		// Mark it as unloaded - for the Current Post Type
		if ( isset($dataHH['bulk_unloaded_type'], $dataHH['bulk_unloaded'][$dataHH['bulk_unloaded_type']][$assetType]) &&
		     $dataHH['bulk_unloaded_type'] &&
		     is_array($dataHH['bulk_unloaded'][$dataHH['bulk_unloaded_type']][$assetType]) &&
		     in_array( $dataHH['row']['obj']->handle, $dataHH['bulk_unloaded'][$dataHH['bulk_unloaded_type']][$assetType] ) ) {
			$dataHH['row']['is_group_unloaded'] = true;

			if ( $dataHH['bulk_unloaded_type'] === 'post_type' ) {
				$dataHH['row']['is_post_type_unloaded'] = true;
			}
		}

		$isLoadExceptionPerPage = isset($dataHH['load_exceptions'][$assetType]) &&
		                          is_array($dataHH['load_exceptions'][$assetType]) &&
		                          in_array($dataHH['row']['obj']->handle, $dataHH['load_exceptions'][$assetType]);

		// [wpacu_pro]
		$isUnloadRegExMatch        = isset( $dataHH['unloads_regex_matches'][$assetType] ) && in_array( $dataHH['row']['obj']->handle,
				$dataHH['unloads_regex_matches'][$assetType] );
		$isLoadExceptionRegExMatch = isset( $dataHH['load_exceptions_regex_matches'][$assetType] ) && in_array( $dataHH['row']['obj']->handle,
				$dataHH['load_exceptions_regex_matches'][$assetType] );
		// [/wpacu_pro]

		$dataHH['row']['is_load_exception_per_page']    = $isLoadExceptionPerPage;
		$dataHH['row']['is_load_exception_regex_match'] = $isLoadExceptionRegExMatch;

		$isLoadException = $isLoadExceptionPerPage || $isLoadExceptionRegExMatch;

		// No load exception of any kind and a bulk unload rule is applied? Append the CSS class for unloading
		if ( ! $isLoadException && ( $dataHH['row']['is_group_unloaded'] || $isUnloadRegExMatch ) ) {
			if ( $isUnloadRegExMatch ) {
				$dataHH['row']['is_regex_unload_match'] = true;
			}
			$dataHH['row']['class'] .= ' wpacu_not_load';
		}

		$dataHH['row']['class'] .= ' style_' . $dataHH['row']['obj']->handle;

		return $dataHH;
	}
	// [/wpacu_pro]

	// [wpacu_pro]
	/**
	 * @param $dataRowObj
	 * @param $data
	 *
	 * @return array
	 */
	public static function wpacuGenerateHardcodedScriptData( $dataRowObj, $data )
	{
		$assetType = 'scripts';

		$dataHH = $data;

		$dataHH['row']        = array();
		$dataHH['row']['obj'] = $dataRowObj;

		$active = ( isset( $dataHH['current'][$assetType] ) && in_array( $dataHH['row']['obj']->handle,
				$dataHH['current'][$assetType] ) );

		$dataHH['row']['class']   = $active ? 'wpacu_not_load' : '';
		$dataHH['row']['checked'] = $active ? 'checked="checked"' : '';

		/*
		 * $data['row']['is_group_unloaded'] is only used to apply a red background in the script's area to point out that the script is unloaded
		 *               is set to `true` if either the asset is unloaded everywhere or it's unloaded on a group of pages (such as all pages belonging to 'page' post type)
		*/
		$dataHH['row']['global_unloaded'] = $dataHH['row']['is_post_type_unloaded'] = $dataHH['row']['is_load_exception_per_page'] = $dataHH['row']['is_group_unloaded'] = $dataHH['row']['is_regex_unload_match'] = false;

		// Mark it as unloaded - Everywhere
		if ( isset($dataHH['global_unload'][$assetType]) &&
		     is_array($dataHH['global_unload'][$assetType]) &&
		     in_array( $dataHH['row']['obj']->handle, $dataHH['global_unload'][$assetType] ) ) {
			$dataHH['row']['global_unloaded'] = $dataHH['row']['is_group_unloaded'] = true;
		}

		// Mark it as unloaded - for the Current Post Type
		if ( isset($dataHH['bulk_unloaded_type']) &&
		     isset($dataHH['bulk_unloaded'][$dataHH['bulk_unloaded_type']][$assetType]) &&
		     is_array($dataHH['bulk_unloaded'][$dataHH['bulk_unloaded_type']][$assetType]) &&
		     in_array($dataHH['row']['obj']->handle, $dataHH['bulk_unloaded'][$dataHH['bulk_unloaded_type']][$assetType]) ) {
			$dataHH['row']['is_group_unloaded'] = true;

			if ( $dataHH['bulk_unloaded_type'] === 'post_type' ) {
				$dataHH['row']['is_post_type_unloaded'] = true;
			}
		}

		$isLoadExceptionPerPage = isset( $dataHH['load_exceptions'][$assetType] ) && in_array( $dataHH['row']['obj']->handle, $dataHH['load_exceptions'][$assetType] );

		// [wpacu_pro]
		$isUnloadRegExMatch        = isset( $dataHH['unloads_regex_matches'][$assetType] ) && in_array( $dataHH['row']['obj']->handle,
				$dataHH['unloads_regex_matches'][$assetType] );
		$isLoadExceptionRegExMatch = isset( $dataHH['load_exceptions_regex_matches'][$assetType] ) && in_array( $dataHH['row']['obj']->handle,
				$dataHH['load_exceptions_regex_matches'][$assetType] );
		// [/wpacu_pro]

		$dataHH['row']['is_load_exception_per_page']    = $isLoadExceptionPerPage;
		$dataHH['row']['is_load_exception_regex_match'] = $isLoadExceptionRegExMatch;

		$isLoadException = $isLoadExceptionPerPage || $isLoadExceptionRegExMatch;

		// No load exception of any kind and a bulk unload rule is applied? Append the CSS class for unloading
		if ( ! $isLoadException && ( $dataHH['row']['is_group_unloaded'] || $isUnloadRegExMatch ) ) {
			if ( $isUnloadRegExMatch ) {
				$dataHH['row']['is_regex_unload_match'] = true;
			}
			$dataHH['row']['class'] .= ' wpacu_not_load';
		}

		$dataHH['row']['class'] .= ' script_' . $dataHH['row']['obj']->handle;

		return $dataHH;
	}
	// [/wpacu_pro]

	/**
	 * @param $htmlSource
	 *
	 * @return array
	 */
	public static function extractHtmlFromConditionalComments($htmlSource)
	{
		preg_match_all('#<!--\[if(.*?)]>(<!-->|-->|\s|)(.*?)(<!--<!|<!)\[endif]-->#si', $htmlSource, $matchedContent);

		if (isset($matchedContent[1], $matchedContent[3]) && ! empty($matchedContent[1]) && ! empty($matchedContent[3])) {
			$conditions = array_map('trim', $matchedContent[1]);
			$tags       = array_map('trim', $matchedContent[3]);

			return array(
				'conditions' => $conditions,
				'tags'       => $tags,
			);
		}

		return array();
	}

	/**
	 * @param $targetedTag
	 * @param $contentWithinConditionalComments
	 *
	 * @return bool
	 */
	public static function isWithinConditionalComment($targetedTag, $contentWithinConditionalComments)
	{
		if (empty($contentWithinConditionalComments)) {
			return false;
		}

		$targetedTag = trim($targetedTag);

		foreach ($contentWithinConditionalComments['tags'] as $tagIndex => $tagFromList) {
			$tagFromList = trim($tagFromList);

			if ($targetedTag === $tagFromList || strpos($targetedTag, $tagFromList) !== false) {
				return $contentWithinConditionalComments['conditions'][$tagIndex]; // Stops here and returns the condition
				break;
			}
		}

		return false; // Not within a conditional comment (most cases)
	}

	// [wpacu_pro]
	/**
	 * @return array
	 */
	public static function getHardcodedUnloadList()
	{
		$hardcodedUnloadList['wpacu_hardcoded_links']          = ObjectCache::wpacu_cache_get('wpacu_hardcoded_links')  ?: array();
		$hardcodedUnloadList['wpacu_hardcoded_styles']         = ObjectCache::wpacu_cache_get('wpacu_hardcoded_styles') ?: array();
		$hardcodedUnloadList['wpacu_hardcoded_scripts_src']    = ObjectCache::wpacu_cache_get('wpacu_hardcoded_scripts_src') ?: array();
		$hardcodedUnloadList['wpacu_hardcoded_scripts_inline'] = ObjectCache::wpacu_cache_get('wpacu_hardcoded_scripts_inline') ?: array();

		return Misc::filterList($hardcodedUnloadList);
	}
	// [/wpacu_pro]

	/**
	 * @param $htmlTag
	 *
	 * @return bool|string
	 */
	public static function belongsTo($htmlTag)
	{
		$belongList = array(
			'wpcf7recaptcha.' => '"Contact Form 7" plugin',
			'c = c.replace(/woocommerce-no-js/, \'woocommerce-js\');' => '"WooCommerce" plugin',
			'.woocommerce-product-gallery{ opacity: 1 !important; }'  => '"WooCommerce" plugin',
			'-ss-slider-3' => '"Smart Slider 3" plugin',
			'N2R(["nextend-frontend","smartslider-frontend","smartslider-simple-type-frontend"]' => '"Smart Slider 3" plugin',
			'function setREVStartSize' => '"Slider Revolution" plugin',
			'jQuery(\'.rev_slider_wrapper\')' => '"Slider Revolution" plugin',
			'jQuery(\'#wp-admin-bar-revslider-default' => '"Slider Revolution" plugin'
		);

		foreach ($belongList as $ifContains => $isFromSource) {
			if ( strpos( $htmlTag, $ifContains) !== false ) {
				return $isFromSource;
			}
		}

		return false;
	}

	// [wpacu_pro]
	/**
	 * Sometimes, libraries such as Minify_HTML (source: https://github.com/mrclay/minify/blob/master/lib/Minify.php) are used
	 * which alter the content of the hardcoded asset, so even though it's the same, Asset CleanUp (Pro) might not detect it
	 * Let's make sure they are still found in case Minify_HTML is triggered by a different optimization plugin (e.g. WP Rocket, Autoptimize)
	 *
	 * @param $hardcodedAsset
	 * @return array
	 */
	public static function alternativeValuesIfMinified($hardcodedAsset)
	{
		return array(
			MinifyHtml::minify($hardcodedAsset)
		);
	}
	// [/wpacu_pro]

	/**
	 * @param $data
	 *
	 * @return string
	 */
	public static function getHardCodedManageAreaForFrontEndView($data)
	{
		$dataSettingsFrontEnd = ObjectCache::wpacu_cache_get('wpacu_settings_frontend_data') ?: array();
		$dataSettingsFrontEnd['page_unload_text'] = $data['page_unload_text'];
		// The following string will be replaced by the values got the from the AJAX call to /?wpassetcleanup_load=1&wpacu_just_hardcoded
		$dataWpacuSettingsFrontend = base64_encode(json_encode($dataSettingsFrontEnd));
		$imgLoadingSpinnerUrl = admin_url('images/spinner.gif');

		$currentHardcodedAssetRules = '';

		// When the form is submitted it will clear some values if they are not sent anymore which can happen with a failed AJAX call to retrieve the list of hardcoded assets
		// Place the current values to the area in case the AJAX call fails and it won't print the list
		// If the user presses "Update", it won't clear any existing rules
		// If the list is printed, obviously it will be with all the fields in place as they should be
		foreach (array('current', 'load_exceptions', 'handle_unload_regex', 'handle_load_regex', 'handle_load_logged_in') as $ruleKey) {
			foreach ( array( 'styles', 'scripts' ) as $assetType ) {
				if ( isset( $dataSettingsFrontEnd[$ruleKey][ $assetType ] ) && ! empty( $dataSettingsFrontEnd[$ruleKey][$assetType] ) ) {
					// Go through the values, depending on how the array is structured
					// handle_unload_regex, handle_load_regex
					if (in_array($ruleKey, array('handle_unload_regex', 'handle_load_regex'))) {
						foreach ( $dataSettingsFrontEnd[ $ruleKey ][ $assetType ] as $assetHandle => $assetValues ) {
							if ( strpos( $assetHandle, 'wpacu_hardcoded_' ) !== false ) {
								if ($ruleKey === 'handle_unload_regex') {
									$enableValue                = isset( $assetValues['enable'] ) ? $assetValues['enable'] : '';
									$regExValue                 = isset( $assetValues['value'] ) ? $assetValues['value']   : '';
									$currentHardcodedAssetRules .= '<input type="hidden" name="wpacu_handle_unload_regex[' . $assetType . '][' . $assetHandle . '][enable]" value="' . $enableValue . '" />';
									$currentHardcodedAssetRules .= '<input type="hidden" name="wpacu_handle_unload_regex[' . $assetType . '][' . $assetHandle . '][value]"  value="' . esc_attr( $regExValue ) . '" />';
								} elseif ($ruleKey === 'handle_load_regex') {
									$enableValue                = isset( $assetValues['enable'] ) ? $assetValues['enable'] : '';
									$regExValue                 = isset( $assetValues['value'] ) ? $assetValues['value']   : '';
									$currentHardcodedAssetRules .= '<input type="hidden" name="wpacu_handle_load_regex[' . $assetType . '][' . $assetHandle . '][enable]" value="' . $enableValue . '" />';
									$currentHardcodedAssetRules .= '<input type="hidden" name="wpacu_handle_load_regex[' . $assetType . '][' . $assetHandle . '][value]"  value="' . esc_attr( $regExValue ) . '" />';
								}
							}
						}
					} else {
						// current, load_exceptions, handle_load_logged_in
						foreach ( $dataSettingsFrontEnd[ $ruleKey ][ $assetType ] as $assetHandle ) {
							if ( strpos( $assetHandle, 'wpacu_hardcoded_' ) !== false ) {
								if ( $ruleKey === 'current' ) {
									$currentHardcodedAssetRules .= '<input type="hidden" name="wpassetcleanup[' . $assetType . '][]" value="' . $assetHandle . '" />';
								} elseif ( $ruleKey === 'load_exceptions' ) {
									$currentHardcodedAssetRules .= '<input type="hidden" name="wpacu_styles_load_it[]" value="' . $assetHandle . '" />';
								} elseif ($ruleKey === 'handle_load_logged_in') {
									$currentHardcodedAssetRules .= '<input type="hidden" name="wpacu_load_it_logged_in['.$assetType.']['.$assetHandle.']" value="1" />';
								}
							}
						}
					}
				}
			}
		}

		$hardcodedManageAreaHtml = <<<HTML
<div class="wpacu-assets-collapsible-wrap wpacu-wrap-area wpacu-hardcoded" id="wpacu-assets-collapsible-wrap-hardcoded-list" data-wpacu-settings-frontend="{$dataWpacuSettingsFrontend}">
    <a class="wpacu-assets-collapsible wpacu-assets-collapsible-active" href="#" style="padding: 15px 15px 15px 44px;"><span class="dashicons dashicons-code-standards"></span> Hardcoded (non-enqueued) Styles &amp; Scripts</a>
    <div class="wpacu-assets-collapsible-content" style="max-height: inherit;">
        <div style="padding: 20px 0; margin: 0;"><img src="{$imgLoadingSpinnerUrl}" align="top" width="20" height="20" alt="" /> The list of hardcoded assets is fetched... Please wait...</div>
        {$currentHardcodedAssetRules}
    </div>
</div>
HTML;

		return $hardcodedManageAreaHtml;
	}
}
