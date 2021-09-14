<?php
if (! isset($data)) {
	exit;
}
?>
<div class="wpacu-wrap" id="wpacu-plugins-load-manager-wrap">
    <form>
		<?php
		$pluginsRows = array();

		foreach ($data['active_plugins'] as $pluginData) {
			$pluginPath = $pluginData['path'];
			list($pluginDir) = explode('/', $pluginPath);
			ob_start();
			?>
            <tr>
                <td class="wpacu_plugin_icon" width="40">
					<?php if(isset($data['plugins_icons'][$pluginDir])) { ?>
                        <img width="40" height="40" alt="" src="<?php echo $data['plugins_icons'][$pluginDir]; ?>" />
					<?php } else { ?>
                        <div><span class="dashicons dashicons-admin-plugins"></span></div>
					<?php } ?>
                </td>
                <td class="wpacu_plugin_details">
                    <span class="wpacu_plugin_title"><?php echo $pluginData['title']; ?></span> <span class="wpacu_plugin_path">&nbsp;<small><?php echo $pluginData['path']; ?></small></span>
                    <div class="wpacu-clearfix"></div>

                    <div class="wrap_plugin_unload_rules_options">
                        <!-- [Start] Unload Rules -->
                        <div class="wpacu_plugin_rules_wrap">
                            <ul class="wpacu_plugin_rules">
                                <li>
                                    <label for="wpacu_global_unload_plugin_<?php echo $pluginPath; ?>">
                                        <input data-wpacu-plugin-path="<?php echo $pluginPath; ?>"
                                               disabled="disabled"
                                               class="disabled wpacu_plugin_unload_site_wide wpacu_plugin_unload_rule_input"
                                               id="wpacu_global_unload_plugin_<?php echo $pluginPath; ?>"
                                               type="checkbox"
                                               value="unload_site_wide" />
                                        <a class="go-pro-link-no-style"
                                           href="<?php echo WPACU_PLUGIN_GO_PRO_URL; ?>?utm_source=manage_asset&utm_medium=unload_plugin_site_wide"><span class="wpacu-tooltip" style="width: 200px; margin-left: -146px;">This feature is locked for Pro users<br />Click here to upgrade!</span><img width="20" height="20" src="<?php echo WPACU_PLUGIN_URL; ?>/assets/icons/icon-lock.svg" valign="top" alt="" /></a>&nbsp; Unload in all frontend pages <small>&amp; add exceptions</small></label>
                                </li>
                            </ul>
                        </div>

                        <div class="wpacu_plugin_rules_wrap">
                            <ul class="wpacu_plugin_rules">
                                <li>
                                    <label for="wpacu_unload_it_regex_option_<?php echo $pluginPath; ?>"
                                           style="margin-right: 0;">
                                        <input data-wpacu-plugin-path="<?php echo $pluginPath; ?>"
                                               disabled="disabled"
                                               id="wpacu_unload_it_regex_option_<?php echo $pluginPath; ?>"
                                               class="disabled wpacu_plugin_unload_regex_radio wpacu_plugin_unload_rule_input"
                                               type="checkbox"
                                               value="unload_via_regex">
                                        <a class="go-pro-link-no-style"
                                           href="<?php echo WPACU_PLUGIN_GO_PRO_URL; ?>?utm_source=manage_asset&utm_medium=unload_plugin_via_regex"><span class="wpacu-tooltip" style="width: 200px; margin-left: -146px;">This feature is locked for Pro users<br />Click here to upgrade!</span><img width="20" height="20" src="<?php echo WPACU_PLUGIN_URL; ?>/assets/icons/icon-lock.svg" valign="top" alt="" /></a> &nbsp;<span>Unload it for frontend URLs with request URI matching the RegEx(es):</span></label>
                                    <a class="help_link unload_it_regex"
                                       target="_blank"
                                       href="https://assetcleanup.com/docs/?p=372#wpacu-unload-plugins-via-regex"><span style="color: #74777b;" class="dashicons dashicons-editor-help"></span></a>
                                </li>
                            </ul>
                        </div>

                        <div class="wpacu_plugin_rules_wrap">
                            <ul class="wpacu_plugin_rules">
                                <li>
                                    <label for="wpacu_unload_it_logged_in_plugin_<?php echo $pluginPath; ?>" style="margin-right: 0;">
                                        <input data-wpacu-plugin-path="<?php echo $pluginPath; ?>"
                                               disabled="disabled"
                                               id="wpacu_unload_it_logged_in_plugin_<?php echo $pluginPath; ?>"
                                               class="disabled wpacu_plugin_unload_logged_in"
                                               type="checkbox"
                                               name="wpacu_plugins[<?php echo $pluginPath; ?>][status][]"
                                               value="unload_logged_in" />
                                        <a class="go-pro-link-no-style"
                                           href="<?php echo WPACU_PLUGIN_GO_PRO_URL; ?>?utm_source=manage_asset&utm_medium=unload_plugin_if_logged_in"><span class="wpacu-tooltip" style="width: 200px; margin-left: -146px;">This feature is locked for Pro users<br />Click here to upgrade!</span><img width="20" height="20" src="<?php echo WPACU_PLUGIN_URL; ?>/assets/icons/icon-lock.svg" valign="top" alt="" /></a> &nbsp;<span>Unload it in the frontend if the user is logged in</span>
                                    </label>
                                </li>
                            </ul>
                        </div>

                        <div class="wpacu-clearfix"></div>
                    </div>
                    <!-- [End] Unload Rules -->
                </td>
            </tr>
			<?php
			$trOutput = ob_get_clean();
			$pluginsRows['always_loaded'][] = $trOutput;
		}

		if (isset($pluginsRows['always_loaded']) && ! empty($pluginsRows['always_loaded'])) {
			if (isset($pluginsRows['has_unload_rules']) && count($pluginsRows['has_unload_rules']) > 0) {
				?>
                <div style="margin-top: 35px;"></div>
				<?php
			}

			$totalAlwaysLoadedPlugins = count($pluginsRows['always_loaded']);
			?>

            <h3><span style="color: green;" class="dashicons dashicons-admin-plugins"></span> <span style="color: green;"><?php echo $totalAlwaysLoadedPlugins; ?></span> active plugin<?php echo ($totalAlwaysLoadedPlugins > 1) ? 's' : ''; ?> (loaded by default)</h3>
            <table class="wp-list-table wpacu-list-table widefat plugins striped">
				<?php
				foreach ( $pluginsRows['always_loaded'] as $pluginRowOutput ) {
					echo $pluginRowOutput . "\n";
				}
				?>
            </table>
			<?php
		}
		?>
        <div id="wpacu-update-button-area" style="margin-left: 0;">
            <input class="disabled" disabled="disabled" type="hidden" name="wpacu_plugins_manager_submit" value="1" />
        </div>
    </form>
</div>