<?php
/*
 * The file is included from /templates/meta-box-loaded-assets/_asset-style-single-row.php
*/

if (! isset($data)) {
	exit; // no direct access
}

$childHandles = isset($data['all_deps']['parent_to_child']['styles'][$data['row']['obj']->handle]) ? $data['all_deps']['parent_to_child']['styles'][$data['row']['obj']->handle] : array();

$handleAllStatuses = array();

if (! empty($childHandles)) {
	$handleAllStatuses[] = 'is_parent';
}

if (isset($data['row']['obj']->deps) && ! empty($data['row']['obj']->deps)) {
	$handleAllStatuses[] = 'is_child';
}

if (empty($handleAllStatuses)) {
    $handleAllStatuses[] = 'is_independent';
}

// No media attribute different than "all"
$linkHasDistinctiveMediaAttr = (isset($data['row']['obj']->args) && $data['row']['obj']->args && $data['row']['obj']->args !== 'all');

$showMatchMediaFeature = true; // always show it for CSS files (alert the admin if the rule is applied to a "parent" that has "children")
$showMatchMediaAlertForParentCss = false;

// Is "parent" of other "children"? Show an alert to the admin!
if (in_array('is_parent', $handleAllStatuses)) {
	$showMatchMediaAlertForParentCss = true;
}

if ( $showMatchMediaFeature && ! $linkHasDistinctiveMediaAttr ) {
?>
    <!-- [wpacu_pro] -->
    <?php
    if (isset($data['row']['obj']->src) && $data['row']['obj']->src !== '') {
    ?>
        <div style="margin: 0 0 15px;">
            <?php
            $matchMediaLoadArray = (isset($data['media_queries_load']['styles'][$data['row']['obj']->handle]) && $data['media_queries_load']['styles'][$data['row']['obj']->handle])
                ? $data['media_queries_load']['styles'][$data['row']['obj']->handle]
                : array();

            $matchMediaLoadEnable = (isset($matchMediaLoadArray['enable']) && $matchMediaLoadArray['enable']);
            $matchMediaLoadValue  = (isset($matchMediaLoadArray['value'])  && $matchMediaLoadArray['value']) ? $matchMediaLoadArray['value'] : '';

            $wpacuDataForId = 'wpacu_handle_media_query_load_style_'.$data['row']['obj']->handle;
            ?>

            If kept loaded, make the browser download the file&nbsp;
                <select data-handle="<?php echo $data['row']['obj']->handle; ?>"
                        <?php if ($showMatchMediaAlertForParentCss) { echo ' data-wpacu-show-parent-alert '; } ?>
                        name="wpacu_media_queries_load[styles][<?php echo $data['row']['obj']->handle; ?>][enable]"
                        class="wpacu-screen-size-load wpacu-for-style">
                    <option <?php if (! $matchMediaLoadEnable) { echo 'selected="selected"'; } ?> value="">on any screen size (default)</option>
                    <option <?php if ($matchMediaLoadEnable) { echo ' selected="selected" '; } ?> value="1">if the media query is matched</option>
                </select>

            <input type="hidden" name="wpacu_media_queries_load[styles][<?php echo $data['row']['obj']->handle; ?>][value]" value="" />

            <div data-style-handle="<?php echo $data['row']['obj']->handle; ?>"
                 class="wpacu-handle-media-queries-load-field <?php if ($matchMediaLoadEnable) { echo 'wpacu-is-visible'; } ?> wpacu-fade-in">
                    <textarea id="<?php echo $wpacuDataForId; ?>"
                      style="min-height: 40px;"
                      class="wpacu-handle-media-queries-load-field-input"
                      data-wpacu-adapt-height="1"
                      data-wpacu-is-empty-on-page-load="<?php echo (! $matchMediaLoadValue) ? 'true' : 'false'; ?>"
                      <?php if (! $matchMediaLoadValue) { echo 'disabled="disabled"'; } ?>
                      name="wpacu_media_queries_load[styles][<?php echo $data['row']['obj']->handle; ?>][value]"><?php echo esc_textarea($matchMediaLoadValue); ?></textarea> &nbsp;<small style="vertical-align: top;">e.g. <em style="vertical-align: top;">screen and (max-width: 767px)</em></small>
                <div class="wpacu-clearfix"></div>
        </div>
        <div class="wpacu-helper-area"><a style="text-decoration: none; color: inherit;" target="_blank" href="https://assetcleanup.com/docs/?p=1023"><span class="dashicons dashicons-editor-help"></span></a></div>
        <div class="wpacu-clearfix"></div>
    <?php
    }
    ?>
    <!-- [/wpacu_pro] -->
<?php
}
