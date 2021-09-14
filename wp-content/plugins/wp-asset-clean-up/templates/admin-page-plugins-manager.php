<?php
/*
 * No direct access to this file
 */
if (! isset($data)) {
	exit;
}

$wpacuSubPage = (array_key_exists('wpacu_sub_page', $_GET) && $_GET['wpacu_sub_page']) ? $_GET['wpacu_sub_page'] : 'manage_plugins_front';

include_once '_top-area.php';

do_action('wpacu_admin_notices');

if ( ! \WpAssetCleanUp\Main::instance()->currentUserCanViewAssetsList() ) {
	?>
    <div class="error" style="padding: 10px;">
		<?php echo sprintf(__('Only the administrators listed here can manage plugins: %s"Settings" &#10141; "Plugin Usage Preferences" &#10141; "Allow managing assets to:"%s. If you believe you should have access to managing plugins, you can add yourself to that list.', 'wp-asset-clean-up'), '<a target="_blank" href="'.admin_url('admin.php?page=wpassetcleanup_settings&wpacu_selected_tab_area=wpacu-setting-plugin-usage-settings').'">', '</a>'); ?></div>
	<?php
	return;
}
?>
    <div class="wpacu-sub-page-tabs-wrap"> <!-- Sub-tabs wrap -->
        <!-- Sub-nav menu -->
        <label class="wpacu-sub-page-nav-label <?php if ($wpacuSubPage === 'manage_plugins_front') { ?>wpacu-selected<?php } ?>"><a href="<?php echo admin_url('admin.php?page=wpassetcleanup_plugins_manager&wpacu_sub_page=manage_plugins_front'); ?>"><span class="dashicons dashicons-admin-home"></span> IN FRONTEND VIEW (your visitors)</a></label>
        <label class="wpacu-sub-page-nav-label <?php if ($wpacuSubPage === 'manage_plugins_dash') { ?>wpacu-selected<?php } ?>"><a href="<?php echo admin_url('admin.php?page=wpassetcleanup_plugins_manager&wpacu_sub_page=manage_plugins_dash'); ?>"><span class="dashicons dashicons-dashboard"></span> IN THE DASHBOARD /wp-admin/</a></label>
        <!-- /Sub-nav menu -->
    </div> <!-- /Sub-tabs wrap -->

<?php if ($wpacuSubPage === 'manage_plugins_front') { ?>
    <div style="border-radius: 5px; line-height: 20px; background: white; padding: 8px; margin-bottom: 16px; width: 95%; border-left: 2px solid #004567; border-top: 1px solid #e7e7e7; border-right: 1px solid #e7e7e7; border-bottom: 1px solid #e7e7e7;">
        <p style="margin-top: 0;"><strong>Remember:</strong> Please be careful when using this feature as it would not only unload all the CSS/JS that is loading from a plugin, but everything else (e.g. its backend PHP code, HTML output printed via <code>wp_head()</code> or <code>wp_footer()</code> action hooks, any cookies that are set, .etc). It would be like the plugin is deactivated for the pages where it's chosen to be unloaded. Consider enabling "Test Mode" in plugin's "Settings" if you're unsure about anything. All the rules set below are applied in the front-end view only. They are not taking effect within the Dashboard (the function <code style="font-size: inherit;">is_admin()</code> is used to verify that) to make sure nothing will get broken while you're configuring any plugins' settings. <a style="text-decoration: none; color: #004567;" target="_blank" href="https://www.assetcleanup.com/docs/?p=372"><span class="dashicons dashicons-info"></span>&nbsp;Read more</a></p>
        <p style="margin-bottom: 0;">If you wish to completely stop using a plugin in both admin/frontend pages, the most effective way would be to deactivate it from the "Plugins" -&gt; "Installed Plugins" area.</p>
    </div>
	<?php
	include_once __DIR__.'/_admin-page-plugins-manager/_front.php';
} elseif ($wpacuSubPage === 'manage_plugins_dash') {
    ?>
    <div style="border-radius: 5px; line-height: 20px; background: white; padding: 8px; margin-bottom: 16px; width: 95%; border-left: 2px solid #004567; border-top: 1px solid #e7e7e7; border-right: 1px solid #e7e7e7; border-bottom: 1px solid #e7e7e7;">
        <p style="margin-top: 0;"><strong>Remember:</strong> Using this feature is only recommended for advanced users (e.g. developers/admins that know very well their website and the consequences of having plugins unloaded for certain pages) &amp; who really need it. A set rule would not only unload all the CSS/JS that is loading from a plugin, but everything else (e.g. its backend PHP code, HTML output printed via <code>admin_head()</code> or <code>admin_footer()</code> action hooks, any cookies that are set, .etc).</p>
        <p style="margin-top: 0;">Reasons for using this feature include: some admin pages are very slow, you want to avoid a conflict between two plugins, etc. It would be like the plugin is deactivated within the Dashboard for the pages where it's chosen to be unloaded. The function <code style="font-size: inherit;">is_admin()</code> is used to perform the verification to determine if the user is inside a Dashboard page. If you make a mistake and set a rule that doesn't allow you to access a page anymore, you can cancel it by appending the following query string to the URL: <code>&amp;wpacu_no_dash_plugin_unload</code>, thus allowing you to change/remove the rule in this management page. <a style="text-decoration: none; color: #004567;" target="_blank" href="https://www.assetcleanup.com/docs/?p=1128"><span class="dashicons dashicons-info"></span>&nbsp;Read more</a></p>
        <p style="margin-bottom: 0;">If you wish to completely stop using a plugin in both admin/frontend pages, the most effective way would be to deactivate it from the "Plugins" -&gt; "Installed Plugins" area.</p>
    </div>
	<?php
	include_once __DIR__.'/_admin-page-plugins-manager/_dash.php';
}