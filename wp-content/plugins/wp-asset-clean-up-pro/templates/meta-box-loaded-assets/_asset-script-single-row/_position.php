<?php
/*
 * The file is included from /templates/meta-box-loaded-assets/_asset-script-single-row.php
*/

if ( ! isset($data, $scriptPosition, $scriptPositionNew) ) {
	exit; // no direct access
}

ob_start();
?>
	<div class="wpacu-wrap-choose-position">
		<?php echo __('Location:', 'wp-asset-clean-up'); ?>
		<select name="wpacu_scripts_positions[{handle}]" id="wpacu_scripts_positions_{handle}_select">
			<option <?php if ($scriptPositionNew === 'head') { echo 'selected="selected"'; } ?>
				value="<?php if ($scriptPosition === 'head') { echo 'initial'; } else { echo 'head'; } ?>">
				&lt;HEAD&gt; <?php if ($scriptPosition === 'head') { ?>* initial<?php } ?>
			</option>
			<option <?php if ($scriptPositionNew === 'body') { echo 'selected="selected"'; } ?>
				value="<?php if ($scriptPosition === 'body') { echo 'initial'; } else { echo 'body'; } ?>">
				&lt;BODY&gt; <?php if ($scriptPosition === 'body') { ?>* initial<?php } ?>
			</option>
		</select>
		<small>* applies site-wide</small>
	</div>
<?php
$htmlChoosePosition = str_replace('{handle}', $data['row']['obj']->handle, ob_get_clean());

if (isset($data['row']['obj']->position) && $data['row']['obj']->position !== '') {
	$extraInfo[] = $htmlChoosePosition;
}
