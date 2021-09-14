<?php
/*
 * No direct access to this file
 */
if (! isset($data)) {
	exit;
}
?>
<div style="margin: 25px 0 0;">
	<?php
	$anyMetaBoxHidden = $data['wpacu_settings']['hide_assets_meta_box'] || $data['wpacu_settings']['hide_options_meta_box'];

	if ($anyMetaBoxHidden) {
		?>
        <div class="wpacu-warning" style="width: 95%; margin: -10px 0 15px; padding: 10px; font-size: inherit;">
            <strong><span class="dashicons dashicons-warning" style="color: orange;"></span> Important Reminder:</strong> The following meta boxes were marked to be hidden in plugin's "Settings" &#187; "Plugin Usage Preferences":
            <ul style="margin-bottom: 0; list-style: circle; padding-left: 25px;">
				<?php if ($data['wpacu_settings']['hide_assets_meta_box']) { ?>
                    <li><strong><?php echo WPACU_PLUGIN_TITLE; ?>: CSS &amp; JavaScript Manager</strong> * <em>to see the CSS/JS list for any <strong>Post</strong>, you need to make the meta box visible again</em></li>
				<?php } ?>

				<?php if ($data['wpacu_settings']['hide_options_meta_box']) { ?>
                    <li><strong><?php echo WPACU_PLUGIN_TITLE; ?>: Options</strong> * <em>to prevent minify/combine/unload settings per page, you need to make the meta box visible again</em></li>
				<?php } ?>
            </ul>
        </div>
		<?php
	}

	$data['post_id'] = (isset($_GET['wpacu_post_id']) && $_GET['wpacu_post_id']) ? $_GET['wpacu_post_id'] : false;
    ?>
        <p style="margin-bottom: 0;">Post Type: 'post' (e.g. blog entries) &#10230; <a target="_blank" href="https://wordpress.org/support/article/writing-posts/"><?php _e('read more', 'wp-asset-clean-up'); ?></a></p>
        <div style="margin: 15px 0 0;" class="clearfix"></div>
    <?php
    $data['dashboard_edit_not_allowed'] = false;

    require_once __DIR__.'/common/_is-dashboard-edit-allowed.php';

    if ($data['dashboard_edit_not_allowed']) {
        return; // stop here as the message about the restricted access has been printed
    }

	if ($data['post_id']) {
	    // There's a POST ID requested in the URL / Show the assets
	    $data['post_type'] = get_post_type($data['post_id']);
		do_action('wpacu_admin_notices');
	    require_once __DIR__.'/_singular-page.php';
    } else {
		// There's no POST ID requested
	    $data['post_type'] = 'post';
		require_once '_singular-page-search-form.php';
	}
	?>
</div>