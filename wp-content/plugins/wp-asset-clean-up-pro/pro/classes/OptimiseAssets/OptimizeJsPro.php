<?php
namespace WpAssetCleanUpPro\OptimiseAssets;

use WpAssetCleanUp\FileSystem;
use WpAssetCleanUp\Main;
use WpAssetCleanUp\Misc;
use WpAssetCleanUp\OptimiseAssets\OptimizeCommon;
use WpAssetCleanUp\OptimiseAssets\OptimizeJs;

/**
 * Class OptimizeJsPro
 * @package WpAssetCleanUpPro
 */
class OptimizeJsPro
{
	/**
	 * @return array
	 */
	public static function getAllInlineChosenPatterns()
	{
		$inlineJsFilesPatterns = trim(Main::instance()->settings['inline_js_files_list']);

		$allPatterns = array();

		if (strpos($inlineJsFilesPatterns, "\n")) {
			// Multiple values (one per line)
			foreach (explode("\n", $inlineJsFilesPatterns) as $inlinePattern) {
				$allPatterns[] = trim($inlinePattern);
			}
		} else {
			// Only one value?
			$allPatterns[] = trim($inlineJsFilesPatterns);
		}

		// Strip any empty values
		return array_filter($allPatterns);
	}

	/**
	 * @return bool
	 */
	public static function isInlineJsEnabled()
	{
		$isEnabledInSettingsWithListOrAuto = (Main::instance()->settings['inline_js_files'] &&
		                                      (trim(Main::instance()->settings['inline_js_files_list']) !== '' || self::isAutoInlineEnabled()));

		if (! $isEnabledInSettingsWithListOrAuto) {
			return false;
		}

		// Deactivate it for debugging purposes via query string /?wpacu_no_inline_js
		if (array_key_exists('wpacu_no_inline_js', $_GET)) {
			return false;
		}

		// Finally, return true
		return true;
	}

	/**
	 * @param $htmlSource
	 *
	 * @return mixed
	 */
	public static function doInline($htmlSource)
	{
		$allPatterns = self::getAllInlineChosenPatterns();

		// Skip any SCRIPT tags within conditional comments (e.g. Internet Explorer ones)
		preg_match_all('#<script[^>]*(type|\ssrc)(|\s+)=(|\s+)[^>]*(>)(.*)</script>#Usmi', OptimizeCommon::cleanerHtmlSource($htmlSource, array('strip_content_between_conditional_comments')), $matchesSourcesFromTags, PREG_SET_ORDER);

		// In case automatic inlining is used
		$belowSizeInput = (int)Main::instance()->settings['inline_js_files_below_size_input'];

		if ($belowSizeInput === 0) {
			$belowSizeInput = 1; // needs to have a minimum value
		}

		if (! empty($matchesSourcesFromTags)) {
			$cdnUrls = OptimizeCommon::getAnyCdnUrls();
			$cdnUrlForJs = isset($cdnUrls['js']) ? trim($cdnUrls['js']) : false;

			foreach ($matchesSourcesFromTags as $matchList) {
				$matchedTag = $matchList[0];

				// Do not inline the admin bar SCRIPT file, saving resources as it's shown for the logged-in user only
				if (strpos($matchedTag, '/wp-includes/js/admin-bar') !== false) {
					continue;
				}

				// They were preloaded for a reason, leave them
				if (strpos($matchedTag, 'data-wpacu-to-be-preloaded-basic=') !== false) {
					continue;
				}

				if (strip_tags($matchedTag) !== '') {
					continue; // something is funny, don't mess with the HTML alteration, leave it as it was
				}

				$chosenInlineJsMatches = false;

				// Condition #1: Only chosen (via textarea) CSS get inlined
				// Even if the JS was optimized and moved to the caching dir, the original location will be within "data-wpacu-script-rel-src-before" attribute
				// Condition #1: Only chosen (via textarea) CSS get inlined
				if ( false !== strpos( $matchedTag, ' wpacu-to-be-inlined' ) ) {
					$chosenInlineJsMatches = true;
				} elseif ( ! empty( $allPatterns ) ) {
					// Fallback, in case "wpacu-to-be-inlined" was not already added to the tag
					foreach ($allPatterns as $patternToCheck) {
						if (preg_match('#'.$patternToCheck.'#si', $matchedTag) || strpos($matchedTag, $patternToCheck) !== false) {
							$chosenInlineJsMatches = true;
							break;
						}
					}
				}

				// Is auto inline disabled and the chosen JS does not match? Continue to the next SCRIPT tag
				if (! $chosenInlineJsMatches && ! self::isAutoInlineEnabled()) {
					continue;
				}

				preg_match_all('#\ssrc=(["\'])' . '(.*)' . '(["\'])#Usmi', $matchedTag, $outputMatches);

				$scriptSrcOriginal = trim($outputMatches[2][0], '"\'');
				$localAssetPath = OptimizeCommon::getLocalAssetPath($scriptSrcOriginal, 'js');

				if (! $localAssetPath) {
					continue; // Not on the same domain
				}

				// Condition #2: Auto inline is enabled and there's no match for any entry in the textarea
				if (! $chosenInlineJsMatches && self::isAutoInlineEnabled()) {
					$fileSizeKb = number_format(filesize($localAssetPath) / 1024, 2);

					// If it's not smaller than the value from the input, do not continue with the inlining
					if ($fileSizeKb >= $belowSizeInput) {
						continue;
					}
				}

				$appendBeforeAnyRelPath = $cdnUrlForJs ? OptimizeCommon::cdnToUrlFormat($cdnUrlForJs, 'raw') : '';

				$jsContent = OptimizeJs::maybeDoJsFixes(
					FileSystem::file_get_contents($localAssetPath), // JS content
					$appendBeforeAnyRelPath . OptimizeCommon::getPathToAssetDir($scriptSrcOriginal) . '/'
				);

				if (preg_match('/(\s+)defer((\/s+|)=(\/s+|)|>|\s+)/i', str_replace($scriptSrcOriginal, '', $matchedTag))) { // has defer attribute
					$jsContent = 'document.addEventListener(\'DOMContentLoaded\', function() {'."\n".$jsContent."\n".'});';
				}

				// The JS file is read from its original plugin/theme/cache location
				// If minify was enabled, then it's already minified, no point in re-minify it to save resources
				$jsContentArray = OptimizeJs::maybeAlterContentForJsFile($jsContent, false);
				$jsContent = trim($jsContentArray['content']);

				if ($jsContent && $jsContent !== '/**/') {
					$typeAttr = Misc::getScriptTypeAttribute();

					$htmlSource = str_replace(
						$matchedTag,
						'<script '.$typeAttr.' data-wpacu-inline-js-file="1">' . "\n" . $jsContent . "\n" . '</script>',
						$htmlSource
					);
				} else {
					// After JS alteration (e.g. minify), there's no content left, most likely the JS file contained only comments and empty spaces
					// Strip the tag completely as there's no reason to print an empty SCRIPT tag to further add to the total DOM elements
					$htmlSource = str_replace($matchedTag, '', $htmlSource);
				}
			}
		}

		return $htmlSource;
	}

	/**
	 * @return bool
	 */
	public static function isAutoInlineEnabled()
	{
		return Main::instance()->settings['inline_js_files'] &&
		       Main::instance()->settings['inline_js_files_below_size'] &&
		       (int)Main::instance()->settings['inline_js_files_below_size_input'] > 0;
	}

	/**
	 * @param $htmlSource
	 *
	 * @return mixed
	 */
	public static function moveScriptsFromHeadToBody($htmlSource)
	{
		preg_match_all('#<head[^>]*>(.*?)</head>(.*?)<body#is', OptimizeCommon::cleanerHtmlSource($htmlSource, array('strip_content_between_conditional_comments')), $matches);

		if (isset($matches[0][0]) && $matches[0][0]) {
			preg_match_all('#<script[^>]*>(.*?)</script>#si', $matches[0][0], $matchesTwo);

			$headScripts = array();

			if (isset($matchesTwo[0]) && ! empty($matchesTwo[0])) {
				foreach ($matchesTwo[0] as $scriptTag) {

					// Only the SCRIPT tags with no "type" or the ones with "text/javascript" are considered
					preg_match_all('#type=(["\'])' . '(.*)' . '(["\'])#Usmi', $scriptTag, $outputMatches);
					$scriptType = isset($outputMatches[2][0]) ? trim($outputMatches[2][0], '"\'') : 'text/javascript'; // default if none is set
					if ($scriptType !== 'text/javascript') {
						continue;
					}

					// Replace the first match only in rare cases there are multiple SCRIPT tags with the same code
					if ($scriptTag && ($pos = strpos($htmlSource, $scriptTag)) !== false && ! self::skipMovingToBody($scriptTag)) {
						$headScripts[] = $scriptTag;
						$htmlSource = substr_replace($htmlSource, '', $pos, strlen($scriptTag));
					}
				}
			}
		}

		if (! empty($headScripts)) {
			preg_match_all('#</head>(.*?)<body[^>]*>#si', $htmlSource, $matches);

			if (isset($matches[0][0]) && $matches[0][0]) {
				$htmlSource = str_replace($matches[0][0], $matches[0][0] . "\n". implode("\n", $headScripts), $htmlSource);
			}

			}

		return $htmlSource;
	}

	/**
	 * @param $scriptTag
	 *
	 * @return bool
	 */
	public static function skipMovingToBody($scriptTag)
	{
		$regExps = array();

		if (Main::instance()->settings['move_scripts_to_body_exceptions'] !== '') {
			$moveScriptsToBodyExceptions = trim(Main::instance()->settings['move_scripts_to_body_exceptions']);

			if (strpos($moveScriptsToBodyExceptions, "\n")) {
				// Multiple values (one per line)
				foreach (explode("\n", $moveScriptsToBodyExceptions) as $moveScriptsToBodyException) {
					$regExps[] = '#'.trim(preg_quote($moveScriptsToBodyException, '/')).'#';
				}
			} else {
				// Only one value?
				$regExps[] = '#'.trim(preg_quote($moveScriptsToBodyExceptions, '/')).'#';
			}
		}

		// Automatically check for //cdn.ampproject.org/ (AMP pages)
		// Do not move them to BODY as they should be kept in the HEAD
		$regExps[] = '#' . '//cdn.ampproject.org/' . '#';

		foreach ($regExps as $regExp) {
			if (preg_match($regExp, $scriptTag)) {
				return true; // Do not move it
			}
		}

		return false; // Move it
	}
}
