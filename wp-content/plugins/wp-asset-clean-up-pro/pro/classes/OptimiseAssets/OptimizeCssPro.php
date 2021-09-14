<?php
namespace WpAssetCleanUpPro\OptimiseAssets;

use WpAssetCleanUpPro\Positions;

/**
 * Class OptimizeCssPro
 * @package WpAssetCleanUpPro
 */
class OptimizeCssPro
{
	/**
	 *
	 */
	public function init()
	{
		add_filter('wpacu_local_fonts_display_css_output',   array($this, 'updateCssOutputFontDisplay'), 10, 2);
		add_filter('wpacu_local_fonts_display_style_inline', array($this, 'updateInlineCssOutputFontDisplay'), 10, 2); // alters $htmlSource
		add_filter('wpacu_change_css_position',              array($this, 'changeCssPosition'), 10, 1);
	}

	/**
	 * @param $cssContent
	 * @param $enable
	 *
	 * @return mixed
	 */
	public function updateCssOutputFontDisplay($cssContent, $enable)
	{
		if (! $enable || ! preg_match('/@font-face(\s+|){/i', $cssContent)) {
			return $cssContent;
		}

		// "font-display" is enabled in "Settings" - "Local Fonts"
		return FontsLocalPro::alterLocalFontFaceFromCssContent($cssContent);
	}

	/**
	 * @param $htmlSource
	 * @param $status
	 *
	 * @return mixed
	 */
	public function updateInlineCssOutputFontDisplay($htmlSource, $status)
	{
		if (! $status) {
			return $htmlSource;
		}

		return FontsLocalPro::alterLocalFontFaceFromInlineStyleTags($htmlSource);
	}

	/**
	 * @param $htmlSource
	 *
	 * @return mixed|void
	 */
	public function changeCssPosition($htmlSource)
	{
		return Positions::doChanges($htmlSource);
	}
}
