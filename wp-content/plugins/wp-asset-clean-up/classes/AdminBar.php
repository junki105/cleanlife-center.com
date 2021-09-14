<?php
namespace WpAssetCleanUp;

/**
 * Class AdminBar
 * @package WpAssetCleanUp
 */
class AdminBar
{
	/**
	 *
	 */
	public function __construct()
	{
		add_action( 'init', array( $this, 'topBar' ) );

		// Hide top WordPress admin bar on request for debugging purposes and a cleared view of the tested page
		// This is done in /early-triggers.php within assetCleanUpNoLoad() function
	}

	/**
	 *
	 */
	public function topBar()
	{
		if (Menu::userCanManageAssets() && (! Main::instance()->settings['hide_from_admin_bar'])) {
			add_action( 'admin_bar_menu', array( $this, 'topBarInfo' ), 81 );
		}
	}

	/**
	 * @param $wp_admin_bar
	 */
	public function topBarInfo($wp_admin_bar)
	{
		$topTitle = WPACU_PLUGIN_TITLE;

		$wpacuUnloadedAssetsStatus = false;

		if (! is_admin()) {
			$markedCssListForUnload = isset(Main::instance()->allUnloadedAssets['css']) ? array_unique(Main::instance()->allUnloadedAssets['css']) : array();
			$markedJsListForUnload  = isset(Main::instance()->allUnloadedAssets['js'])  ? array_unique(Main::instance()->allUnloadedAssets['js'])  : array();

			// [wpacu_lite]
			// Do not print any irrelevant data from the Pro version such as hardcoded CSS/JS
			$markedCssListForUnload = array_filter($markedCssListForUnload, function($value) {
				if (strpos($value, 'wpacu_hardcoded_style_') === 0) {
					return false;
				}

				return $value;
			});

			$markedJsListForUnload = array_filter($markedJsListForUnload, function($value) {
				if (strpos($value, 'wpacu_hardcoded_script_') === 0) {
					return false;
				}

				return $value;
			});
			// [/wpacu_lite]

			$wpacuUnloadedAssetsStatus = (count($markedCssListForUnload) + count($markedJsListForUnload)) > 0;
		}

		if ($wpacuUnloadedAssetsStatus) {
			$styleAttrType = Misc::getStyleTypeAttribute();

			$cssStyle = <<<HTML
<style {$styleAttrType}>
#wpadminbar .wpacu-alert-sign-top-admin-bar {
    font-size: 20px;
    color: lightyellow;
    vertical-align: top;
    margin: -7px 0 0;
    display: inline-block;
    box-sizing: border-box;
}

#wp-admin-bar-assetcleanup-plugin-unload-rules-notice-default .ab-item {
	min-width: 250px !important;
}

#wp-admin-bar-assetcleanup-plugin-unload-rules-notice .ab-item > .dashicons-admin-plugins {
	width: 20px;
	height: 20px;
    font-size: 20px;
    line-height: normal;
    vertical-align: middle;
    margin-top: -2px;
}
</style>
HTML;
			$topTitle .= $cssStyle . '&nbsp;<span class="wpacu-alert-sign-top-admin-bar dashicons dashicons-filter"></span>';
		}

		if (Main::instance()->settings['test_mode']) {
			$topTitle .= '&nbsp; <span class="dashicons dashicons-admin-tools"></span> <strong>TEST MODE</strong> is <strong>ON</strong>';
		}

		$goBackToCurrentUrl = '&_wp_http_referer=' . urlencode( wp_unslash( $_SERVER['REQUEST_URI'] ) );

		$wp_admin_bar->add_menu(array(
			'id'    => 'assetcleanup-parent',
			'title' => $topTitle,
			'href'  => admin_url('admin.php?page=' . WPACU_PLUGIN_ID . '_settings')
		));

		$wp_admin_bar->add_menu(array(
			'parent' => 'assetcleanup-parent',
			'id'     => 'assetcleanup-settings',
			'title'  => __('Settings', 'wp-asset-clean-up'),
			'href'   => admin_url( 'admin.php?page=' . WPACU_PLUGIN_ID . '_settings')
		));

		$wp_admin_bar->add_menu( array(
			'parent' => 'assetcleanup-parent',
			'id'     => 'assetcleanup-clear-css-js-files-cache',
			'title'  => __('Clear CSS/JS Files Cache', 'wp-asset-clean-up'),
			'href'   => wp_nonce_url( admin_url( 'admin-post.php?action=assetcleanup_clear_assets_cache' . $goBackToCurrentUrl ),
				'assetcleanup_clear_assets_cache' )
		) );

		// Only trigger in the front-end view
		if (! is_admin()) {
			if ( ! Misc::isHomePage() ) {
				// Not on the home page
				$homepageManageAssetsHref = Main::instance()->frontendShow()
					? get_site_url().'#wpacu_wrap_assets'
					: admin_url( 'admin.php?page=' . WPACU_PLUGIN_ID . '_assets_manager&wpacu_for=homepage' );

				$wp_admin_bar->add_menu(array(
					'parent' => 'assetcleanup-parent',
					'id'     => 'assetcleanup-homepage',
					'title'  => __('Manage Homepage Assets', 'wp-asset-clean-up'),
					'href'   => $homepageManageAssetsHref
				));
			} else {
				// On the home page
				// Front-end view is disabled! Go to Dashboard link
				if ( ! Main::instance()->frontendShow() ) {
					$wp_admin_bar->add_menu( array(
						'parent' => 'assetcleanup-parent',
						'id'     => 'assetcleanup-homepage',
						'title'  => __('Manage Page Assets', 'wp-asset-clean-up'),
						'href'   => admin_url('admin.php?page=' . WPACU_PLUGIN_ID . '_assets_manager&wpacu_for=homepage')
					) );
				}
			}
		}

		if (! is_admin() && Main::instance()->frontendShow()) {
			$wp_admin_bar->add_menu(array(
				'parent' => 'assetcleanup-parent',
				'id'     => 'assetcleanup-jump-to-assets-list',
				'title'  => __('Manage Page Assets', 'wp-asset-clean-up'),
				'href'   => '#wpacu_wrap_assets'
			));
		}

		$wp_admin_bar->add_menu(array(
			'parent' => 'assetcleanup-parent',
			'id'     => 'assetcleanup-bulk-unloaded',
			'title'  => __('Bulk Changes', 'wp-asset-clean-up'),
			'href'   => admin_url( 'admin.php?page=' . WPACU_PLUGIN_ID . '_bulk_unloads')
		));

		$wp_admin_bar->add_menu( array(
			'parent' => 'assetcleanup-parent',
			'id'     => 'assetcleanup-overview',
			'title'  => __('Overview', 'wp-asset-clean-up'),
			'href'   => admin_url( 'admin.php?page=' . WPACU_PLUGIN_ID . '_overview')
		) );

		$wp_admin_bar->add_menu(array(
			'parent' => 'assetcleanup-parent',
			'id'     => 'assetcleanup-support-forum',
			'title'  => __('Support Forum', 'wp-asset-clean-up'),
			'href'   => 'https://wordpress.org/support/plugin/wp-asset-clean-up',
			'meta'   => array('target' => '_blank')
		));

		// [START LISTING UNLOADED ASSETS]
		if (! is_admin()) { // Frontend view (show any unloaded handles)
			$totalUnloadedAssets = count($markedCssListForUnload) + count($markedJsListForUnload);

			if ($totalUnloadedAssets > 0) {
				$titleUnloadText = sprintf( _n( '%d unload asset rules took effect on this frontend page',
					'%d unload asset rules took effect on this frontend page', $totalUnloadedAssets, 'wp-asset-clean-up' ),
					$totalUnloadedAssets );

				$wp_admin_bar->add_menu( array(
					'parent' => 'assetcleanup-parent',
					'id'     => 'assetcleanup-asset-unload-rules-notice',
					'title'  => '<span style="margin: -10px 0 0;" class="wpacu-alert-sign-top-admin-bar dashicons dashicons-filter"></span> &nbsp; '. $titleUnloadText,
					'href'   => '#'
				) );

				if ( count( $markedCssListForUnload ) > 0 ) {
					$wp_admin_bar->add_menu(array(
						'parent' => 'assetcleanup-asset-unload-rules-notice',
						'id'     => 'assetcleanup-asset-unload-rules-css',
						'title'  => 'CSS ('.count( $markedCssListForUnload ).')',
						'href'   => '#'
					));
					sort($markedCssListForUnload);

					foreach ($markedCssListForUnload as $cssHandle) {
						$wp_admin_bar->add_menu(array(
							'parent' => 'assetcleanup-asset-unload-rules-css',
							'id'     => 'assetcleanup-asset-unload-rules-css-'.$cssHandle,
							'title'  => $cssHandle,
							'href'   => admin_url('admin.php?page=wpassetcleanup_overview#wpacu-overview-css-'.$cssHandle)
						));
					}
				}

				if ( count( $markedJsListForUnload ) > 0 ) {
					$wp_admin_bar->add_menu(array(
						'parent' => 'assetcleanup-asset-unload-rules-notice',
						'id'     => 'assetcleanup-asset-unload-rules-js',
						'title'  => 'JavaScript ('.count( $markedJsListForUnload ).')',
						'href'   => '#'
					));
					sort($markedJsListForUnload);

					foreach ($markedJsListForUnload as $jsHandle) {
						$wp_admin_bar->add_menu(array(
							'parent' => 'assetcleanup-asset-unload-rules-js',
							'id'     => 'assetcleanup-asset-unload-rules-js-'.$jsHandle,
							'title'  => $jsHandle,
							'href'   => admin_url('admin.php?page=wpassetcleanup_overview#wpacu-overview-js-'.$jsHandle)
						));
					}
					}
			}
		}
		// [END LISTING UNLOADED ASSETS]

		}
}
