<?php
namespace WpAssetCleanUpPro\OptimiseAssets;

use WpAssetCleanUp\ObjectCache;

/**
 * Class MatchMediaLoadPro
 * @package WpAssetCleanUp\OptimiseAssets
 */
class MatchMediaLoadPro
{
	/**
	 *
	 */
	public function init()
	{
		add_filter('wpacu_media_queries_load_for_css', array($this, 'alterHtmlSourceForMediaQueriesLoadCss'));
		add_filter('wpacu_media_queries_load_for_js',  array($this, 'alterHtmlSourceForMediaQueriesLoadJs'));
	}

	/**
	 * @param $htmlSource
	 *
	 * @return string|string[]
	 */
	public function alterHtmlSourceForMediaQueriesLoadCss($htmlSource)
	{
		preg_match_all('#<link[^>]*(data-wpacu-apply-media-query)[^>]*(>)#Umi', $htmlSource, $matchesSourcesFromTags, PREG_SET_ORDER);

		$linkTagsToFallback = array();

		if (! empty($matchesSourcesFromTags)) {
			// [START Check if Handle is Eligible For The Feature]
			// The handle has to be a "child" or "independent", but not a "parent"
			$allCssDepsParentToChild = self::getAllParentToChildInRelationToMarkedHandles('css');
			// [END Check if Handle is Eligible For The Feature]

			foreach ($matchesSourcesFromTags as $matchedValues) {
				$matchedTag = $matchedValues[0];

				// Check if the tag has any 'data-wpacu-skip' attribute or if it's marked for preloading (as it was done for a reason)
				// If there's a match, leave the tag as it is, no media query load would be applied
				if (preg_match('#data-wpacu-skip([=>/ ])#i', $matchedTag) ||
				    (strpos($matchedTag, 'data-wpacu-preload-it-async=') !== false) ||
				    (strpos($matchedTag, 'data-wpacu-to-be-preloaded-basic=') !== false)) {
					continue;
				}

				preg_match_all('#data-wpacu-style-handle=(["\'])' . '(.*)' . '(["\'])#Usmi', $matchedTag, $outputMatchesMedia);
				$tagHandle = isset($outputMatchesMedia[2][0]) ? trim($outputMatchesMedia[2][0], '"\'') : '';

				if (isset($allCssDepsParentToChild[$tagHandle])) {
					// Has "children", this is not supported yet and somehow it was added as a rule (or someone tries to hack it)
					continue;
				}

				preg_match_all('#data-wpacu-apply-media-query=(["\'])' . '(.*)' . '(["\'])#Usmi', $matchedTag, $outputMatchesMedia);
				$mediaQueryValue = isset($outputMatchesMedia[2][0]) ? trim($outputMatchesMedia[2][0], '"\'') : '';

				$newTag = self::maybeAlterToMatchMedia($tagHandle, $matchedTag, $mediaQueryValue, 'css');

				if ($matchedTag !== $newTag) {
					$htmlSource = str_replace( $matchedTag, $newTag, $htmlSource );
					$linkTagsToFallback[] = $matchedTag;
				}
			}
		}

		if ( ! empty($linkTagsToFallback) ) {
			ObjectCache::wpacu_cache_add('wpacu_link_tags_fallback', $linkTagsToFallback);
		}

		return $htmlSource;
	}

	/**
	 * @param $htmlSource
	 *
	 * @return string|string[]
	 */
	public function alterHtmlSourceForMediaQueriesLoadJs($htmlSource)
	{
		preg_match_all('#(<script[^>]*(data-wpacu-apply-media-query)(|\s+)=(|\s+)[^>]*>)|(<link[^>]*(as(\s+|)=(\s+|)(|"|\')script(|"|\'))[^>]*>)#Umi', $htmlSource, $matchesSourcesFromTags, PREG_SET_ORDER);

		if (! empty($matchesSourcesFromTags)) {
			// [START Check if Handle is Eligible For The Feature]
			// The handle has to be a "child" or "independent", but not a "parent"
			$allJsDepsParentToChild = self::getAllParentToChildInRelationToMarkedHandles('js');
			// [END Check if Handle is Eligible For The Feature]

			// [Collect basic preloads]
			$matchedBasicPreloadsSrcs = array();

			foreach ( $matchesSourcesFromTags as $matchedValues ) {
				$matchedTag = $matchedValues[0];

				if (strpos($matchedTag, 'data-wpacu-preload-js=') !== false) {
					preg_match_all('#href=(["\'])' . '(.*)' . '(["\'])#Usmi', $matchedTag, $outputMatchesMedia);
					$matchedBasicPreloadsSrcs[] = isset($outputMatchesMedia[2][0]) ? trim($outputMatchesMedia[2][0], '"\'') : '';
				}
			}

			$matchedBasicPreloadsSrcs = array_filter($matchedBasicPreloadsSrcs);
			// [/Collect basic preloads]

			foreach ($matchesSourcesFromTags as $matchedValues) {
				$matchedTag = $matchedValues[0];

				// Check if the tag has any 'data-wpacu-skip' attribute; if it does, do not alter it
				if (preg_match('#data-wpacu-skip([=>/ ])#i', $matchedTag)) {
					continue;
				}

				// Extract "src"
				preg_match_all('#src=(["\'])' . '(.*)' . '(["\'])#Usmi', $matchedTag, $outputMatchesMedia);
				$scriptSrc = isset($outputMatchesMedia[2][0]) ? trim($outputMatchesMedia[2][0], '"\'') : '';

				if (in_array($scriptSrc, $matchedBasicPreloadsSrcs)) {
					// this SCRIPT is already preloaded, thus, it makes no sense to apply any media queries loading
					// as it defies the purpose (the browser already download the file)
					continue;
				}

				preg_match_all('#data-wpacu-script-handle=(["\'])' . '(.*)' . '(["\'])#Usmi', $matchedTag, $outputMatchesMedia);
				$tagHandle = isset($outputMatchesMedia[2][0]) ? trim($outputMatchesMedia[2][0], '"\'') : '';

				if (isset($allJsDepsParentToChild[$tagHandle])) {
					// Has "children", this is not supported yet and somehow it was added as a rule (or someone tries to hack it)
					continue;
				}

				preg_match_all('#data-wpacu-apply-media-query=(["\'])' . '(.*)' . '(["\'])#Usmi', $matchedTag, $outputMatchesMedia);
				$mediaQueryValue = isset($outputMatchesMedia[2][0]) ? trim($outputMatchesMedia[2][0], '"\'') : '';

				$matchedCompleteTag = $matchedTag.'</script>';

				$newTag = self::maybeAlterToMatchMedia($tagHandle, $matchedCompleteTag, $mediaQueryValue, 'js');

				if ($matchedTag !== $newTag) {
					$htmlSource = str_replace( $matchedCompleteTag, $newTag, $htmlSource );
				}
			}
		}

		return $htmlSource;
	}

	/**
	 * @param $tagHandle
	 * @param $htmlTag
	 * @param $mediaQueryValue
	 * @param $assetType
	 *
	 * @return string
	 */
	public static function maybeAlterToMatchMedia($tagHandle, $htmlTag, $mediaQueryValue, $assetType)
	{
		if ((! $tagHandle) || (! $htmlTag) || (! $mediaQueryValue)) {
			return $htmlTag;
		}

		// Extra check: make sure the targeted handle doesn't have any "children" (independent or has "parents")
		// as there's no support for such handles at this time


		// Check if there are any media queries set (e.g. mobile, desktop, custom ones) for this tag
		// To only load when the media query matches
		if ($assetType === 'css') {
			$attrToSet = 'wpacu-' . str_replace(array(' '), '_', sanitize_title( $tagHandle ) . '-href');
			$htmlTag   = str_replace( ' href=', ' ' . $attrToSet . '=', $htmlTag );

			$wpacuJsFunc        = str_replace( '-', '_', 'wpacu_' . sanitize_title( $tagHandle ) . '_match_media' );
			$wpacuMatchMediaVar = str_replace( '-', '_', 'wpacu_' . sanitize_title( $tagHandle ) . '_match_media_var' );

			$wpacuHtmlMatchMedia = <<<HTML
<script>
function myFunc(matchMediaVar) {
    if (matchMediaVar.matches) { 
        var wpacuHrefAttr = document.querySelectorAll("[{$attrToSet}]")[0].getAttribute('{$attrToSet}');
        document.querySelectorAll("[{$attrToSet}]")[0].setAttribute('href', wpacuHrefAttr); 
    }
}
try { var matchMediaVar = window.matchMedia("{$mediaQueryValue}"); myFunc(matchMediaVar); matchMediaVar.addListener(myFunc); }
catch (wpacuError) {
	var wpacuHrefAttr = document.querySelectorAll("[{$attrToSet}]")[0].getAttribute('{$attrToSet}');
    document.querySelectorAll("[{$attrToSet}]")[0].setAttribute('href', wpacuHrefAttr); 
}
</script>
HTML;
			$wpacuHtmlMatchMedia = str_replace(
				array( 'myFunc', 'matchMediaVar' ),
				array( $wpacuJsFunc, $wpacuMatchMediaVar ),
				$wpacuHtmlMatchMedia );

			return $htmlTag . $wpacuHtmlMatchMedia;
		}

		if ($assetType === 'js') {
			$attrToSet = 'wpacu-' . str_replace(array(' '), '_', sanitize_title( $tagHandle ) . '-src');
			$htmlTag   = str_replace( ' src=', ' ' . $attrToSet . '=', $htmlTag );

			$wpacuJsFunc        = str_replace( array('-', ' '), '_', 'wpacu_' . sanitize_title( $tagHandle ) . '_match_media' );
			$wpacuMatchMediaVar = str_replace( array('-', ' '), '_', 'wpacu_' . sanitize_title( $tagHandle ) . '_match_media_var' );

			$wpacuHtmlMatchMedia = <<<HTML
<script>
function myFunc(matchMediaVar) {
    if (matchMediaVar.matches) {
        var wpacuSrcAttr = document.querySelectorAll("[{$attrToSet}]")[0].getAttribute('{$attrToSet}');
        document.querySelectorAll("[{$attrToSet}]")[0].setAttribute('src', wpacuSrcAttr); 
    }
}
try { var matchMediaVar = window.matchMedia("{$mediaQueryValue}"); myFunc(matchMediaVar); matchMediaVar.addListener(myFunc); }
catch (wpacuError) {
  	var wpacuHrefAttr = document.querySelectorAll("[{$attrToSet}]")[0].getAttribute('{$attrToSet}');
    document.querySelectorAll("[{$attrToSet}]")[0].setAttribute('href', wpacuHrefAttr); 
}
</script>
HTML;
			$wpacuHtmlMatchMedia = str_replace(
				array( 'myFunc', 'matchMediaVar' ),
				array( $wpacuJsFunc, $wpacuMatchMediaVar ),
				$wpacuHtmlMatchMedia );

			return $htmlTag . $wpacuHtmlMatchMedia;
		}

		// Finally, return the tag if there were no changes applied
		return $htmlTag;
	}

	/**
	 * If any current handle marked for media query load has any "children", do not alter it
	 *
	 * @param $assetType
	 *
	 * @return array
	 */
	public static function getAllParentToChildInRelationToMarkedHandles($assetType)
	{
		if ($assetType === 'css') {
			$allCssDepsParentToChild = array();
			$allCssMediaQueriesLoadMarkedHandlesList = ObjectCache::wpacu_cache_get('wpacu_css_media_queries_load_current_page') ?: array();

			global $wp_styles;

			if (isset($wp_styles->registered) && ! empty($wp_styles->registered)) {
				foreach ( $wp_styles->registered as $assetHandle => $assetObj ) {
					if ( isset( $assetObj->deps ) && ! empty( $assetObj->deps ) ) {
						foreach ( $assetObj->deps as $dep ) {
							if (isset($wp_styles->done) && in_array($assetHandle, $allCssMediaQueriesLoadMarkedHandlesList) && in_array($assetHandle, $wp_styles->done)) {
								$allCssDepsParentToChild[$dep][] = $assetHandle;
							}
						}
					}
				}
			}

			return $allCssDepsParentToChild;
		}

		if ($assetType === 'js') {
			$allJsDepsParentToChild = array();
			$allJsMediaQueriesLoadMarkedHandlesList = ObjectCache::wpacu_cache_get( 'wpacu_js_media_queries_load_current_page' ) ?: array();

			global $wp_scripts;

			if ( isset( $wp_scripts->registered ) && ! empty( $wp_scripts->registered ) ) {
				foreach ( $wp_scripts->registered as $assetHandle => $assetObj ) {
					if ( isset( $assetObj->deps ) && ! empty( $assetObj->deps ) ) {
						foreach ( $assetObj->deps as $dep ) {
							if ( isset( $wp_scripts->done ) && is_array($wp_scripts->done) && is_array($allJsMediaQueriesLoadMarkedHandlesList) &&
							     in_array($assetHandle, $allJsMediaQueriesLoadMarkedHandlesList) &&
							     in_array($assetHandle, $wp_scripts->done) ) {
								$allJsDepsParentToChild[ $dep ][] = $assetHandle;
							}
						}
					}
				}
			}

			return $allJsDepsParentToChild;
		}

		return array(); // should get here, unless the $assetType is not valid
	}
}