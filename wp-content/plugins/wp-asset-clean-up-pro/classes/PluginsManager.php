<?php
namespace WpAssetCleanUp;

/**
 *
 * Class PluginsManager
 * @package WpAssetCleanUp
 */
class PluginsManager
{
    /**
     * @var array
     */
    public $data = array();

	/**
	 * PluginsManager constructor.
	 */
	public function __construct()
    {
        // Note: The rules update takes place in /pro/classes/UpdatePro.php
	    if (Misc::getVar('get', 'page') === WPACU_PLUGIN_ID . '_plugins_manager') {
		    add_action('wpacu_admin_notices', array($this, 'notices'));
	    }
    }

	/**
	 *
	 */
	public function page()
    {
    	// Get active plugins and their basic information
	    $this->data['active_plugins'] = self::getActivePlugins();
	    $this->data['plugins_icons']  = Misc::getAllActivePluginsIcons();
	    $this->data['rules']          = self::getAllRules(); // get all rules from the database (for either the frontend or dash view)

	    // [wpacu_pro]
	    $this->data['mu_file_missing']  = false; // default
	    $this->data['mu_file_rel_path'] = '/' . str_replace(ABSPATH, '', WPMU_PLUGIN_DIR)
	                                      . '/' . \WpAssetCleanUpPro\PluginPro::$muPluginFileName;

	    if ( ! is_file(WPMU_PLUGIN_DIR . '/' . \WpAssetCleanUpPro\PluginPro::$muPluginFileName) ) {
			$this->data['mu_file_missing']  = true; // alert the user in the "Plugins Manager" area
	    }
        // [/wpacu_pro]

	    Main::instance()->parseTemplate('admin-page-plugins-manager', $this->data, true);
    }

	/**
	 * @param false $fetchAllLocations (if set to true, it will return the rules for both the frontend and the backend
	 *
	 * @return array
	 */
	public static function getAllRules($fetchAllLocations = false)
	{
		$pluginsRulesDbListJson = get_option(WPACU_PLUGIN_ID . '_global_data');

		if ($pluginsRulesDbListJson) {
			$regExDbList = @json_decode($pluginsRulesDbListJson, true);

			// Issues with decoding the JSON file? Return an empty list
			if (Misc::jsonLastError() !== JSON_ERROR_NONE) {
				return array();
			}

			// 1) For listing them in "Overview"
			if ($fetchAllLocations) {
                $rulesList = array();

				if ( isset( $regExDbList['plugins'] ) && ! empty( $regExDbList['plugins'] ) ) {
					$rulesList['plugins'] = $regExDbList['plugins'];
				}

				if ( isset( $regExDbList['plugins_dash'] ) && ! empty( $regExDbList['plugins_dash'] ) ) {
					$rulesList['plugins_dash'] = $regExDbList['plugins_dash'];
				}

				return $rulesList;
            }

			// 2) For listing them within "Plugins Manager" -> "In Frontend View" or "In the Dashboard" when the admin is managing the rules
			$wpacuSubPage = (array_key_exists('wpacu_sub_page', $_GET) && $_GET['wpacu_sub_page']) ? $_GET['wpacu_sub_page'] : 'manage_plugins_front';

			$mainGlobalKey = ($wpacuSubPage === 'manage_plugins_front') ? 'plugins' : 'plugins_dash';

			if ( isset( $regExDbList[$mainGlobalKey] ) && ! empty( $regExDbList[$mainGlobalKey] ) ) {
				return $regExDbList[$mainGlobalKey];
			}
		}

		return array();
	}

	/**
	 * @return array
	 */
	public static function getActivePlugins()
    {
	    $activePluginsFinal = array();

	    // Get active plugins and their basic information
	    $activePlugins = array_unique(get_option('active_plugins', array()));

	    foreach ($activePlugins as $plugin) {
		    // Skip Asset CleanUp as it's obviously needed for the functionality
		    if (strpos($plugin, 'wp-asset-clean-up') !== false) {
			    continue;
		    }

		    $pluginData = get_plugin_data(WP_CONTENT_DIR . '/plugins/'.$plugin);
		    $activePluginsFinal[] = array('title' => $pluginData['Name'], 'path' => $plugin);
	    }

	    usort($activePluginsFinal, static function($a, $b)
	    {
		    return strcmp($a['title'], $b['title']);
	    });

	    return $activePluginsFinal;
    }

	/**
	 * Make sure there is a status for the rule, otherwise it's likely set to "Load it",
	 * thus the rule wouldn't count
	 * @param bool $checkIfPluginIsActive
	 * @param bool $getRulesForAllLocations
     *
	 * @return array
	 */
	public static function getPluginRulesFiltered($checkIfPluginIsActive = true, $getRulesForAllLocations = false)
    {
	    $pluginsWithRules = array();

		$pluginsAllDbRules = self::getAllRules($getRulesForAllLocations);

		// Are there any load exceptions / unload RegExes?
	    if (! empty( $pluginsAllDbRules ) ) {
	        foreach ($pluginsAllDbRules as $locationKey => $pluginsRules) {
		        foreach ( $pluginsRules as $pluginPath => $pluginData ) {
			        // Only the rules for the active plugins are retrieved
			        if ( $checkIfPluginIsActive && ! Misc::isPluginActive( $pluginPath ) ) {
				        continue;
			        }

			        // 'status' refers to the Unload Status (any option that was chosen)
			        $pluginStatus = isset( $pluginData['status'] ) && ! empty( $pluginData['status'] ) ? $pluginData['status'] : array();

			        if ( ! empty( $pluginStatus ) ) {
				        $pluginsWithRules[ $locationKey ][ $pluginPath ] = $pluginData;
			        }
		        }
	        }

		    }

	    return $pluginsWithRules;
    }

	/**
	 *
	 */
	public function notices()
	{
		// After "Save changes" is clicked
		if (get_transient('wpacu_plugins_manager_updated')) {
			delete_transient('wpacu_plugins_manager_updated');

			$appliedForText = '';
			if (array_key_exists('wpacu_sub_page', $_GET)) {
				if ( $_GET['wpacu_sub_page'] === 'manage_plugins_front' ) {
					$appliedForText = 'the frontend view';
				} elseif ( $_GET['wpacu_sub_page'] === 'manage_plugins_dash' ) {
					$appliedForText = 'the Dashboard view (/wp-admin/)';
				}
			}

			if ($appliedForText !== '') {
			?>
			<div style="margin-bottom: 15px; margin-left: 0; width: 90%;" class="notice notice-success is-dismissible">
				<p><span class="dashicons dashicons-yes"></span> <?php echo sprintf(__('The plugins\' rules were successfully applied within %s.', 'wp-asset-clean-up'), $appliedForText); ?></p>
			</div>
			<?php
            }
		}
	}
}
