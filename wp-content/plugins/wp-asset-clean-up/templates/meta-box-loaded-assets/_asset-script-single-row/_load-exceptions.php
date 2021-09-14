<?php
/*
 * The file is included from /templates/meta-box-loaded-assets/_asset-script-single-row.php
*/

if (! isset($data)) {
	exit; // no direct access
}

$isGroupUnloaded  = $data['row']['is_group_unloaded'];
$anyUnloadRuleSet = ($isGroupUnloaded || $data['row']['checked']);
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

			<li>
				<label for="wpacu_load_it_regex_option_script_<?php echo $data['row']['obj']->handle; ?>"><input
						data-handle="<?php echo $data['row']['obj']->handle; ?>"
						id="wpacu_load_it_regex_option_script_<?php echo $data['row']['obj']->handle; ?>"
						class="wpacu_load_it_option_two wpacu_script wpacu_load_exception wpacu_lite_locked"
						type="checkbox"
						disabled="disabled"
						value="1"/>
					Load it for URLs with request URI matching this RegEx(es): <a class="go-pro-link-no-style"
					                                                          href="<?php echo WPACU_PLUGIN_GO_PRO_URL; ?>?utm_source=manage_asset&utm_medium=load_via_regex_make_exception"><span
							class="wpacu-tooltip wpacu-larger"><?php _e( 'This feature is available in the premium version of the plugin.',
								'wp-asset-clean-up' ); ?><br/> <?php _e( 'Click here to upgrade to Pro',
								'wp-asset-clean-up' ); ?>!</span><img width="20" height="20"
					                                                  src="<?php echo WPACU_PLUGIN_URL; ?>/assets/icons/icon-lock.svg"
					                                                  valign="top" alt=""/></a> <a
						style="text-decoration: none; color: inherit;" target="_blank"
						href="https://assetcleanup.com/docs/?p=21#wpacu-method-2"><span
							class="dashicons dashicons-editor-help"></span></a></label>
			</li>
            <?php
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
