<?php
/*
 * No direct access to this file
 */
if (! isset($data, $locationKey, $criticalCssConfig)) {
	exit;
}

$enable     = isset($criticalCssConfig[$locationKey]['enable']) && $criticalCssConfig[$locationKey]['enable'];
$showMethod = (isset($criticalCssConfig[$locationKey]['show_method']) && $criticalCssConfig[$locationKey]['show_method']) ? $criticalCssConfig[$locationKey]['show_method'] : 'original';

$contentDataJson = get_option(WPACU_PLUGIN_ID.'_critical_css_location_key_'.$locationKey);
$contentData = @json_decode($contentDataJson, ARRAY_A);

$textareaContent = '';

// Only the original content will show in the textarea as the admin has choices how to have it printed in the front-end view
if (isset($contentData['content_original']) && $contentData['content_original']) {
	$textareaContent = stripslashes($contentData['content_original']);
}
?>
<div class="wpacu-wrap <?php if ($data['wpacu_settings']['input_style'] !== 'standard') { ?>wpacu-switch-enhanced<?php } else { ?>wpacu-switch-standard<?php } ?>">
    <?php
    include_once __DIR__.'/_applies-to.php';

    if ($data['for'] === 'custom-post-types') {
        ?>
        <div style="margin: 0 0 22px;">
            <p>Choose the custom post type for which you want to apply the critical CSS on the singular pages:</p>
            <?php
            \WpAssetCleanUpPro\OptimiseAssets\CriticalCssPro::buildCustomPostTypesListLinks($data['post_types_list'], $data['chosen_post_type'], $criticalCssConfig);
            ?>
        </div>
    <?php
    }

    if ($data['for'] === 'custom-taxonomy') {
        ?>
        <div style="margin: 0 0 22px;">
            <p>Choose the custom taxonomy for which you want to apply the critical CSS on the singular pages:</p>
		    <?php
		    \WpAssetCleanUpPro\OptimiseAssets\CriticalCssPro::buildTaxonomyListLinks($data['taxonomy_list'], $data['chosen_taxonomy'], $criticalCssConfig);
		    ?>
        </div>
        <?php
    }
    ?>
    <label class="wpacu_switch wpacu_with_text">
		<input type="checkbox"
               data-wpacu-custom-page-type="<?php if ($data['for'] === 'custom-post-types') { echo $data['chosen_post_type'].'_post_type'; } elseif ($data['for'] === 'custom-taxonomy') { echo $data['chosen_taxonomy'].'_taxonomy'; } ?>"
		       id="wpacu_critical_css_status"
			<?php if ($enable) { echo 'checked="checked"'; } ?>
			   name="<?php echo WPACU_PLUGIN_ID . '_critical_css'; ?>[enable]"
			   value="1" /> <span class="wpacu_slider wpacu_round"></span> </label> &nbsp; * you can enable/disable at any time the critical CSS functionality for all the pages from this group (e.g. disabling it won't remove the any current CSS content in case you will ever need it again); if you enable it, you have to provide the critical CSS content

	<div style="margin: 25px 0 0;" class="clearfix"></div>

	<div id="wpacu-critical-css-options-area" class="<?php if (! $enable) { echo 'wpacu-faded'; } ?>">
		<div id="wpacu-css-editor-area">
			<textarea name="<?php echo WPACU_PLUGIN_ID . '_critical_css'; ?>[content]" id="wpacu-css-editor-textarea"><?php echo $textareaContent; ?></textarea>
		</div>

		<div style="margin: 25px 0 0;" class="clearfix"></div>

		<div>
			<strong>How to print it in the front-end view?</strong>
			<ul>
				<li><label for="wpacu_show_critical_css_original_option">
						<input id="wpacu_show_critical_css_original_option" <?php if ( $showMethod === 'original' ) {
							echo 'checked="checked"';
						} ?> type="radio" name="<?php echo WPACU_PLUGIN_ID . '_critical_css'; ?>[show_method]"
						       value="original"/>&nbsp;As it is (it will print exactly as it is showing in the textarea)</label>
				</li>
				<li><label for="wpacu_show_critical_css_minified_option">
						<input id="wpacu_show_critical_css_minified_option" <?php if ( $showMethod === 'minified' ) {
							echo 'checked="checked"';
						} ?> type="radio" name="<?php echo WPACU_PLUGIN_ID . '_critical_css'; ?>[show_method]"
						       value="minified"/>&nbsp;Minified (if it's not already minified, it's good to enable this option to save some KB)</label>
				</li>
			</ul>
		</div>
	</div>
	<input type="hidden" name="<?php echo WPACU_PLUGIN_ID . '_critical_css'; ?>[location_key]" value="<?php echo $locationKey; ?>" />
</div>
