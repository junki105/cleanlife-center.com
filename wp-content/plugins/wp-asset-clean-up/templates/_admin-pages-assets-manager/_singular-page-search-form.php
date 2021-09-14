<?php
/*
 * No direct access to this file
 */
if (! isset($data)) {
	exit;
}
?>
<div style="margin: 0 0 15px;">
	<?php
		$searchPlaceholderText = sprintf(__('You can type a keyword or the ID to search the %s for which you want to manage its CSS/JS (e.g. unloading)', 'wp-asset-clean-up'), $data['post_type']);
		?>

	<?php
	// Anything that is not within the array, is a custom post type
	if (isset($_GET['wpacu_for']) && $_GET['wpacu_for'] === 'custom-post-types') {
		$postTypes = get_post_types( array( 'public' => true ) );
		$postTypesList = \WpAssetCleanUp\Misc::filterCustomPostTypesList($postTypes);
		?>
            After you choose the custom post type, you can then search within all the posts that are within your choice:
        <select id="wpacu-custom-post-type-choice">
			<?php foreach ($postTypesList as $listPostType => $listPostTypeLabel) { ?>
                <option <?php if ($data['post_type'] === $listPostType) { echo 'selected="selected"'; } ?> value="<?php echo $listPostType; ?>"><?php echo $listPostTypeLabel; ?></option>
			<?php } ?>
        </select>
        <div style="margin: 0 0 15px;"></div>
		<?php
	}
	?>

		<form id="wpacu-search-form-assets-manager">
			Load assets manager for:
            <input type="text"
			       class="search-field"
			       value=""
			       placeholder="<?php echo $searchPlaceholderText ?>"
			       style="max-width: 800px; width: 100%; padding-right: 15px;" />
            * <small>Once you choose the post, a new page will load with the CSS &amp; JS manager</small>
            <div style="display: none; padding: 10px; color: #cc0000;" id="wpacu-search-form-assets-manager-no-results"><span class="dashicons dashicons-warning"></span> <?php echo __('There are no results based on your search', 'wp-asset-clean-up'); ?>. <?php echo sprintf(__('Remember that you can also use the %s ID in the input', 'wp-asset-clean-up'), $data['post_type']); ?>.</div>
		</form>

        <div style="display: none;" id="wpacu-post-chosen-loading-assets">
            <img style="margin: 2px 0 4px;"
                 src="<?php echo WPACU_PLUGIN_URL; ?>/assets/icons/loader-horizontal.svg?x=<?php echo time(); ?>"
                 align="top"
                 width="120"
                 alt="" />
        </div>
</div>