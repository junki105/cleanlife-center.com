<?php
namespace WpAssetCleanUpPro\OptimiseAssets;

use WpAssetCleanUp\Main;
use WpAssetCleanUp\OptimiseAssets\FontsLocal;

/**
 * Class LocalFontsPro
 * @package WpAssetCleanUpPro\OptimiseAssets
 */
class FontsLocalPro
{
	/**
	 * It will append 'font-display' to the CSS @font-face
	 *
	 * @param $cssContent
	 *
	 * @return mixed
	 */
	public static function alterLocalFontFaceFromCssContent($cssContent)
	{
		if ( ! Main::instance()->settings['local_fonts_display'] || FontsLocal::preventAnyChange() ) {
			return $cssContent;
		}

		$fontDisplayChosen = Main::instance()->settings['local_fonts_display'];
		$fontDisplayOverwrite = Main::instance()->settings['local_fonts_display_overwrite'];

		preg_match_all('#@font-face(|\s+){(.*?)}#si', $cssContent, $matchesFromCssCode, PREG_SET_ORDER);

		if (! empty($matchesFromCssCode)) {
			foreach ($matchesFromCssCode as $matchedFontFace) {
				$fontFaceOuterContent = trim($matchedFontFace[0]);

				if (PHP_VERSION_ID >= 70000) {
					try {
						// Some PHP RegEx warnings that won't affect the functionality might throw up for PHP >= 7; do not show them
						$cleanFontFaceContent = $fontFaceOuterContentUpdated = self::cleanerCssContent($fontFaceOuterContent);
					} catch (\Error $e) {
						$cleanFontFaceContent = $fontFaceOuterContentUpdated = $fontFaceOuterContent;
					}
				} else {
					$cleanFontFaceContent = $fontFaceOuterContentUpdated = self::cleanerCssContent($fontFaceOuterContent);
				}

				$cleanFontFaceContent = preg_replace('!\s+!', ' ', $cleanFontFaceContent);
				$cleanFontFaceContent = preg_replace('/@font-face(|\s+){(|\s+)}/i', '', $cleanFontFaceContent);

				if (! $cleanFontFaceContent) {
					continue;
				}

				$cleanFontFaceContent = trim($cleanFontFaceContent);

				// Add it if it's not there already
				if (stripos($cleanFontFaceContent, 'font-display') === false) {
					if (substr($cleanFontFaceContent, -1) === '}') {
						// No room for errors; Check the syntax before the CSS property
						$withoutCurlyBraces = trim(trim($cleanFontFaceContent, '}{'));
						$appendBefore = (substr($withoutCurlyBraces, -1) !== ';') ? ';' : '';
						$fontFaceOuterContentUpdated = substr_replace($fontFaceOuterContent, $appendBefore.' font-display:'.$fontDisplayChosen.';}', -1);
					}
				} elseif ($fontDisplayOverwrite && stripos($cleanFontFaceContent, 'font-display') !== false) {
					// Already there? Overwrite it
					preg_match_all('/font-display(|\s+):(|\s+)(?<value>.*?)(;|\s+;|}|\s+})/i', $cleanFontFaceContent, $matchesFontDisplay);

					if (isset($matchesFontDisplay[0][0], $matchesFontDisplay['value'][0])) {
						$totalMatches = count($matchesFontDisplay[0]);

						for ($mI = 0; $mI < $totalMatches; $mI++) {
							$currentDisplayProperty      = trim($matchesFontDisplay[0][$mI]);

							$currentDisplayPropertyRexExp = preg_replace('!\s+!', '(\s+|)', preg_quote($currentDisplayProperty, '/'));
							$currentDisplayPropertyRexExp = '/'.$currentDisplayPropertyRexExp.'/i';

							$currentDisplayPropertyValue = trim($matchesFontDisplay['value'][$mI]);

							$newDisplayProperty          = str_replace($currentDisplayPropertyValue, $fontDisplayChosen, $currentDisplayProperty);
							$newDisplayProperty = preg_replace('!\s+!', ' ', $newDisplayProperty);

							$fontFaceOuterContentUpdated = preg_replace($currentDisplayPropertyRexExp, $newDisplayProperty, $fontFaceOuterContentUpdated);
						}
					}
				}

				$fontFaceOuterContentNew = str_replace($fontFaceOuterContent, $fontFaceOuterContentUpdated, $fontFaceOuterContent);
				$fontFaceOuterContentNew = preg_replace('/@font-face(|\s+){(|\s+)}/i', '', $fontFaceOuterContentNew);

				$cssContent = str_replace($fontFaceOuterContent, $fontFaceOuterContentNew, $cssContent);
			}
		}

		return $cssContent;
	}

	/**
	 * @param $cssOuterContent
	 *
	 * @return string
	 */
	public static function cleanerCssContent($cssOuterContent)
	{
		$regex = array(
			"`^([\t\s]+)`ism"                       => '',
			"`([:;}{]{1})([\t\s]+)(\S)`ism"         => '$1$3',
			"`(\S)([\t\s]+)([:;}{]{1})`ism"         => '$1$3',
			"`\/\*(.+?)\*\/`ism"                    => '',
			"`(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+`ism" => "\n"
		);

		return trim(preg_replace(array_keys($regex), array_values($regex), $cssOuterContent));
	}

	/**
	 * It will scan all STYLE tags and alter any that contains @font-face
	 *
	 * @param $htmlSource
	 *
	 * @return mixed
	 */
	public static function alterLocalFontFaceFromInlineStyleTags($htmlSource)
	{
		// Avoid further processing if there is no @font-face in the HTML source
		if (! preg_match('/@font-face(\s+|){/i', $htmlSource)) {
			return $htmlSource;
		}

		preg_match_all('#<\s*?style\b[^>]*>(.*?)</style\b[^>]*>#s', $htmlSource, $styleMatches, PREG_SET_ORDER);

		// No STYLE tags? Stop here
		if (empty($styleMatches)) {
			return $htmlSource;
		}

		// Go through each STYLE tag
		foreach ($styleMatches as $styleInlineArray) {
			list($styleInlineTag, $styleInlineContent) = $styleInlineArray;

			// Is the content relevant?
			if (! preg_match('/@font-face(\s+|){/i', $styleInlineContent)) {
				continue;
			}

			// Alter @font-face content
			$newCssOutput = self::alterLocalFontFaceFromCssContent($styleInlineTag);

			$htmlSource = str_replace($styleInlineTag, $newCssOutput, $htmlSource);
		}

		return $htmlSource;
	}
}
