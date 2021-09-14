<?php
/*
 * The file is included from /templates/meta-box-loaded-assets/_asset-script-single-row.php
*/

if (! isset($data)) {
	exit; // no direct access
}

$isGroupUnloaded        = $data['row']['is_group_unloaded'];
// [wpacu_pro]
$isMarkedForRegExUnload = isset($data['handle_unload_regex']['scripts'][ $data['row']['obj']->handle ]['enable']) ? $data['handle_unload_regex']['scripts'][ $data['row']['obj']->handle ]['enable'] : false;
// [/wpacu_pro]
$anyUnloadRuleSet       = ($isGroupUnloaded || $isMarkedForRegExUnload || $data['row']['checked']);
?>
<div class="wpacu_exception_options_area_load_exception <?php if (! $anyUnloadRuleSet) { echo 'wpacu_hide'; } ?>">
    <div data-script-handle="<?php echo $data['row']['obj']->handle; ?>"
         class="wpacu_exception_options_area_wrap">
        <fieldset>
            <legend>Make an exception from any unload rule &amp; <strong>always load it</strong>:</legend>

		<ul class="wpacu_area_two wpacu_asset_options wpacu_exception_options_area">
			<li id="wpacu_load_it_option_script_<?php echo $data['row']['obj']->handle; ?>">
				<label><input data-handle="<?php echo $data['row']['obj']->handle; ?>"
				              id="wpacu_load_it_option_script_<?php echo $data['row']['obj']->handle; ?>"
				              class="wpacu_load_it_option_one wpacu_script wpacu_load_exception"
				              type="checkbox"
						<?php if ($data['row']['is_load_exception_per_page']) { ?> checked="checked" <?php } ?>
						      name="wpacu_scripts_load_it[]"
						      value="<?php echo $data['row']['obj']->handle; ?>" />
                    <span>On this page</span></label>
			</li>

			<?php
			if ($data['bulk_unloaded_type'] === 'post_type') {
				// Only show it on edit post/page/custom post type
				switch ($data['post_type']) {
					case 'product':
						$loadBulkText = __('On all WooCommerce "Product" pages', 'wp-asset-clean-up');
						break;
					case 'download':
						$loadBulkText = __('On all Easy Digital Downloads "Download" pages', 'wp-asset-clean-up');
						break;
					default:
						$loadBulkText = sprintf(__('On All Pages of "<strong>%s</strong>" post type', 'wp-asset-clean-up'), $data['post_type']);
				}
				?>
                <li id="wpacu_load_it_post_type_option_script_<?php echo $data['row']['obj']->handle; ?>">
                    <input type="hidden"
                           name="wpacu_scripts_load_it_post_type[<?php echo $data['post_type']; ?>][<?php echo $data['row']['obj']->handle; ?>]"
                           value="" />
                    <label><input data-handle="<?php echo $data['row']['obj']->handle; ?>"
                                  id="wpacu_script_load_it_post_type_<?php echo $data['row']['obj']->handle; ?>"
                                  class="wpacu_load_it_option_post_type wpacu_script wpacu_load_exception"
                                  type="checkbox"
							<?php if ($data['row']['is_load_exception_post_type']) { ?> checked="checked" <?php } ?>
                                  name="wpacu_scripts_load_it_post_type[<?php echo $data['post_type']; ?>][<?php echo $data['row']['obj']->handle; ?>]"
                                  value="1"/>
                        <span><?php echo $loadBulkText; ?></span></label>
                </li>
				<?php
			}
			?>

			<?php
			// [wpacu_pro]
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
						   value="1" />&nbsp;<span>If the URL (its URI) is matched by a RegEx(es):</span></label> <a style="text-decoration: none; color: inherit; vertical-align: middle;" target="_blank" href="https://assetcleanup.com/docs/?p=21#wpacu-method-2"><span class="dashicons dashicons-editor-help"></span></a>
				<div class="wpacu_load_regex_input_wrap <?php if (! $isLoadRegExEnabledWithValue) { echo 'wpacu_hide'; } ?>">
                    <div class="wpacu_regex_rule_area">
                        <textarea <?php if (! $isLoadRegExEnabledWithValue) { echo 'disabled="disabled"'; } ?>
                            class="wpacu_regex_rule_textarea"
                            data-wpacu-adapt-height="1"
                            name="wpacu_handle_load_regex[scripts][<?php echo $data['row']['obj']->handle; ?>][value]"><?php echo esc_attr($handleLoadRegex['value']); ?></textarea>
                        <p style="margin-top: 0 !important;"><small><span style="font-weight: 500;">Note:</span> Multiple RegEx rules can be added as long as they are one per line.</small></p>
                    </div>
				</div>
			</li>
            <?php
            // [/wpacu_pro]
            $isLoadItLoggedIn = in_array($data['row']['obj']->handle, $data['handle_load_logged_in']['scripts']);
            ?>
            <li id="wpacu_load_it_user_logged_in_option_script_<?php echo $data['row']['obj']->handle; ?>">
                <label><input data-handle="<?php echo $data['row']['obj']->handle; ?>"
                              id="wpacu_load_it_user_logged_in_option_script_<?php echo $data['row']['obj']->handle; ?>"
                              class="wpacu_load_it_option_three wpacu_script wpacu_load_exception"
                              type="checkbox"
						<?php if ($isLoadItLoggedIn) { ?> checked="checked" <?php } ?>
                              name="wpacu_load_it_logged_in[scripts][<?php echo $data['row']['obj']->handle; ?>]"
                              value="1"/>
                    <span>If the user is logged-in</span></label>
            </li>
		</ul>
        <div class="wpacu-clearfix"></div>
        </fieldset>
	</div>
</div>
