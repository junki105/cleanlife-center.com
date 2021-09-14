<?php
/*
 * No direct access to this file
 * This content is placed inside #wpacu_meta_box_content meta box DIV element
 */
if (! isset($data)) {
    exit;
}

global $wp_version;

$data['wp_version'] = $wp_version; // in case there is no version of a CSS/JS WordPress appends its latest version to "ver"

$metaBoxLoadedFine = (! (isset($data['is_dashboard_view']) && $data['is_dashboard_view']
                && isset($data['wp_remote_post']) && !empty($data['wp_remote_post'])));

if (! $metaBoxLoadedFine) {
    // Errors for "WP Remote Post"? Print them out
    ?>
    <div class="ajax-wp-remote-post-call-error-area">
        <p><span class="dashicons dashicons-warning"></span> <?php _e('It looks like "WP Remote Post" method for retrieving assets via the Dashboard is not working in this environment.', 'wp-asset-clean-up'); ?></p>
        <p><?php _e('Since the server (from its IP) is making the call, it will not "behave" in the same way as the "Direct" method, which could bypass for instance any authentication request (you might use a staging website that is protected by login credentials).', 'wp-asset-clean-up'); ?></p>
        <p><?php _e('Consider using "Direct" method. If that doesn\'t work either, use the "Manage in the Front-end" option (which should always work in any instance) and submit a ticket regarding the problem you\'re having. Here\'s the output received by the call:', 'wp-asset-clean-up'); ?></p>
        <table class="table-data">
            <tr>
                <td><strong><?php _e('CODE', 'wp-asset-clean-up'); ?>:</strong></td>
                <td><?php echo $data['wp_remote_post']['response']['code']; ?></td>
            </tr>
            <tr>
                <td><strong><?php _e('MESSAGE', 'wp-asset-clean-up'); ?>:</strong></td>
                <td><?php echo $data['wp_remote_post']['response']['message']; ?></td>
            </tr>
            <tr>
                <td valign="top"><strong><?php _e('OUTPUT', 'wp-asset-clean-up'); ?>:</strong></td>
                <td><?php echo $data['wp_remote_post']['body']; ?></td>
            </tr>
        </table>
    </div>
    <?php
    exit();
}

$tipsClass = new \WpAssetCleanUp\Tips();
$data['tips'] = $tipsClass->list;

if (is_admin()) {
    // Dashboard edit view
    if (get_option('show_on_front') === 'page') {
        if (get_option('page_on_front') == $data['post_id']) {
        ?>
            <p><span style="color: #0f6cab;" class="dashicons dashicons-admin-home"></span> <?php echo sprintf(__('This page was set as the home page in %s"Settings" &#10141; "Reading"%s.', 'wp-asset-clean-up'), '<a target="_blank" href="'.admin_url('options-reading.php').'">', '</a>'); ?></p>
	    <?php
        } elseif (get_option('page_for_posts') == $data['post_id']) {
        ?>
            <p><span style="color: #0f6cab;" class="dashicons dashicons-admin-post"></span> <?php echo sprintf(__('This page was set to show the latest posts in %s"Settings" &#10141; "Reading"%s.', 'wp-asset-clean-up'), '<a target="_blank" href="'.admin_url('options-reading.php').'">', '</a>'); ?></p>
        <?php
        }
    }
} else {
    // Front-end view
    if (\WpAssetCleanUp\Misc::isBlogPage()) {
        ?>
        <p><span style="color: #0f6cab;" class="dashicons dashicons-admin-post"></span> <?php _e('You are currently viewing the page that shows your latest posts.', 'wp-asset-clean-up'); ?></p>
        <?php
    } elseif (\WpAssetCleanUp\Misc::isHomePage()) {
        ?>
        <p><span style="color: #0f6cab;" class="dashicons dashicons-admin-home"></span> <?php _e('You are currently viewing the home page.', 'wp-asset-clean-up'); ?></p>
        <?php
    }
}

if ($data['bulk_unloaded_type'] === 'post_type') {
	$isWooPage = $iconShown = false;

	if (
        (function_exists('is_woocommerce') && is_woocommerce()) ||
        (function_exists('is_cart') && is_cart()) ||
        (function_exists('is_product_tag') && is_product_tag()) ||
        (function_exists('is_product_category') && is_product_category()) ||
        (function_exists('is_checkout') && is_checkout())
    ) {
        $isWooPage = true;
        $iconShown = WPACU_PLUGIN_URL . '/assets/icons/woocommerce-icon-logo.svg';
    }

    if (! $iconShown) {
	    switch ( $data['post_type'] ) {
		    case 'post':
			    $dashIconPart = 'post';
			    break;
		    case 'page':
			    $dashIconPart = 'page';
			    break;
		    case 'attachment':
			    $dashIconPart = 'media';
			    break;
		    default:
			    $dashIconPart = 'post';
	    }
    }
    ?>
    <p>
	<?php if ($isWooPage) { ?>
        <img src="<?php echo $iconShown; ?>" alt="" style="height: 40px !important; margin-top: -6px; margin-right: 5px;" align="middle" /> <strong>WooCommerce</strong>
    <?php } ?>
        <?php if (! $iconShown) { ?><span style="color: #0f6cab;" class="dashicons dashicons-admin-<?php echo $dashIconPart; ?>"></span> <?php } ?> <u><?php echo $data['post_type']; ?></u> <?php if ($data['post_type'] !== 'post') {  echo 'post'; } ?> type.
    </p>
    <?php
}

if (! is_404()) {
    if (isset($data['post_type']) && $data['post_type'] && ! (isset($data['is_for_singular']) && $data['is_for_singular'])) {
	?>
        <div class="wpacu_verified">
            <strong>Page URL:</strong> <a target="_blank" href="<?php echo $data['fetch_url']; ?>"><span><?php echo $data['fetch_url']; ?></span></a>
        </div>
	<?php
    }
}

if (isset($data['page_template'])) {
	?>
    <p>
        <strong><?php if ($data['post_type'] === 'page') { echo 'Page'; } elseif ($data['post_type'] === 'post') { echo 'Post'; } ?>
            Template:</strong>
		<?php
		if (isset($data['all_page_templates'][$data['page_template']])) { ?>
            <u><?php echo $data['all_page_templates'][$data['page_template']]; ?></u>
		<?php } ?>

        (<?php echo $data['page_template'];

		if (isset($data['page_template_path'])) {
			echo '&nbsp; &#10230; &nbsp;<em>'.$data['page_template_path'].'</em>';
		}
		?>)
    </p>
	<?php
}

$viewAssetsMode = 'default'; // All Styles & All Scripts (two lists)

if ($data['plugin_settings']['assets_list_layout'] === 'all') {
	$viewAssetsMode = 'all'; // All Styles & Scripts (one list)
}

if ($data['plugin_settings']['assets_list_layout'] === 'by-location') {
	$viewAssetsMode = 'by-location'; // Plugins, Theme(s), Core Files, External etc.
}

if ($data['plugin_settings']['assets_list_layout'] === 'by-position') {
	$viewAssetsMode = 'by-position'; // <head> or <body> (two lists)
}

if ($data['plugin_settings']['assets_list_layout'] === 'by-preload') {
	$viewAssetsMode = 'by-preload'; // <head> or <body> (two lists)
}

if ($data['plugin_settings']['assets_list_layout'] === 'by-parents') {
    $viewAssetsMode = 'by-parents'; // Loaded & Unloaded (two lists)
}

if ($data['plugin_settings']['assets_list_layout'] === 'by-loaded-unloaded') {
	$viewAssetsMode = 'by-loaded-unloaded'; // Parent or Child/Independent (two lists)
}

if ($data['plugin_settings']['assets_list_layout'] === 'by-size') {
	$viewAssetsMode = 'by-size'; // Local Files (in descending order based on the size) and External Files (or files that can't have the size determined)
}

if ($data['plugin_settings']['assets_list_layout'] === 'by-rules') {
	$viewAssetsMode = 'by-rules'; // Enqueued Files with at least one rule & Enqueued Files with no rules
}

$data['page_unload_text'] = __('Unload on this page', 'wp-asset-clean-up');

if (is_singular()) {
    global $post;
    ?>
    <input type="hidden" name="wpacu_is_singular_page" value="<?php echo $post->ID; ?>" />
    <?php
}

\WpAssetCleanUp\ObjectCache::wpacu_cache_set('wpacu_data_page_unload_text', $data['page_unload_text']);

// Assets List Layout - added here to convenience - to avoid going to "Settings"
// it could make debugging faster
ob_start();
?>
<label for="wpacu_assets_list_layout"><strong>Assets List Layout:</strong></label> <small>* any new change will take effect after you use the "Update" button</small>
<p style="margin: 8px 0;"><?php echo \WpAssetCleanUp\Settings::generateAssetsListLayoutDropDown($data['plugin_settings']['assets_list_layout'], 'wpacu_assets_list_layout'); ?></p>
<?php
$data['assets_list_layout_output'] = ob_get_clean();
?>
<div class="<?php if ($data['plugin_settings']['input_style'] !== 'standard') { ?>wpacu-switch-enhanced<?php } else { ?>wpacu-switch-standard<?php } ?>">
    <?php
    if (isset($data['is_frontend_view']) && $data['is_frontend_view']) {
	    $hardcodedManageAreaHtml = \WpAssetCleanUp\HardcodedAssets::getHardCodedManageAreaForFrontEndView($data);
    }

    include_once __DIR__.'/meta-box-loaded-assets/view-'.$viewAssetsMode.'.php';
    ?>
</div>
<?php
/*
 Bug Fix: Make sure that savePost() from Update class is triggered ONLY if the meta box is loaded
 Otherwise, an early form submit will erase any selected assets for unload by sending an empty $_POST[WPACU_PLUGIN_ID] request

 NOTE: In case no assets are retrieved, then it's likely that for some reason, fetching the assets from the Dashboard
 is not possible and the user will have to manage them in the front-end.
 We'll make sure that no existing assets (managed in the front-end) are removed when the user updates the post/page from the Dashboard
*/

// Check it again
if ($metaBoxLoadedFine) {
	$metaBoxLoadedFine = ( ! ( empty( $data['all']['styles'] ) && empty( $data['all']['scripts'] ) ) );
}

if ($metaBoxLoadedFine) {
    ?>
    <input type="hidden"
           id="wpacu_unload_assets_area_loaded"
           name="wpacu_unload_assets_area_loaded"
           value="1" />
    <?php
}
