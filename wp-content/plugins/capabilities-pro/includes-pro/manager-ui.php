<?php
namespace PublishPress\Capabilities;

class ManagerUI {
    function __construct() {
        $this->loadScripts();

        add_filter('cme_plugin_capabilities', [$this, 'fltPluginCapabilities']);
        add_action('pp-capabilities-type-specific-ui', [$this, 'customStatusControlOptionUI']);
    }

    public function customStatusControlOptionUI() {
        if (defined('PUBLISHPRESS_VERSION') && class_exists('PP_Custom_Status')) {
            $checked = get_option('cme_custom_status_control') ? 'checked="checked"' : '';
            ?>
            <p>
            <label for="" title="<?php _e('Control selection of custom post statuses.', 'capabilities-pro-by-publishpress');?>"> <input type="checkbox" name="cme_custom_status_control" id="cme_custom_status_control" autocomplete="off" value="1" <?php echo $checked;?>> <?php _e('Control Custom Statuses', 'capabilities-pro-by-publishpress'); ?> </label>
            </p>
        <?php
        }
    }

    public function loadScripts() {
        $suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '.dev' : '';
        wp_enqueue_script('publishpress-caps-pro-settings', plugins_url('', CME_FILE) . "/includes-pro/settings-pro{$suffix}.js", ['jquery', 'jquery-form'], PUBLISHPRESS_CAPS_VERSION, true);
    }

    public function fltPluginCapabilities($plugin_caps) {
        if (class_exists('BuddyPress')) {
            $plugin_caps['BuddyPress'] = apply_filters('cme_buddypress_capabilities',
                ['bp_moderate', 'bp_create_groups']
            );
        }

        ksort($plugin_caps);

        return $plugin_caps;
    }
}
