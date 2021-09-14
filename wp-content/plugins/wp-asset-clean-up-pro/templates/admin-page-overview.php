<?php
/*
 * No direct access to this file
 */
if (! isset($data)) {
	exit;
}

include_once '_top-area.php';

if (! defined('WPACU_USE_MODAL_BOX')) {
	define('WPACU_USE_MODAL_BOX', true);
}
?>
<div class="wrap wpacu-overview-wrap">
    <div style="padding: 0 0 10px; line-height: 22px;"><strong>Note:</strong> This overview contains all the changes of any kind (unload rules, load exceptions, preloads, notes, async/defer SCRIPT attributes, changed positions, etc.) made via Asset CleanUp to any of the loaded (enqueued) CSS/JS files as well as the plugins (e.g. unloaded on certain pages). To make any changes to the values below, please use the "CSS &amp; JS Manager", "Plugins Manager" or "Bulk Changes" tabs.</div>
    <hr />

    <div style="padding: 0 10px 0 0;">
        <h3><span class="dashicons dashicons-admin-appearance"></span> <?php _e('Stylesheets (.css)', 'wp-asset-clean-up'); ?>
        <?php
        if (isset($data['handles']['styles']) && count($data['handles']['styles']) > 0) {
            echo ' &#10230; Total: '.count($data['handles']['styles']);
        }
        ?></h3>
        <?php
        if (isset($data['handles']['styles']) && ! empty($data['handles']['styles'])) {
            ?>
            <table class="wp-list-table wpacu-overview-list-table widefat fixed striped">
                <thead>
                    <tr class="wpacu-top">
                        <td><strong>Handle</strong></td>
                        <td><strong>Unload &amp; Load Exception Rules</strong></td>
                    </tr>
                </thead>
                <?php
                foreach ($data['handles']['styles'] as $handle => $handleData) {
                    ?>
                    <tr id="wpacu-overview-css-<?php echo $handle; ?>" class="wpacu_global_rule_row wpacu_bulk_change_row">
                        <td>
                            <?php \WpAssetCleanUp\Overview::renderHandleTd($handle, 'styles', $data); ?>
                        </td>
                        <td>
                            <?php
                            $handleData['handle'] = $handle;
                            $handleData['asset_type'] = 'styles';
                            $handleChangesOutput = \WpAssetCleanUp\Overview::renderHandleChangesOutput($handleData);

                            if (! empty($handleChangesOutput)) {
	                            echo '<ul style="margin: 0;">' . "\n";

	                            foreach ( $handleChangesOutput as $handleChangesOutputPart ) {
		                            echo '<li>' . $handleChangesOutputPart . '</li>' . "\n";
	                            }

	                            echo '</ul>';
                            } else {
                                echo '<em style="color: #6d6d6d;">'.__('No unload/load exception rules of any kind are set for this stylesheet file', 'wp-asset-clean-up').'</em>.';
                            }
                            ?>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </table>
            <?php
        } else {
            ?>
            <p><?php _e('There is no data added to (e.g. unload, load exceptions, notes, changing of location, preloading, etc.) to any stylesheet.', 'wp-asset-clean-up'); ?></p>
            <?php
        }

        // [CRITICAL CSS]
        ?>
            <hr style="margin: 15px 0;"/>
            <h3><span class="dashicons dashicons-admin-appearance"></span> <?php _e('Critical CSS', 'wp-asset-clean-up'); ?></h3>

            <div style="padding: 10px; background: white; border: 1px solid #ccd0d4; box-shadow: 0 1px 1px rgba(0,0,0,.04);">
            <?php

            if ($data['critical_css_disabled']) {
                echo '<p style="margin-top: 0;">This feature is globally disabled based on the option set in <a style="text-decoration: underline; color: #cc0000;" target="_blank" href="'.admin_url('admin.php?page=wpassetcleanup_settings&wpacu_selected_tab_area=wpacu-setting-optimize-css#wpacu-critical-css-status').'"><strong>"Settings" -&gt; "Optimize CSS" -&gt; "Critical CSS Status"</strong></a>, thus any of the critical CSS content set within <a target="_blank" href="'.admin_url('admin.php?page=wpassetcleanup_assets_manager&wpacu_sub_page=manage_critical_css').'"><strong>"CSS &amp; JS Manager" -&gt; "Manage Critical CSS"</strong></a> is not taking effect in the front-end view.</p>';
            }

            $pageTypeText = '';

            if (! empty($data['critical_css_config'])) {
                $atLeastOneSet = false;
                $pageGroups = $customPostTypes = $customTaxonomies = array();

                foreach ($data['critical_css_config'] as $pageType => $pageTypeValues) {
                    $pageType = trim($pageType);

                    if (isset($pageTypeValues['enable']) && $pageTypeValues['enable']) {
                        if (in_array($pageType, array('homepage', 'posts', 'pages', '404_not_found', 'date', 'author', 'search', 'tag', 'category', 'media'))) {
                            $pageGroups[] = $pageType;
                            $atLeastOneSet = true;
                        } elseif (strpos($pageType, 'custom_post_type_') === 0) {
                            $customPostTypes[] = str_replace('custom_post_type_', '', $pageType);
                            $atLeastOneSet = true;
                        } elseif (strpos($pageType, 'custom_taxonomy_') === 0) {
                            $customTaxonomies[] = str_replace('custom_taxonomy_', '', $pageType);
                            $atLeastOneSet = true;
                        }
                    }
                }

                if ($atLeastOneSet) {
                    $pageTypeText .= 'There is critical CSS content applied for the following <strong>page types / groups</strong>: ';
                    if ( ! empty( $pageGroups ) ) {
                        $pageTypeText .= implode( ', ', array_map(function($value) {
                            $value = str_replace('404_not_found', '404 Not Found', $value);
                            return ucfirst($value);
                        }, $pageGroups) );
                    }

                    if ( ! empty( $customPostTypes ) ) {
                        $pageTypeText .= ' / <strong>Custom Post Types:</strong> '.implode(', ', $customPostTypes);
                    }

                    if ( ! empty( $customTaxonomies ) ) {
                        $pageTypeText .= ' / <strong>Custom Taxonomies:</strong> '.implode(', ', $customTaxonomies);
                    }
                }
            }

            if (! $data['critical_css_disabled']) {
                $pageTypeText .= ' / <a target="_blank" href="'.admin_url('admin.php?page=wpassetcleanup_assets_manager&wpacu_sub_page=manage_critical_css').'">Manage it</a>';
            }

            $opacityLevel = $data['critical_css_disabled'] ? '0.5' : 1;
            echo '<p style="opacity: '.$opacityLevel.'; margin-top: 0; margin-bottom: 0;">'.trim($pageTypeText, ' / ').'</p>';

        // [/CRITICAL CSS]
        ?>
        </div>
        <hr style="margin: 15px 0;"/>

            <h3><span class="dashicons dashicons-media-code"></span> <?php _e('Scripts (.js)', 'wp-asset-clean-up'); ?>
	        <?php
	        if (isset($data['handles']['scripts']) && count($data['handles']['scripts']) > 0) {
		        echo ' &#10230; Total: '.count($data['handles']['scripts']);
	        }
	        ?></h3>
	    <?php
	    if (isset($data['handles']['scripts']) && ! empty($data['handles']['scripts'])) {
		    ?>
            <table class="wp-list-table wpacu-overview-list-table widefat fixed striped">
                <thead>
                    <tr class="wpacu-top">
                        <td><strong>Handle</strong></td>
                        <td><strong>Unload &amp; Load Exception Rules</strong></td>
                    </tr>
                </thead>
			    <?php
			    foreach ($data['handles']['scripts'] as $handle => $handleData) {
				    ?>
                    <tr id="wpacu-overview-js-<?php echo $handle; ?>" class="wpacu_global_rule_row wpacu_bulk_change_row">
                        <td>
						    <?php \WpAssetCleanUp\Overview::renderHandleTd($handle, 'scripts', $data); ?>
                        </td>
                        <td>
	                        <?php
                            $handleData['handle'] = $handle;
	                        $handleData['asset_type'] = 'scripts';
	                        $handleChangesOutput = \WpAssetCleanUp\Overview::renderHandleChangesOutput($handleData);

	                        if (! empty($handleChangesOutput)) {
		                        echo '<ul style="margin: 0;">' . "\n";

		                        foreach ( $handleChangesOutput as $handleChangesOutputPart ) {
			                        echo '<li>' . $handleChangesOutputPart . '</li>' . "\n";
		                        }

		                        echo '</ul>';
	                        } else {
		                        echo '<em style="color: #6d6d6d;">'.__('No unload/load exception rules of any kind are set for this JavaScript file', 'wp-asset-clean-up').'</em>.';
	                        }
	                        ?>
                        </td>
                    </tr>
				    <?php
			    }
			    ?>
            </table>
		    <?php
	    } else {
		    ?>
            <p><?php _e('There is no data added to (e.g. unload, load exceptions, notes, async/defer attributes, changing of location, preloading, etc.) to any SCRIPT tag.', 'wp-asset-clean-up'); ?></p>
		    <?php
	    }
	    ?>
        <!-- [wpacu_pro] -->
        <hr style="margin: 15px 0;"/>

        <div id="wpacu-plugins-load-manager-wrap">
            <?php
            foreach ($data['plugins_with_rules'] as $locationKey => $pluginsWithRules) {
                if ( ! empty($pluginsWithRules) ) {
                ?>
                    <h3><span class="dashicons dashicons-admin-plugins"></span> <?php _e('Plugins with unload rules', 'wp-asset-clean-up'); ?>
                        <?php
                        if ($locationKey === 'plugins') {
	                        $pageTypeText = 'frontend';
                            echo ' (in frontend view)';
                        } elseif ($locationKey === 'plugins_dash') {
	                        $pageTypeText = 'admin';
                            echo ' (within the dashboard, where the user is always logged-in)';
                        }

                        if (isset($data['plugins_with_rules'][$locationKey]) && count($data['plugins_with_rules'][$locationKey]) > 0) {
                            echo ' &#10230; Total: '.count($data['plugins_with_rules'][$locationKey]);
                        }
                        ?>
                    </h3>

                    <table class="wp-list-table wpacu-list-table widefat plugins striped" style="width: 100%;">
                        <?php
                        foreach ($pluginsWithRules as $pluginValues) {
                            $pluginTitle = $pluginValues['title'];
                            $pluginPath  = $pluginValues['path'];
                            $pluginRules = $pluginValues['rules'];

                            if (! is_array($pluginRules['status'])) {
                                $pluginRules['status'] = array($pluginRules['status']); // from v1.1.8.3
                            }

                            list($pluginDir) = explode('/', $pluginPath);

                            $isPluginActive = in_array($pluginPath, $data['plugins_active']);
                            ?>
                            <tr <?php if ( ! $isPluginActive) { echo 'style="opacity: 0.6;"'; } ?>>
                                <td class="wpacu_plugin_details">
                                    <div class="wpacu_plugin_icon" style="float: left;">
                                        <?php if(isset($data['plugins_icons'][$pluginDir])) { ?>
                                            <img width="40" height="40" alt="" src="<?php echo $data['plugins_icons'][$pluginDir]; ?>" />
                                        <?php } else { ?>
                                            <div><span class="dashicons dashicons-admin-plugins"></span></div>
                                        <?php } ?>
                                    </div>

                                    <div style="float: left; margin-left: 8px;">
                                        <div><span class="wpacu_plugin_title"><?php echo $pluginTitle; ?></span></div>
                                        <div><span class="wpacu_plugin_path"><small><?php echo $pluginPath; ?></small></span></div>

                                        <?php
                                        if ( ! in_array($pluginPath, $data['plugins_active']) ) {
                                            ?>
                                            <div><small><strong>Note:</strong> <span style="color: darkred;">The plugin is inactive, thus any of the rules set are also inactive &amp; irrelevant. They would be removed whenever the form from "Plugins Manager" is submitted.</span></small></div>
                                            <?php
                                        }
                                        ?>
                                    </div>

                                    <div class="wpacu-clearfix"></div>
                                </td>
                                <td class="wpacu_plugin_rules" style="padding-left: 10px;">
                                    <?php
                                    $unloadSiteWide = in_array('unload_site_wide', $pluginRules['status']);
                                    $unloadedViaRegEx = in_array('unload_via_regex', $pluginRules['status']) &&
                                                        isset($pluginRules['unload_via_regex']['value']) &&
                                                        $pluginRules['unload_via_regex']['value'];

                                    if ($unloadSiteWide) {
                                        echo '<span style="color: #cc0000;">Unloaded in all '.$pageTypeText.' pages</span>';
                                    } elseif ($unloadedViaRegEx) {
                                        echo '<span style="color: #cc0000;">Unloaded in all '.$pageTypeText.' pages with the URIs (from the URL) matching this RegEx(es):</span> <code>'.nl2br($pluginRules['unload_via_regex']['value']).'</code>';
                                    }

                                    if (isset($pluginRules['load_via_regex']['enable'], $pluginRules['load_via_regex']['value'])) {
                                        echo ' / <span style="color: green;">Loaded (as an exception)</span> for all '.$pageTypeText.' URIs (from the URL) matching this RegEx(es): <code>'.nl2br($pluginRules['load_via_regex']['value']).'</code>';
                                    }

                                    if (isset($pluginRules['load_logged_in']['enable'], $pluginRules['load_logged_in']['enable'])) {
                                        echo ' / <span style="color: green;">Loaded (as an exception)</span> if the user is logged in';
                                    }
                                    ?>
                                    <div class="wpacu-clearfix"></div>
                                </td>
                            </tr>
                        <?php } ?>
                    </table>
                <?php
                } else {
                    ?>
                    <p><?php _e('There are no rules added to any of the active plugins.', 'wp-asset-clean-up'); ?></p>
                    <?php
                }
            }
            ?>
        </div>
        <!-- [/wpacu_pro] -->
    </div>
</div>