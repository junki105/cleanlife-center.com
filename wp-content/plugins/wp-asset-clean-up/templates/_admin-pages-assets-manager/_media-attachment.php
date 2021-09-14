<?php
/*
 * No direct access to this file
 */
if (! isset($data)) {
	exit;
}
?>
<div style="margin: 18px 0 0;" class="clearfix"></div>
<?php
if ( ! \WpAssetCleanUp\Main::instance()->currentUserCanViewAssetsList() ) {
	?>
    <div class="error" style="padding: 10px;">
		<?php echo sprintf(__('Only the administrators listed here can manage CSS/JS assets: %s"Settings" &#10141; "Plugin Usage Preferences" &#10141; "Allow managing assets to:"%s. If you believe you should have access to managing CSS/JS assets, you can add yourself to that list.', 'wp-asset-clean-up'), '<a target="_blank" href="'.admin_url('admin.php?page=wpassetcleanup_settings&wpacu_selected_tab_area=wpacu-setting-plugin-usage-settings').'">', '</a>'); ?></div>
	<?php
	return;
}

$data['post_id'] = (isset($_GET['wpacu_post_id']) && $_GET['wpacu_post_id']) ? $_GET['wpacu_post_id'] : false;
?>
<p>Post Type: 'attachment' (e.g. files from <a target="_blank" href="https://wordpress.org/support/article/media-library-screen/">"Media" &#187; "Library"</a>, the page loaded usually prints the image or other media type) &#10230; <a target="_blank" href="https://wordpress.org/support/article/edit-media/"><?php _e('read more', 'wp-asset-clean-up'); ?></a></p>
<?php
$data['dashboard_edit_not_allowed'] = false;

require_once __DIR__.'/common/_is-dashboard-edit-allowed.php';

if ($data['dashboard_edit_not_allowed']) {
	return; // stop here as the message about the restricted access has been printed
}
?>
<div style="margin: 25px 0 0;">
    <?php
    if (\WpAssetCleanUp\MetaBoxes::isMediaWithPermalinkDeactivated()) {
        ?>
            <div class="wpacu-notice-info" style="width: 95%; margin-bottom: 20px;">
                    <span class="dashicons dashicons-info"></span>
                    <?php
                    echo __('There are no CSS/JS to manage because <em>"Redirect attachment URLs to the attachment itself?"</em> is set to <em>"Yes"</em> in <em>"Search Appearance - Yoast SEO" - "Media"</em> tab).', 'wp-asset-clean-up');
                    ?>
            </div>
        <?php
    } else {
	    $data['dashboard_edit_not_allowed'] = false;

	    require_once __DIR__.'/common/_is-dashboard-edit-allowed.php';

	    if ($data['dashboard_edit_not_allowed']) {
		    return; // stop here as the message about the restricted access has been printed
	    }

	    if ($data['post_id']) {
		    // There's a POST ID requested in the URL / Show the assets
		    $data['post_type'] = 'attachment';
		    do_action('wpacu_admin_notices');
		    require_once __DIR__.'/_singular-page.php';
	    } else {
		    // There's no POST ID requested
		    $data['post_type'] = 'attachment';
		    require_once '_singular-page-search-form.php';
	    }

        }
    ?>
</div>