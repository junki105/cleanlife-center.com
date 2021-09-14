<?php
/*
 * The file is included from /templates/meta-box-loaded-assets/_asset-script-single-row.php
*/

if (! isset($data)) {
	exit; // no direct access
}
?>
<!-- [wpacu_pro] -->
<?php if (isset($data['row']['obj']->src) && $data['row']['obj']->src !== '') {
	$isAsyncGlobal = (in_array($data['row']['obj']->handle, $data['scripts_attributes']['everywhere']['async']));
	$isDeferGlobal = (in_array($data['row']['obj']->handle, $data['scripts_attributes']['everywhere']['defer']));
	?>
	<div class="wpacu-script-attributes-area wpacu-pro wpacu-only-when-kept-loaded">
		<div <?php if ($isAsyncGlobal || $isDeferGlobal) { echo 'style="display: block; width: 100%;"'; } ?>>If kept loaded, set the following attributes:</div>
		<ul class="wpacu-script-attributes-settings wpacu-first">
			<li><strong><u>async</u></strong> &#10230;</li>
			<li><label for="async_on_this_page_<?php echo $data['row']['obj']->handle; ?>"><input
						<?php if ( $isAsyncGlobal ) { ?>disabled="disabled"<?php } ?>
						id="async_on_this_page_<?php echo $data['row']['obj']->handle; ?>"
						class="wpacu_script_attr_rule_input"
						type="checkbox"
						name="wpacu_async[<?php echo $data['row']['obj']->handle; ?>]" <?php if ( in_array( $data['row']['obj']->handle,
						$data['scripts_attributes']['on_this_page']['async'] ) ) {
						echo 'checked="checked"';
					} ?> value="on_this_page"/>on this page <?php if ( $isAsyncGlobal ) { ?><br/><small>*
						locked by site-wide rule</small><?php } ?></label></li>
			<li>
				<?php if ($isAsyncGlobal) { ?>
					<div><strong>Set everywhere</strong> <small>* site-wide</small></div>
					<div>
						<label><input data-handle="<?php echo $data['row']['obj']->handle; ?>"
						              type="radio"
						              name="wpacu_options_global_attribute_scripts[async][<?php echo $data['row']['obj']->handle; ?>]"
						              checked="checked"
						              value="default"/>
							Keep rule</label>

						&nbsp;&nbsp;&nbsp;&nbsp;

						<label><input data-handle="<?php echo $data['row']['obj']->handle; ?>"
						              type="radio"
						              name="wpacu_options_global_attribute_scripts[async][<?php echo $data['row']['obj']->handle; ?>]"
						              value="remove"/>
							Remove rule</label>
					</div>
				<?php } else { ?>
					<label for="async_everywhere_<?php echo $data['row']['obj']->handle; ?>"><input
							id="async_everywhere_<?php echo $data['row']['obj']->handle; ?>"
							class="wpacu_script_attr_rule_input wpacu_script_attr_rule_global"
							type="checkbox"
							name="wpacu_async[<?php echo $data['row']['obj']->handle; ?>]"
							value="everywhere"/>everywhere</label>
				<?php } ?>
			</li>
			<li class="wpacu-script-attr-make-exception <?php if (! $isAsyncGlobal) { ?>wpacu_hide<?php } ?>">
				<label for="async_none_<?php echo $data['row']['obj']->handle; ?>">
					<input id="async_none_<?php echo $data['row']['obj']->handle; ?>"
					       type="checkbox"
					       name="wpacu_async[no_load][]"
						<?php if (in_array($data['row']['obj']->handle, $data['scripts_attributes']['not_on_this_page']['async'])) { ?>
							checked="checked"
						<?php } ?>
						   value="<?php echo $data['row']['obj']->handle; ?>" />not here (exception)
				</label>
			</li>
		</ul>
		<ul class="wpacu-script-attributes-settings">
			<li><strong><u>defer</u></strong> &#10230;</li>
			<li><label for="defer_on_this_page_<?php echo $data['row']['obj']->handle; ?>"><input
						<?php if ( $isDeferGlobal ) { ?>disabled="disabled"<?php } ?>
						id="defer_on_this_page_<?php echo $data['row']['obj']->handle; ?>"
						class="wpacu_script_attr_rule_input"
						type="checkbox"
						name="wpacu_defer[<?php echo $data['row']['obj']->handle; ?>]" <?php if ( in_array( $data['row']['obj']->handle,
						$data['scripts_attributes']['on_this_page']['defer'] ) ) {
						echo 'checked="checked"';
					} ?> value="on_this_page"/>on this page <?php if ( $isDeferGlobal ) { ?><br/><small>*
						locked by site-wide rule</small><?php } ?></label></li>
			<li>
				<?php if ($isDeferGlobal) { ?>
					<div><strong>Set everywhere</strong> <small>* site-wide</small></div>
					<div>
						<label><input data-handle="<?php echo $data['row']['obj']->handle; ?>"
						              type="radio"
						              name="wpacu_options_global_attribute_scripts[defer][<?php echo $data['row']['obj']->handle; ?>]"
						              checked="checked"
						              value="default"/>
							Keep rule</label>

						&nbsp;&nbsp;&nbsp;&nbsp;

						<label><input data-handle="<?php echo $data['row']['obj']->handle; ?>"
						              type="radio"
						              name="wpacu_options_global_attribute_scripts[defer][<?php echo $data['row']['obj']->handle; ?>]"
						              value="remove"/>
							Remove rule</label>
					</div>
				<?php } else { ?>
					<label for="defer_everywhere_<?php echo $data['row']['obj']->handle; ?>"><input
							id="defer_everywhere_<?php echo $data['row']['obj']->handle; ?>"
							class="wpacu_script_attr_rule_input wpacu_script_attr_rule_global"
							type="checkbox"
							name="wpacu_defer[<?php echo $data['row']['obj']->handle; ?>]"
							value="everywhere"/>everywhere</label>
				<?php } ?>
			</li>
			<li class="wpacu-script-attr-make-exception <?php if (! $isDeferGlobal) { ?>wpacu_hide<?php } ?>">
				<label for="defer_none_<?php echo $data['row']['obj']->handle; ?>">
					<input id="defer_none_<?php echo $data['row']['obj']->handle; ?>"
					       type="checkbox"
					       name="wpacu_defer[no_load][]"
						<?php if (in_array($data['row']['obj']->handle, $data['scripts_attributes']['not_on_this_page']['defer'])) { ?>
							checked="checked"
						<?php } ?>
						   value="<?php echo $data['row']['obj']->handle; ?>" />not here (exception)
				</label>
			</li>
		</ul>
		<div class="wpacu-clearfix"></div>
	</div>

    <?php
    $childHandles = isset($data['all_deps']['parent_to_child']['scripts'][$data['row']['obj']->handle]) ? $data['all_deps']['parent_to_child']['scripts'][$data['row']['obj']->handle] : array();

    $handleAllStatuses = array();

    if (! empty($childHandles)) {
	    $handleAllStatuses[] = 'is_parent';
    }

    if (isset($data['row']['obj']->deps) && ! empty($data['row']['obj']->deps)) {
	    $handleAllStatuses[] = 'is_child';
    }

    if (empty($handleAllStatuses)) {
	    $handleAllStatuses[] = 'is_independent';
    }

    $showMatchMediaFeature = false;

    // Is "independent" or has "parents" (is "child") with nothing under it (no "children")
    if (in_array('is_independent', $handleAllStatuses) || (in_array('is_child', $handleAllStatuses) && (! in_array('is_parent', $handleAllStatuses)))) {
	    $showMatchMediaFeature = true;
    }

    // "extra" is fine, "after" and "before" are more tricky to accept (at least at this time)
    $wpacuHasExtraInline = ($data['row']['extra_before_js'] || $data['row']['extra_after_js']);

    if ($showMatchMediaFeature && ! $wpacuHasExtraInline) {
    ?>
    <div class="wpacu-only-when-kept-loaded">
        <div style="margin: 0 0 15px;">
            <?php
            $matchMediaLoadArray = (isset($data['media_queries_load']['scripts'][$data['row']['obj']->handle]) && $data['media_queries_load']['scripts'][$data['row']['obj']->handle])
                ? $data['media_queries_load']['scripts'][$data['row']['obj']->handle]
                : array();

            $matchMediaLoadEnable = (isset($matchMediaLoadArray['enable']) && $matchMediaLoadArray['enable']);
            $matchMediaLoadValue  = (isset($matchMediaLoadArray['value'])  && $matchMediaLoadArray['value']) ? $matchMediaLoadArray['value'] : '';

            $wpacuDataForId = 'wpacu_handle_media_query_load_script_'.$data['row']['obj']->handle;
            ?>

            If kept loaded, make the browser download the file&nbsp;
                <select data-handle="<?php echo $data['row']['obj']->handle; ?>"
                        name="wpacu_media_queries_load[scripts][<?php echo $data['row']['obj']->handle; ?>][enable]"
                        class="wpacu-screen-size-load wpacu-for-script">
                    <option <?php if (! $matchMediaLoadEnable) { echo 'selected="selected"'; } ?> value="">on any screen size (default)</option>
                    <option <?php if ($matchMediaLoadEnable) { echo 'selected'; } ?> value="1">if the media query is matched</option>
                </select>

            <input type="hidden" name="wpacu_media_queries_load[scripts][<?php echo $data['row']['obj']->handle; ?>][value]" value="" />

            <div data-script-handle="<?php echo $data['row']['obj']->handle; ?>"
                 class="wpacu-handle-media-queries-load-field <?php if ($matchMediaLoadEnable) { echo 'wpacu-is-visible'; } ?> wpacu-fade-in">
                    <textarea id="<?php echo $wpacuDataForId; ?>"
                      style="min-height: 40px;"
                      class="wpacu-handle-media-queries-load-field-input"
                      data-wpacu-adapt-height="1"
                      data-wpacu-is-empty-on-page-load="<?php echo (! $matchMediaLoadValue) ? 'true' : 'false'; ?>"
                      <?php if (! $matchMediaLoadValue) { echo 'disabled="disabled"'; } ?>
                      name="wpacu_media_queries_load[scripts][<?php echo $data['row']['obj']->handle; ?>][value]"><?php echo esc_textarea($matchMediaLoadValue); ?></textarea> &nbsp;<small style="vertical-align: top;">e.g. <em style="vertical-align: top;">screen and (max-width: 767px)</em></small>
                <div class="wpacu-clearfix"></div>
        </div>
        <div class="wpacu-helper-area"><a style="text-decoration: none; color: inherit;" target="_blank" href="http://assetcleanup.com/docs/?p=1023"><span class="dashicons dashicons-editor-help"></span></a></div>
    </div>
    <?php
    }
    ?>
	<div class="wpacu-clearfix"></div>
<?php } ?>
<!-- [/wpacu_pro] -->