<?php
/*
 * The file is included from /templates/meta-box-loaded-assets/_asset-style-single-row.php
*/

if ( ! isset($data, $stylePosition, $stylePositionNew, $styleHandleHasSrc) ) {
	exit; // no direct access
}

ob_start();

if ($styleHandleHasSrc) {
	?>
	<div class="wpacu-wrap-choose-position">
		<?php echo __('Location:', 'wp-asset-clean-up'); ?>
		<select name="wpacu_styles_positions[{handle}]" id="wpacu_styles_positions{handle}_select">
			<option <?php if ($stylePositionNew === 'head') {
				echo 'selected="selected"';
			} ?>
				value="<?php if ($stylePosition === 'head') {
					echo 'initial';
				} else {
					echo 'head';
				} ?>">
				&lt;HEAD&gt; <?php if ($stylePosition === 'head') { ?>* initial<?php } ?>
			</option>
			<option <?php if ($stylePositionNew === 'body') {
				echo 'selected="selected"';
			} ?>
				value="<?php if ($stylePosition === 'body') {
					echo 'initial';
				} else {
					echo 'body';
				} ?>">
				&lt;BODY&gt; <?php if ($stylePosition === 'body') { ?>* initial<?php } ?>
			</option>
		</select>
		<small>* applies site-wide</small>
	</div>
	<?php
} else {
	if ($data['row']['obj']->handle === 'woocommerce-inline') {
		$noSrcLoadedIn = __('Inline CSS Loaded In:', 'wp-asset-clean-up');
	} else {
		$noSrcLoadedIn = __('This handle is not for external stylesheet (most likely inline CSS) and it is loaded in:', 'wp-asset-clean-up');
	}

	echo $noSrcLoadedIn . ' '. (($stylePosition === 'head') ? 'HEAD' : 'BODY');
}

$htmlChoosePosition = str_replace('{handle}', $data['row']['obj']->handle, ob_get_clean());

if (isset($data['row']['obj']->position) && $data['row']['obj']->position !== '') {
	$extraInfo[] = $htmlChoosePosition;
}
