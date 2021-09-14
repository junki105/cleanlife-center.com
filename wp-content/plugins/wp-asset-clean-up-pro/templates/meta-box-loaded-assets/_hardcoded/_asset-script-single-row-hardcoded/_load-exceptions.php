<?php
/*
 * The file is included from /templates/meta-box-loaded-assets/_asset-script-single-row.php
*/

if (! isset($data)) {
	exit; // no direct access
}

$isGroupUnloaded        = $data['row']['is_group_unloaded'];
$isMarkedForRegExUnload = isset($data['handle_unload_regex']['scripts'][ $data['row']['obj']->handle ]['enable']) ? $data['handle_unload_regex']['scripts'][ $data['row']['obj']->handle ]['enable'] : false;
$anyUnloadRuleSet       = ($isGroupUnloaded || $isMarkedForRegExUnload || $data['row']['checked']);
?>
<div class="wpacu_asset_options_wrap <?php if (! $anyUnloadRuleSet) { echo 'wpacu_hide'; } ?>">
	<div data-script-handle="<?php echo $data['row']['obj']->handle; ?>"
	     style="margin-bottom: 12px;"
	     class="wpacu_exception_options_area_wrap">
		<div class="wpacu_area_one">
			<?php if ($isGroupUnloaded) { ?>
				<strong>Make an exception</strong> and always:
			<?php } else { ?>
                <strong>Make an exception</strong> if unloaded and always:
			<?php } ?>
		</div>
		<ul class="wpacu_area_two wpacu_asset_options wpacu_exception_options_area">
			<li id="wpacu_load_it_option_script_<?php echo $data['row']['obj']->handle; ?>">
				<label><input data-handle="<?php echo $data['row']['obj']->handle; ?>"
				              id="wpacu_load_it_option_script_<?php echo $data['row']['obj']->handle; ?>"
				              class="wpacu_load_it_option_one wpacu_script wpacu_load_exception"
				              type="checkbox"
						<?php if ($data['row']['is_load_exception_per_page']) { ?> checked="checked" <?php } ?>
						      name="wpacu_scripts_load_it[]"
						      value="<?php echo $data['row']['obj']->handle; ?>" />
					Load it on this page</label>
			</li>
			<?php
			$handleLoadRegex = (isset($data['handle_load_regex']['scripts'][$data['row']['obj']->handle]) && $data['handle_load_regex']['scripts'][$data['row']['obj']->handle])
				? $data['handle_load_regex']['scripts'][$data['row']['obj']->handle]
				: false;

			$handleLoadRegex['enable'] = isset($handleLoadRegex['enable']) && $handleLoadRegex['enable'];
			$handleLoadRegex['value']  = (isset($handleLoadRegex['value']) && $handleLoadRegex['value']) ? $handleLoadRegex['value'] : '';

			$isLoadRegExEnabledWithValue = $handleLoadRegex['enable'] && $handleLoadRegex['value'];
			?>
			<li>
				<label for="wpacu_load_it_regex_option_script_<?php echo $data['row']['obj']->handle; ?>">
					<input data-handle="<?php echo $data['row']['obj']->handle; ?>"
					       id="wpacu_load_it_regex_option_script_<?php echo $data['row']['obj']->handle; ?>"
					       class="wpacu_load_it_option_two wpacu_script wpacu_load_exception"
					       type="checkbox"
					       name="wpacu_handle_load_regex[scripts][<?php echo $data['row']['obj']->handle; ?>][enable]"
						<?php if ($isLoadRegExEnabledWithValue) { ?> checked="checked" <?php } ?>
						   value="1" />&nbsp;<span>Load it if the URL (its URI) is matched by a RegEx(es):</span></label> <a style="text-decoration: none; color: inherit; vertical-align: middle;" target="_blank" href="https://assetcleanup.com/docs/?p=21#wpacu-method-2"><span class="dashicons dashicons-editor-help"></span></a>
				<div class="wpacu_load_regex_input_wrap <?php if (! $isLoadRegExEnabledWithValue) { echo 'wpacu_hide'; } ?>">
                    <div class="wpacu_regex_rule_area">
                        <textarea <?php if (! $isLoadRegExEnabledWithValue) { echo 'disabled="disabled"'; } ?>
                            class="wpacu_regex_rule_textarea"
                            name="wpacu_handle_load_regex[scripts][<?php echo $data['row']['obj']->handle; ?>][value]"><?php echo esc_attr($handleLoadRegex['value']); ?></textarea>
                    </div>
				</div>
			</li>
            <?php
            $isLoadItLoggedIn = isset($data['handle_load_logged_in']['scripts']) &&
                                is_array($data['handle_load_logged_in']['scripts']) &&
                                in_array($data['row']['obj']->handle, $data['handle_load_logged_in']['scripts']);
            ?>
            <!-- For the $referenceKey -->
            <input type="hidden" name="wpacu_preloads[scripts][<?php echo $data['row']['obj']->handle; ?>]" value="" />
            <!-- /For the $referenceKey -->

            <li id="wpacu_load_it_user_logged_in_option_script_<?php echo $data['row']['obj']->handle; ?>">
                <label><input data-handle="<?php echo $data['row']['obj']->handle; ?>"
                              id="wpacu_load_it_user_logged_in_option_script_<?php echo $data['row']['obj']->handle; ?>"
                              class="wpacu_load_it_option_three wpacu_script wpacu_load_exception"
                              type="checkbox"
                            <?php if ($isLoadItLoggedIn) { ?> checked="checked" <?php } ?>
                              name="wpacu_load_it_logged_in[scripts][<?php echo $data['row']['obj']->handle; ?>]"
                              value="1"/>
                    Load it if the user is logged-in</label>
            </li>
		</ul>
        <div class="wpacu-clearfix"></div>
	</div>
</div>
