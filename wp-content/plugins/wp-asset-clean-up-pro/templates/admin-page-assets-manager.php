<?php
/*
 * No direct access to this file
 */

use WpAssetCleanUp\Misc;

if (! isset($data)) {
	exit;
}

$wpacuSubPage = (array_key_exists('wpacu_sub_page', $_GET) && $_GET['wpacu_sub_page']) ? $_GET['wpacu_sub_page'] : 'manage_css_js';
$criticalCssIsGlobalDisabled = $data['wpacu_settings']['critical_css_status'] === 'off';

include_once '_top-area.php';
?>
<div class="wpacu-wrap" style="margin: -12px 0 0;">
    <div class="wpacu-sub-page-tabs-wrap"> <!-- Sub-tabs wrap -->
        <!-- Sub-nav menu -->
        <label class="wpacu-sub-page-nav-label <?php if ($wpacuSubPage === 'manage_css_js') { ?>wpacu-selected<?php } ?>"><a href="<?php echo admin_url('admin.php?page=wpassetcleanup_assets_manager&wpacu_sub_page=manage_css_js'); ?>">MANAGE CSS/JS</a></label>
        <?php
        // [CRITICAL CSS]
        ?>
            <label class="wpacu-sub-page-nav-label <?php if ($wpacuSubPage === 'manage_critical_css') { ?>wpacu-selected<?php } ?>"><a href="<?php echo admin_url('admin.php?page=wpassetcleanup_assets_manager&wpacu_sub_page=manage_critical_css'); ?>">MANAGE CRITICAL CSS</a></label>
        <?php
        // [/CRITICAL CSS]
        ?>
        <!-- /Sub-nav menu -->
    </div> <!-- /Sub-tabs wrap -->

    <?php
    if ($wpacuSubPage === 'manage_css_js') {
    ?>
        <nav class="nav-tab-wrapper nav-assets-manager">
            <a href="<?php echo admin_url('admin.php?page=wpassetcleanup_assets_manager&wpacu_for=homepage'); ?>" class="nav-tab <?php if ($data['for'] === 'homepage') { ?>nav-tab-active<?php } ?>"><?php _e('Homepage', 'wp-asset-clean-up'); ?></a>
            <a href="<?php echo admin_url('admin.php?page=wpassetcleanup_assets_manager&wpacu_for=posts'); ?>" class="nav-tab <?php if ($data['for'] === 'posts') { ?>nav-tab-active<?php } ?>"><?php _e('Posts', 'wp-asset-clean-up'); ?></a>
            <a href="<?php echo admin_url('admin.php?page=wpassetcleanup_assets_manager&wpacu_for=pages'); ?>" class="nav-tab <?php if ($data['for'] === 'pages') { ?>nav-tab-active<?php } ?>"><?php _e('Pages', 'wp-asset-clean-up'); ?></a>
            <a href="<?php echo admin_url('admin.php?page=wpassetcleanup_assets_manager&wpacu_for=custom-post-types'); ?>" class="nav-tab <?php if ($data['for'] === 'custom-post-types') { ?>nav-tab-active<?php } ?>"><?php _e('Custom Post Types', 'wp-asset-clean-up'); ?></a>
            <a href="<?php echo admin_url('admin.php?page=wpassetcleanup_assets_manager&wpacu_for=media-attachment'); ?>" class="nav-tab <?php if ($data['for'] === 'media-attachment') { ?>nav-tab-active<?php } ?>"><?php _e('Media', 'wp-asset-clean-up'); ?></a>
            <a href="<?php echo admin_url('admin.php?page=wpassetcleanup_assets_manager&wpacu_for=category'); ?>" class="nav-tab <?php if ($data['for'] === 'category') { ?>nav-tab-active<?php } ?>"><?php _e('Category', 'wp-asset-clean-up'); ?></a>
            <a href="<?php echo admin_url('admin.php?page=wpassetcleanup_assets_manager&wpacu_for=tag'); ?>" class="nav-tab <?php if ($data['for'] === 'tag') { ?>nav-tab-active<?php } ?>"><?php _e('Tag', 'wp-asset-clean-up'); ?></a>
            <a href="<?php echo admin_url('admin.php?page=wpassetcleanup_assets_manager&wpacu_for=custom-taxonomy'); ?>" class="nav-tab <?php if ($data['for'] === 'custom-taxonomy') { ?>nav-tab-active<?php } ?>"><?php _e('Custom Taxonomy', 'wp-asset-clean-up'); ?></a>
            <a href="<?php echo admin_url('admin.php?page=wpassetcleanup_assets_manager&wpacu_for=search'); ?>" class="nav-tab <?php if ($data['for'] === 'search') { ?>nav-tab-active<?php } ?>"><?php _e('Search', 'wp-asset-clean-up'); ?></a>
            <a href="<?php echo admin_url('admin.php?page=wpassetcleanup_assets_manager&wpacu_for=author'); ?>" class="nav-tab <?php if ($data['for'] === 'author') { ?>nav-tab-active<?php } ?>"><?php _e('Author', 'wp-asset-clean-up'); ?></a>
            <a href="<?php echo admin_url('admin.php?page=wpassetcleanup_assets_manager&wpacu_for=date'); ?>" class="nav-tab <?php if ($data['for'] === 'date') { ?>nav-tab-active<?php } ?>"><?php _e('Date', 'wp-asset-clean-up'); ?></a>
            <a href="<?php echo admin_url('admin.php?page=wpassetcleanup_assets_manager&wpacu_for=404-not-found'); ?>" class="nav-tab <?php if ($data['for'] === '404-not-found') { ?>nav-tab-active<?php } ?>"><?php _e('404 Not Found', 'wp-asset-clean-up'); ?></a>
            <?php ?>
        </nav>
        <div class="wpacu-clearfix"></div>
        <?php
        if ($data['for'] === 'homepage') {
            include_once '_admin-pages-assets-manager/_homepage.php';
        } elseif ($data['for'] === 'posts') {
            include_once '_admin-pages-assets-manager/_posts.php';
        } elseif ($data['for'] === 'custom-post-types') {
            include_once '_admin-pages-assets-manager/_custom-post-types.php';
        } elseif ($data['for'] === 'pages') {
            include_once '_admin-pages-assets-manager/_pages.php';
        } elseif ($data['for'] === 'media-attachment') {
            include_once '_admin-pages-assets-manager/_media-attachment.php';
        } elseif ($data['for'] === 'category') {
            include_once '_admin-pages-assets-manager/_category.php';
        } elseif ($data['for'] === 'tag') {
            include_once '_admin-pages-assets-manager/_tag.php';
        } elseif ($data['for'] === 'custom-taxonomy') {
            include_once '_admin-pages-assets-manager/_custom-taxonomy.php';
        } elseif ($data['for'] === 'search') {
            include_once '_admin-pages-assets-manager/_search.php';
        } elseif ($data['for'] === 'author') {
            include_once '_admin-pages-assets-manager/_author.php';
        } elseif ($data['for'] === 'date') {
            include_once '_admin-pages-assets-manager/_date.php';
        } elseif ($data['for'] === '404-not-found') {
            include_once '_admin-pages-assets-manager/_404-not-found.php';
        }
        } elseif ($wpacuSubPage === 'manage_critical_css') {
        // [CRITICAL CSS]
        $allEnabledLocations = array();

        $criticalCssConfigJson = get_option(WPACU_PLUGIN_ID . '_critical_css_config');
        $criticalCssConfig = json_decode($criticalCssConfigJson, true);

        if (Misc::jsonLastError() !== JSON_ERROR_NONE) {
            $criticalCssConfig = array(); // JSON has to be valid
        }

        $allEnabledLocations = (! empty($criticalCssConfig) ) ? \WpAssetCleanUpPro\OptimiseAssets\CriticalCssPro::getAllEnabledLocations($criticalCssConfig) : array();

        if ($criticalCssIsGlobalDisabled) {
            echo '<p style="color: #cc0000"><span class="dashicons dashicons-warning"></span> Critical CSS is globally deactivated from <a style="text-decoration: underline; color: inherit;" target="_blank" href="'.admin_url('admin.php?page=wpassetcleanup_settings&wpacu_selected_tab_area=wpacu-setting-optimize-css#wpacu-critical-css-status').'"><strong>"Settings" -&gt; "Optimize CSS" &gt; "Critical CSS Status"</strong></a>, thus any change done below would be saved, but not take effect in the front-end view, unless you re-activate it.</p>';
        }
        ?>

        <?php
        if (in_array($data['for'], array('posts', 'pages', 'custom-post-types'))) {
        ?>
            <div style="background: white; border: 1px solid #cdcdcd; padding: 10px; margin: 0 0 10px;"><p style="margin: 0;"><strong>Note:</strong> The changes below apply for page groups such as posts (the blog articles), pages (e.g. About, Contact), etc. For most websites, this works fine as the layout's styling (especially the above the fold where the critical CSS applies) is the same. However, there are sometimes exceptions (e.g. a landing page that has been customised differently) and for this, you can use the "Custom Pages" tab.</p></div>
        <?php
        } else {
        ?>
            <div style="padding: 5px; margin: 0;">
                <ul style="display: inline-block; margin: 0;">
                    <li style="float: left; margin-right: 10px;"><a target="_blank" style="text-decoration: none;" href="https://www.assetcleanup.com/docs/?p=608"><span class="dashicons dashicons-editor-help"></span> What's critical CSS &amp; and how to implement it?</a></li>
                </ul>
            </div>
        <?php
        }
        ?>

        <nav id="wpacu-critical-css-manager-tab-menu" class="nav-tab-wrapper nav-critical-css-manager">
            <a href="<?php echo admin_url('admin.php?page=wpassetcleanup_assets_manager&wpacu_sub_page=manage_critical_css&wpacu_for=homepage'); ?>" class="nav-tab <?php if ($data['for'] === 'homepage') { ?>nav-tab-active<?php } ?>"><?php _e('Homepage', 'wp-asset-clean-up'); ?><span class="wpacu-circle-status <?php if (in_array('homepage', $allEnabledLocations)) { echo 'wpacu-on'; } else { echo 'wpacu-off'; } ?>"></span></a>
            <a href="<?php echo admin_url('admin.php?page=wpassetcleanup_assets_manager&wpacu_sub_page=manage_critical_css&wpacu_for=posts'); ?>" class="nav-tab <?php if ($data['for'] === 'posts') { ?>nav-tab-active<?php } ?>"><?php _e('Posts', 'wp-asset-clean-up'); ?><span class="wpacu-circle-status <?php if (in_array('posts', $allEnabledLocations)) { echo 'wpacu-on'; } else { echo 'wpacu-off'; } ?>"></span></a>
            <a href="<?php echo admin_url('admin.php?page=wpassetcleanup_assets_manager&wpacu_sub_page=manage_critical_css&wpacu_for=pages'); ?>" class="nav-tab <?php if ($data['for'] === 'pages') { ?>nav-tab-active<?php } ?>"><?php _e('Pages', 'wp-asset-clean-up'); ?><span class="wpacu-circle-status <?php if (in_array('pages', $allEnabledLocations)) { echo 'wpacu-on'; } else { echo 'wpacu-off'; } ?>"></span></a>
            <a href="<?php echo admin_url('admin.php?page=wpassetcleanup_assets_manager&wpacu_sub_page=manage_critical_css&wpacu_for=custom-post-types'); ?>" class="nav-tab <?php if ($data['for'] === 'custom-post-types') { ?>nav-tab-active<?php } ?>"><?php _e('Custom Post Types', 'wp-asset-clean-up'); ?><span class="wpacu-circle-status <?php if (\WpAssetCleanUpPro\OptimiseAssets\CriticalCssPro::isEnabledForAtLeastOnePageType($criticalCssConfig, 'custom_post_type')) { echo 'wpacu-on'; } else { echo 'wpacu-off'; } ?>"></span></a>
            <a href="<?php echo admin_url('admin.php?page=wpassetcleanup_assets_manager&wpacu_sub_page=manage_critical_css&wpacu_for=media-attachment'); ?>" class="nav-tab <?php if ($data['for'] === 'media-attachment') { ?>nav-tab-active<?php } ?>"><?php _e('Media', 'wp-asset-clean-up'); ?><span class="wpacu-circle-status <?php if (in_array('media', $allEnabledLocations)) { echo 'wpacu-on'; } else { echo 'wpacu-off'; } ?>"></span></a>
            <a href="<?php echo admin_url('admin.php?page=wpassetcleanup_assets_manager&wpacu_sub_page=manage_critical_css&wpacu_for=category'); ?>" class="nav-tab <?php if ($data['for'] === 'category') { ?>nav-tab-active<?php } ?>"><?php _e('Category', 'wp-asset-clean-up'); ?><span class="wpacu-circle-status <?php if (in_array('category', $allEnabledLocations)) { echo 'wpacu-on'; } else { echo 'wpacu-off'; } ?>"></span></a>
            <a href="<?php echo admin_url('admin.php?page=wpassetcleanup_assets_manager&wpacu_sub_page=manage_critical_css&wpacu_for=tag'); ?>" class="nav-tab <?php if ($data['for'] === 'tag') { ?>nav-tab-active<?php } ?>"><?php _e('Tag', 'wp-asset-clean-up'); ?><span class="wpacu-circle-status <?php if (in_array('tag', $allEnabledLocations)) { echo 'wpacu-on'; } else { echo 'wpacu-off'; } ?>"></span></a>
            <a href="<?php echo admin_url('admin.php?page=wpassetcleanup_assets_manager&wpacu_sub_page=manage_critical_css&wpacu_for=custom-taxonomy'); ?>" class="nav-tab <?php if ($data['for'] === 'custom-taxonomy') { ?>nav-tab-active<?php } ?>"><?php _e('Custom Taxonomy', 'wp-asset-clean-up'); ?><span class="wpacu-circle-status <?php if (\WpAssetCleanUpPro\OptimiseAssets\CriticalCssPro::isEnabledForAtLeastOnePageType($criticalCssConfig, 'custom_taxonomy')) { echo 'wpacu-on'; } else { echo 'wpacu-off'; } ?>"></span></a>
            <a href="<?php echo admin_url('admin.php?page=wpassetcleanup_assets_manager&wpacu_sub_page=manage_critical_css&wpacu_for=search'); ?>" class="nav-tab <?php if ($data['for'] === 'search') { ?>nav-tab-active<?php } ?>"><?php _e('Search', 'wp-asset-clean-up'); ?><span class="wpacu-circle-status <?php if (in_array('search', $allEnabledLocations)) { echo 'wpacu-on'; } else { echo 'wpacu-off'; } ?>"></span></a>
            <a href="<?php echo admin_url('admin.php?page=wpassetcleanup_assets_manager&wpacu_sub_page=manage_critical_css&wpacu_for=author'); ?>" class="nav-tab <?php if ($data['for'] === 'author') { ?>nav-tab-active<?php } ?>"><?php _e('Author', 'wp-asset-clean-up'); ?><span class="wpacu-circle-status <?php if (in_array('author', $allEnabledLocations)) { echo 'wpacu-on'; } else { echo 'wpacu-off'; } ?>"></span></a>
            <a href="<?php echo admin_url('admin.php?page=wpassetcleanup_assets_manager&wpacu_sub_page=manage_critical_css&wpacu_for=date'); ?>" class="nav-tab <?php if ($data['for'] === 'date') { ?>nav-tab-active<?php } ?>"><?php _e('Date', 'wp-asset-clean-up'); ?><span class="wpacu-circle-status <?php if (in_array('date', $allEnabledLocations)) { echo 'wpacu-on'; } else { echo 'wpacu-off'; } ?>"></span></a>
            <a href="<?php echo admin_url('admin.php?page=wpassetcleanup_assets_manager&wpacu_sub_page=manage_critical_css&wpacu_for=404-not-found'); ?>" class="nav-tab <?php if ($data['for'] === '404-not-found') { ?>nav-tab-active<?php } ?>"><?php _e('404 Not Found', 'wp-asset-clean-up'); ?><span class="wpacu-circle-status <?php if (in_array('404_not_found', $allEnabledLocations)) { echo 'wpacu-on'; } else { echo 'wpacu-off'; } ?>"></span></a>
            <a style="padding: 6px 10px;" href="<?php echo admin_url('admin.php?page=wpassetcleanup_assets_manager&wpacu_sub_page=manage_critical_css&wpacu_for=custom-pages'); ?>" class="nav-tab <?php if ($data['for'] === 'custom-pages') { ?>nav-tab-active<?php } ?>"><?php _e('Custom Pages', 'wp-asset-clean-up'); ?></a>
        </nav>
        <div class="wpacu-clearfix"></div>
        <?php
        if ( ! \WpAssetCleanUp\Main::instance()->currentUserCanViewAssetsList() ) {
        ?>
            <div class="error" style="padding: 10px;">
                <?php echo sprintf(__('Only the administrators listed here can manage the critical CSS: %s"Settings" &#10141; "Plugin Usage Preferences" &#10141; "Allow managing assets to:"%s. If you believe you should have access to this page, you can add yourself to that list.', 'wp-asset-clean-up'), '<a target="_blank" href="'.admin_url('admin.php?page=wpassetcleanup_settings&wpacu_selected_tab_area=wpacu-setting-plugin-usage-settings').'">', '</a>'); ?>
            </div>
        <?php
        } else {
            ?>
            <form id="wpacu-critical-css-form" method="post" action="">
                <?php
                // Show notices when the critical CSS is updated (e.g. updated/disabled, new CSS syntax)
                do_action('wpacu_admin_notices');
                ?>
                <div style="margin: 30px 0 0;" class="wpacu-clearfix"></div>

                <?php
                if ( $data['for'] === 'homepage' ) {
                    include_once '_admin-pages-critical-css/_homepage.php';
                } elseif ( $data['for'] === 'posts' ) {
                    include_once '_admin-pages-critical-css/_posts.php';
                } elseif ( $data['for'] === 'custom-post-types' ) {
                    include_once '_admin-pages-critical-css/_custom-post-types.php';
                } elseif ( $data['for'] === 'pages' ) {
                    include_once '_admin-pages-critical-css/_pages.php';
                } elseif ( $data['for'] === 'media-attachment' ) {
                    include_once '_admin-pages-critical-css/_media.php';
                } elseif ( $data['for'] === 'category' ) {
                    include_once '_admin-pages-critical-css/_category.php';
                } elseif ( $data['for'] === 'tag' ) {
                    include_once '_admin-pages-critical-css/_tag.php';
                } elseif ( $data['for'] === 'custom-taxonomy' ) {
                    include_once '_admin-pages-critical-css/_custom-taxonomy.php';
                } elseif ( $data['for'] === 'search' ) {
                    include_once '_admin-pages-critical-css/_search.php';
                } elseif ( $data['for'] === 'author' ) {
                    include_once '_admin-pages-critical-css/_author.php';
                } elseif ( $data['for'] === 'date' ) {
                    include_once '_admin-pages-critical-css/_date.php';
                } elseif ( $data['for'] === '404-not-found' ) {
                    include_once '_admin-pages-critical-css/_404-not-found.php';
                } elseif ( $data['for'] === 'custom-pages' ) {
                    // Nothing to update here; Just information about how to implement "wpacu_critical_css" hook for custom pages
                    include_once '_admin-pages-critical-css/_custom-pages.php';
                }

                if ($data['for'] !== 'custom-pages') {
                ?>
                    <div id="wpacu-update-critical-css-button-area">
                        <input type="submit" name="submit" class="button button-primary" value="UPDATE" />
                        <div id="wpacu-updating-critical-css" class="wpacu-hide">
                            <img src="<?php echo admin_url('images/spinner.gif'); ?>" align="top" width="20" height="20" alt="" />
                        </div>
                        <?php wp_nonce_field('wpacu_critical_css_update', 'wpacu_critical_css_nonce'); ?>
                        <input type="hidden" name="wpacu_critical_css_submit" value="1" />
                    </div>
                <?php
                }
        }
        // [/CRITICAL CSS]
    }
    ?>
</div>