<?php
namespace WpAssetCleanUpPro;

/**
 * Class PluginPro
 * @package WpAssetCleanUpPro
 */
class PluginPro
{
	/**
	 * @var string
	 */
	public static $muPluginFileName = 'wpacu-plugins-filter.php';

	/**
	 * PluginPro constructor.
	 */
	public function __construct()
	{
		// Only trigger when a plugin page is accessed within the Dashboard
		if (is_admin() && isset($_GET['page']) && (strpos($_GET['page'], WPACU_PLUGIN_ID.'_') !== false)) {
			self::copyMuPluginFilter();
		}

		add_action( 'upgrader_process_complete', static function( $upgrader_object, $options ) {
			self::copyMuPluginFilter();
		}, 10, 2 );

		register_activation_hook(WPACU_PLUGIN_FILE, array($this, 'whenActivated'));
		register_deactivation_hook(WPACU_PLUGIN_FILE, array($this, 'whenDeactivated'));
	}

	/**
	 *
	 */
	public static function copyMuPluginFilter()
	{
		// Isn't the MU plugin there? Copy it
		$copyFrom = dirname( WPACU_PLUGIN_FILE ) . '/pro/mu-plugins/to-copy/' . self::$muPluginFileName;
		$copyTo   = WPMU_PLUGIN_DIR . '/' . self::$muPluginFileName;

		if (! is_file(WPMU_PLUGIN_DIR . '/' . self::$muPluginFileName)) {
			// MU plugins directory has to be there first
			if (! is_dir( WPMU_PLUGIN_DIR )) {
				// Attempt directory creation
				$muPluginsCreateDir = ( @mkdir(WPMU_PLUGIN_DIR, 0755 ) && is_dir( WPMU_PLUGIN_DIR ) );

				if ( $muPluginsCreateDir ) {
					@copy( $copyFrom, $copyTo );
					return;
				}

				// The directory couldn't be created / The error will be shown from /classes/PluginsManager.php
				return;
			}

			// MU plugin directory was already created; copy the MU plugin
			@copy( $copyFrom, $copyTo );
		}
	}

	/**
	 * Copy/Update the MU plugin file
	 */
	public function whenActivated()
	{
		self::copyMuPluginFilter();
	}

	/**
	 * Remove the MU plugin file
	 */
	public function whenDeactivated()
	{
		@unlink(WPMU_PLUGIN_DIR.'/'.self::$muPluginFileName);
	}
}
