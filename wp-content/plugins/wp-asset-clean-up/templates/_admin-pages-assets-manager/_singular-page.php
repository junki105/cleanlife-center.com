<?php
/*
 * No direct access to this file
 */
if (! isset($data)) {
	exit;
}
?>
    <div style="margin: 18px 0 0;" class="clearfix"></div>
<?php
if (isset($data['post_id']) && $data['post_id']) {
	$data['fetch_url'] = \WpAssetCleanUp\Misc::getPageUrl( $data['post_id'] );
}

if (assetCleanUpHasNoLoadMatches($data['fetch_url'])) { // Asset CleanUp Pro is set not to load for the front-page
	?>
	<p class="wpacu_verified">
		<strong>Target URL:</strong> <a target="_blank" href="<?php echo $data['fetch_url']; ?>"><span><?php echo $data['fetch_url']; ?></span></a>
	</p>
	<?php
	$msg = sprintf(__('This page\'s URI is matched by one of the RegEx rules you have in <strong>"Settings"</strong> -&gt; <strong>"Plugin Usage Preferences"</strong> -&gt; <strong>"Do not load the plugin on certain pages"</strong>, thus %s is not loaded on that page and no CSS/JS are to be managed. If you wish to view the CSS/JS manager, please remove the matching RegEx rule and reload this page.', 'wp-asset-clean-up'), WPACU_PLUGIN_TITLE);
	?>
	<p class="wpacu-warning"
	   style="margin: 15px 0 0; padding: 10px; font-size: inherit; width: 99%;">
            <span style="color: red;"
                  class="dashicons dashicons-info"></span> <?php echo $msg; ?>
	</p>
	<?php
} else {
	$strAdminUrl  = 'admin.php?page='.WPACU_PLUGIN_ID.'_assets_manager&wpacu_for='.$data['for'];

	if ( $data['for'] !== 'homepage' && isset($data['post_id']) && $data['post_id'] ) {
		$strAdminUrl .= '&wpacu_post_id=' . $data['post_id'];
	}

	$strAdminUrl .= '&wpacu_rand='.uniqid(time(), true);

	if (array_key_exists('wpacu_manage_dash', $_GET) || array_key_exists('force_manage_dash', $_REQUEST)) { // For debugging purposes
		$strAdminUrl .= '&wpacu_manage_dash';
	}

	$wpacuAdminUrl = admin_url($strAdminUrl);

	// Show the search form on tabs such as "Posts", "Pages", "Custom Post Types"
    // Do not how it in the homepage (that was set to be a singular page) as it could confuse the admin
	if ( ! (isset($data['is_homepage_tab']) && $data['is_homepage_tab']) ) {
		require_once '_singular-page-search-form.php';
    }
	?>
	<form id="wpacu_dash_assets_manager_form" method="post" action="<?php echo $wpacuAdminUrl; ?>">
		<input type="hidden"
               id="wpacu_manage_singular_page_assets"
		       name="wpacu_manage_singular_page_assets"
		       value="1" />

        <input type="hidden"
               id="wpacu_manage_singular_page_id"
               name="wpacu_manage_singular_page_id"
               value="<?php echo $data['post_id']; ?>" />

		<input type="hidden"
		       id="wpacu_ajax_fetch_assets_list_dashboard_view"
		       name="wpacu_ajax_fetch_assets_list_dashboard_view"
		       value="1" />

        <?php
        if (isset($data['post_type']) && $data['post_type']) {
	        $postTypeObject = get_post_type_object( $data['post_type'] );
	        $postTypeLabels = $postTypeObject->labels;
	        $postName = $postTypeLabels->singular_name;
        }

        if (isset($data['is_homepage_tab']) && $data['is_homepage_tab']) {
	        $pageUrlTitle = __('Homepage URL', 'wp-asset-clean-up');
        } else {
	        $pageUrlTitle = __('Page URL', 'wp-asset-clean-up');
        }
        ?>
        <div class="wpacu_verified">
            <strong><?php echo $pageUrlTitle; ?>:</strong> <a target="_blank" href="<?php echo $data['fetch_url']; ?>"><span><?php echo $data['fetch_url']; ?></span></a>
			| <strong><?php echo isset($postName) ? $postName : ''; ?> Title:</strong> <?php echo get_the_title($data['post_id']); ?> | <strong>Post ID:</strong> <?php echo $data['post_id']; ?>
        </div>

		<div id="wpacu_meta_box_content">
			<?php
			$wpacuLoadingSpinnerFetchAssets = '<img src="'.admin_url('images/spinner.gif').'" align="top" width="20" height="20" alt="" />';

			// "Select a retrieval way:" is set to "Direct" (default one) in "Plugin Usage Preferences" -> "Manage in the Dashboard"
			if ($data['wpacu_settings']['dom_get_type'] === 'direct') {
				$wpacuDefaultFetchListStepDefaultStatus   = '<img src="'.admin_url('images/spinner.gif').'" align="top" width="20" height="20" alt="" />&nbsp; Please wait...';
				$wpacuDefaultFetchListStepCompletedStatus = '<span style="color: green;" class="dashicons dashicons-yes-alt"></span> Completed';
				?>
				<div id="wpacu-list-step-default-status" style="display: none;"><?php echo $wpacuDefaultFetchListStepDefaultStatus; ?></div>
				<div id="wpacu-list-step-completed-status" style="display: none;"><?php echo $wpacuDefaultFetchListStepCompletedStatus; ?></div>
				<div>
					<ul class="wpacu_meta_box_content_fetch_steps">
						<li id="wpacu-fetch-list-step-1-wrap"><strong>Step 1</strong>: Fetch the assets from the targeted page... <span id="wpacu-fetch-list-step-1-status"><?php echo $wpacuDefaultFetchListStepDefaultStatus; ?></span></li>
						<li id="wpacu-fetch-list-step-2-wrap"><strong>Step 2</strong>: Build the list of the fetched assets and print it... <span id="wpacu-fetch-list-step-2-status"></span></li>
					</ul>
				</div>
				<?php
			} else {
				// "Select a retrieval way:" is set to "WP Remote Post" (one AJAX call) in "Plugin Usage Preferences" -> "Manage in the Dashboard"
				?>
				<?php echo $wpacuLoadingSpinnerFetchAssets; ?>&nbsp;
				<?php _e('Retrieving the loaded scripts and styles for the home page. Please wait...', 'wp-asset-clean-up');
			}
			?>

			<p><?php echo sprintf(
					__('If you believe fetching the page takes too long and the assets should have loaded by now, I suggest you go to "Settings", make sure "Manage in front-end" is checked and then %smanage the assets in the front-end%s.', 'wp-asset-clean-up'),
					'<a href="'.$data['fetch_url'].'#wpacu_wrap_assets">',
					'</a>'
				); ?></p>
		</div>

		<?php
		wp_nonce_field($data['nonce_action'], $data['nonce_name']);
		?>
		<div id="wpacu-update-button-area" class="no-left-margin">
			<p class="submit"><input type="submit" name="submit" id="submit" class="hidden button button-primary" value="<?php esc_attr_e('Update', 'wp-asset-clean-up'); ?>"></p>
			<div id="wpacu-updating-settings" style="margin-left: 100px;">
				<img src="<?php echo admin_url('images/spinner.gif'); ?>" align="top" width="20" height="20" alt="" />
			</div>
		</div>
	</form>
	<?php
}