<?php
/*
 * The file is included from /templates/meta-box-loaded-assets/_asset-script-single-row.php
*/

if (! isset($data)) {
	exit; // no direct access
}

// Only show it if "Unload site-wide" is NOT enabled
// Otherwise, there's no point to use an unload regex if the asset is unloaded site-wide
if (! $data['row']['global_unloaded']) {
	$handleUnloadRegex = ( isset( $data['handle_unload_regex']['scripts'][ $data['row']['obj']->handle ] ) && $data['handle_unload_regex']['scripts'][ $data['row']['obj']->handle ] )
		? $data['handle_unload_regex']['scripts'][ $data['row']['obj']->handle ]
		: false;

	$handleUnloadRegex['enable'] = isset( $handleUnloadRegex['enable'] ) && $handleUnloadRegex['enable'];
	$handleUnloadRegex['value']  = ( isset( $handleUnloadRegex['value'] ) && $handleUnloadRegex['value'] ) ? $handleUnloadRegex['value'] : '';

	$isUnloadRegExEnabledWithValue = $handleUnloadRegex['enable'] && $handleUnloadRegex['value'];
	?>
	<div data-script-handle="<?php echo $data['row']['obj']->handle; ?>" class="wpacu_asset_options_wrap wpacu_unload_regex_area_wrap">
		<ul class="wpacu_asset_options">
			<li>
				<label for="wpacu_unload_it_regex_option_script_<?php echo $data['row']['obj']->handle; ?>"
					<?php if ( $isUnloadRegExEnabledWithValue ) {
						echo ' class="wpacu_unload_checked"';
					} ?>>
					<input data-handle="<?php echo $data['row']['obj']->handle; ?>"
					       data-handle-for="script"
					       id="wpacu_unload_it_regex_option_script_<?php echo $data['row']['obj']->handle; ?>"
					       class="wpacu_unload_it_regex_checkbox wpacu_unload_rule_input wpacu_bulk_unload"
					       type="checkbox"
					       name="wpacu_handle_unload_regex[scripts][<?php echo $data['row']['obj']->handle; ?>][enable]"
						<?php if ( $handleUnloadRegex['enable'] ) { ?> checked="checked" <?php } ?>
						   value="1"/>&nbsp;<span>Unload it for URLs with request URI matching this RegEx(es):</span></label>
				<a style="text-decoration: none; color: inherit; vertical-align: middle;" target="_blank"
				   href="https://assetcleanup.com/docs/?p=313#wpacu-unload-by-regex"><span
						class="dashicons dashicons-editor-help"></span></a>
				<div class="wpacu_handle_unload_regex_input_wrap <?php if (! $isUnloadRegExEnabledWithValue) { echo 'wpacu_hide'; } ?>">
                    <div class="wpacu_regex_rule_area">
                        <textarea <?php if (! $isUnloadRegExEnabledWithValue) { echo 'disabled="disabled"'; } ?>
                            class="wpacu_regex_rule_textarea"
                            data-wpacu-adapt-height="1"
                            name="wpacu_handle_unload_regex[scripts][<?php echo $data['row']['obj']->handle; ?>][value]"><?php echo esc_attr($handleUnloadRegex['value']); ?></textarea>
                        <p style="margin-top: 0;"><small><span style="font-weight: 500;">Note:</span> Multiple RegEx rules can be added as long as they are one per line.</small></p>
                    </div>
				</div>
			</li>
		</ul>
	</div>
	<?php
}
