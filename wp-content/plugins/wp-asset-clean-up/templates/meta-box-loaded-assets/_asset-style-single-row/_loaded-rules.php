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

$showMatchMediaFeature = false;

// Is "independent" or has "parents" (is "child") with nothing under it (no "children")
if (in_array('is_independent', $handleAllStatuses) || (in_array('is_child', $handleAllStatuses) && (! in_array('is_parent', $handleAllStatuses)))) {
	$showMatchMediaFeature = true;
}

if ( $showMatchMediaFeature && ! $linkHasDistinctiveMediaAttr ) {
?>
    <!-- [wpacu_lite] -->
    <?php
    if (isset($data['row']['obj']->src) && $data['row']['obj']->src !== '') {
    ?>
        <div style="margin: 0 0 15px;">
            <?php
            $wpacuDataForId = 'wpacu_handle_media_query_load_style_'.$data['row']['obj']->handle;
            ?>
            If kept loaded, make the browser download the file&nbsp;
            <select data-handle="<?php echo $data['row']['obj']->handle; ?>"
                    name="wpacu_media_queries_load[styles][<?php echo $data['row']['obj']->handle; ?>][enable]"
                    class="wpacu-screen-size-load wpacu-for-style">
                <option selected="selected" value="">on any screen size (default)</option>
                <option disabled="disabled" value="1">if the media query is matched (Pro)</option>
            </select>
            <div style="display: inline-block; vertical-align: middle; margin-left: -2px;"><a class="wpacu-media-query-load-requires-pro-popup" href="<?php echo WPACU_PLUGIN_GO_PRO_URL; ?>?utm_source=manage_asset&utm_medium=media_query_load_css"><img width="20" height="20" src="<?php echo WPACU_PLUGIN_URL; ?>/assets/icons/icon-lock.svg" valign="top" alt="" /></a></div>
            <div class="wpacu-helper-area" style="vertical-align: middle; margin-left: 6px;"><a style="text-decoration: none; color: inherit;" target="_blank" href="https://assetcleanup.com/docs/?p=1023"><span class="dashicons dashicons-editor-help"></span></a></div>
            <input type="hidden" name="wpacu_media_queries_load[styles][<?php echo $data['row']['obj']->handle; ?>][value]" value="" />
        </div>
    <?php
    }
    ?>
    <div class="wpacu-clearfix"></div>
    <!-- [/wpacu_lite] -->
<?php
}
