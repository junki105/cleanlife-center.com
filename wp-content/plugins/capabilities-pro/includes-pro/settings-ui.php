<?php
namespace PublishPress\Capabilities;

class Pro_Settings_UI {
    public function __construct() {
        $this->loadScripts();
        $this->settingsUI();
    }

    public function loadScripts() {
        $suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '.dev' : '';
        wp_enqueue_script('publishpress-caps-pro-settings', plugins_url('', CME_FILE) . "/includes-pro/settings-pro{$suffix}.js", ['jquery', 'jquery-form'], PUBLISHPRESS_CAPS_VERSION, true);
    }

    private function footerScripts($activated, $expired)
    {
        $vars = [
            'activated' => ($activated || !empty($expired)) ? true : false,
            'expired' => !empty($expired),
            'activateCaption' => __('Activate Key', 'capabilities-pro'),
            'deactivateCaption' => __('Deactivate Key', 'capabilities-pro'),
            'connectingCaption' => __('Connecting to publishpress.com server...', 'capabilities-pro'),
            'noConnectCaption' => __('The request could not be processed due to a connection failure.', 'capabilities-pro'),
            'noEntryCaption' => __('Please enter the license key shown on your order receipt.', 'capabilities-pro'),
            'errCaption' => __('An unidentified error occurred.', 'capabilities-pro'),
            'keyStatus' => json_encode([
                'deactivated' => __('The key has been deactivated.', 'capabilities-pro'),
                'valid' => __('The key has been activated.', 'capabilities-pro'),
                'expired' => __('The key has expired.', 'capabilities-pro'),
                'invalid' => __('The key is invalid.', 'capabilities-pro'),
                '-100' => __('An unknown activation error occurred.', 'capabilities-pro'),
                '-101' => __('The key provided is not valid. Please double-check your entry.', 'capabilities-pro'),
                '-102' => __('This site is not valid to activate the key.', 'capabilities-pro'),
                '-103' => __('The key provided could not be validated by publishpress.com.', 'capabilities-pro'),
                '-104' => __('The key provided is already active on another site.', 'capabilities-pro'),
                '-105' => __('The key has already been activated on the allowed number of sites.', 'capabilities-pro'),
                '-200' => __('An unknown deactivation error occurred.', 'capabilities-pro'),
                '-201' => __('Unable to deactivate because the provided key is not valid.', 'capabilities-pro'),
                '-202' => __('This site is not valid to deactivate the key.', 'capabilities-pro'),
                '-203' => __('The key provided could not be validated by publishpress.com.', 'capabilities-pro'),
                '-204' => __('The key provided is not active on the specified site.', 'capabilities-pro'),
            ]),
            'activateURL' => wp_nonce_url(admin_url(''), 'wp_ajax_pp_activate_key'),
            'deactivateURL' => wp_nonce_url(admin_url(''), 'wp_ajax_pp_deactivate_key'),
            'refreshURL' => wp_nonce_url(admin_url(''), 'wp_ajax_pp_refresh_version'),
            'activationHelp' => sprintf(__('If this is incorrect, <a href="%s">request activation help</a>.', 'capabilities-pro'), 'https://publishpress.com/contact/'),
            'supportOptChanged' => __('Please save settings before uploading site configuration.', 'capabilities-pro'),
        ];

        wp_localize_script('publishpress-caps-pro-settings', 'ppCapabilitiesSettings', $vars);
    }

    public function settingsUI() {
        $all_options = [];

        ?>

        <!--
        <ul id="publishpress-capability-backup-tabs" class="nav-tab-wrapper">
            <li class="nav-tab nav-tab-active"><a href="#ppcb-tab-restore">Restore</a></li>
            <li class="nav-tab"><a href="#ppcb-tab-backup">Backup</a></li>
            <li class="nav-tab"><a href="#ppcb-tab-reset">Reset Roles</a></li>
        </ul>
        -->

        <table class="form-table" role="presentation">
        <tbody>
        <tr>
                    <th scope="row">
                        <?php _e('License Key Activation', 'capsman-enhanced'); ?>
                    </th>
                    <td>
                        <div class="capsman-key-activation">
            <div class="pp-key-wrap">

                <?php
                require_once(PUBLISHPRESS_CAPS_ABSPATH . '/includes-pro/library/Factory.php');
                $container      = \PublishPress\Capabilities\Factory::get_container();
                $licenseManager = $container['edd_container']['license_manager'];

                global $activated;

                $id = 'edd_key';

                if (!get_transient('publishpress-caps-refresh-update-info')) {
                    publishpress_caps_pro()->keyStatus(true);
                    set_transient('publishpress-caps-refresh-update-info', true, 60 * 60 * 24 * 14);  // Force key status query only once every 2 weeks. This mechanism will be improved soon.
                }

                $opt_val = get_option("cme_edd_key");

                if (!is_array($opt_val) || count($opt_val) < 2) {
                    $activated = false;
                    $expired = false;
                    $key = '';
                    $opt_val = [];
                } else {
                    $activated = !empty($opt_val['license_status']) && ('valid' == $opt_val['license_status']);
                    $expired = $opt_val['license_status'] && ('expired' == $opt_val['license_status']);
                }

                if (isset($opt_val['expire_date']) && is_date($opt_val['expire_date'])) {
                    $date = new \DateTime(date('Y-m-d H:i:s', strtotime($opt_val['expire_date'])), new \DateTimezone('UTC'));
                    $date->setTimezone(new \DateTimezone('America/New_York'));
                    $expire_date_gmt = $date->format("Y-m-d H:i:s");
                    $expire_days = intval((strtotime($expire_date_gmt) - time()) / 86400);
                } else {
                    unset($opt_val['expire_date']);
                }

                $msg = '';

                if ($expired) {
                    $class = 'activating';
                    $is_err = true;
                    $msg = sprintf(
                        __('Your PublishPress license key has expired. For continued priority support, <a href="%s">please renew</a>.', 'capabilities-pro'),
                        'https://publishpress.com/my-downloads/'
                    );
                } elseif (!empty($opt_val['expire_date'])) {
                    $class = 'activating';
                    if ($expire_days < 30) {
                        $is_err = true;
                    }

                    if ($expire_days == 1) {
                        $msg = sprintf(
                            __('Your PublishPress license key will expire today. For updates and priority support, <a href="%s">please renew</a>.', 'capabilities-pro'),
                            $expire_days,
                            'https://publishpress.com/my-downloads/'
                        );
                    } elseif ($expire_days < 30) {
                        $msg = sprintf(
                            _n(
                                'Your PublishPress license key will expire in %d day. For updates and priority support, <a href="%s">please renew</a>.',
                                'Your PublishPress license key (for plugin updates) will expire in %d days. For updates and priority support, <a href="%s">please renew</a>.',
                                $expire_days,
                                'capabilities-pro'
                            ),
                            $expire_days,
                            'https://publishpress.com/my-downloads/'
                        );
                    } else {
                        $class = "activating hidden";
                    }
                } elseif (!$activated) {
                    $class = 'activating';
                    //$msg = sprintf(__('For updates, activate your <a href="%s">key</a>.', 'capabilities-pro'), 'https://publishpress.com/pricing/');
                    //$msg = sprintf(__('<a href="%s">Pricing</a>', 'capabilities-pro'), 'https://publishpress.com/pricing/');
                } else {
                    $class = "activating hidden";
                    $msg = '';
                }
                ?>

                <?php if ($expired && (!empty($key))) : ?>
                                    <div class="pp-key-label">
                    <span class="pp-key-expired"><?php _e("Key Expired", 'capabilities-pro') ?></span>
                                    </div>
                                    <div class="pp-key-license">
                    <input name="<?php echo($id); ?>" type="text" id="<?php echo($id); ?>" style="display:none"/>
                    <button type="button" id="activation-button" name="activation-button"
                            class="button-secondary"><?php _e('Deactivate', 'capabilities-pro'); ?></button>
                                    </div>
                <?php else : ?>
                    <div class="pp-key-label">
                        <span class="pp-key-active" <?php if (!$activated) echo 'style="display:none;"';?>><?php _e("Activated", 'press-permit-core') ?></span>
                    </div>
                                    <div class="pp-key-license">
                                        <input name="<?php echo($id); ?>" type="text" placeholder="<?php _e('Enter your license key', 'press-permit-pro');?>" id="<?php echo($id); ?>"
                        maxlength="40" <?php echo ($activated) ? ' style="display:none"' : ''; ?> />
                    <button type="button" id="activation-button" name="activation-button"
                        class="button-secondary"><?php echo (!$activated) ? __('Activate', 'capabilities-pro') : __('Deactivate', 'capabilities-pro'); ?></button>
                                    </div>
                <?php endif; ?>

                <img id="pp_support_waiting" class="waiting" style="display:none;position:relative" src="<?php echo esc_url(admin_url('images/wpspin_light.gif')) ?>" alt=""/>

                <?php
                $update_info = [];

                $info_link = '';

                if (empty($suppress_updates)) {
                    $wp_plugin_updates = get_site_transient('update_plugins');
                    if (
                        $wp_plugin_updates && isset($wp_plugin_updates->response[plugin_basename(CME_FILE)])
                        && !empty($wp_plugin_updates->response[plugin_basename(CME_FILE)]->new_version)
                        && version_compare($wp_plugin_updates->response[plugin_basename(CME_FILE)]->new_version, PUBLISHPRESS_CAPS_VERSION, '>')
                    ) {
                        $update_available = true;

                        $slug = 'capabilities-pro';

                        $_url = "plugin-install.php?tab=plugin-information&plugin=$slug&section=changelog&TB_iframe=true&width=600&height=800";
                        $info_url = (!empty($use_network_admin)) ? network_admin_url($_url) : admin_url($_url);

                        $info_link = "&nbsp;<span class='update-message'> &bull;&nbsp;&nbsp;<a href='$info_url' class='thickbox'>"
                            . sprintf(__('view %s&nbsp;details', 'capabilities-pro'), $wp_plugin_updates->response[plugin_basename(CME_FILE)]->new_version)
                            . '</a></span>';
                    }
                }
                ?>

                <div class="edd-key-links">
                    <div id="activation-status" class="<?php echo $class?>"></div>

                    <?php if (!empty($update_available)):?>
                        <?php //printf(__('Version %1$s %2$s', 'capabilities-pro'), PUBLISHPRESS_CAPS_VERSION, $info_link); 
                        ?>

                        <a href="<?php echo admin_url('update-core.php');?>"><?php _e('Update&nbsp;Available', 'capabilities-pro'); ?></a>

                        &nbsp;&bull;&nbsp;
                    <?php elseif (current_user_can('activate_plugins')):?>
                        <a href="<?php echo add_query_arg('publishpress_caps_refresh_updates', 1, $_SERVER['REQUEST_URI']);?>"><?php _e('Update&nbsp;Check', 'capabilities-pro'); ?></a>
                        &nbsp;&bull;&nbsp;
                    <?php endif;?>

                    <span class="pp-key-refresh">
                    <a href="https://publishpress.com/checkout/purchase-history/" target="_blank">
                    <?php _e('Account', 'capabilities-pro');?>
                    </a>
                    </span>

                    <?php if (!$activated):?>
                        &nbsp;&bull;&nbsp;
                        <span><?php printf(__('<a href="%s" target="_blank">Pricing</a>', 'capabilities-pro'), 'https://publishpress.com/pricing/'); ?></span>
                    <?php endif;?>
                </div>

                <?php if (!empty($is_err)) : ?>
                    <div id="activation-error" class="error"><?php echo $msg; ?></div>
                <?php endif; ?>

            </div>
                        </div>

        <?php 
        $this->footerScripts($activated, $expired);
        ?>
                    </td>
                </tr>
        <tr>
            <?php
            $all_options []= 'cme_custom_status_control';

            if (defined('PUBLISHPRESS_VERSION') && class_exists('PP_Custom_Status')):
                $checked = get_option('cme_custom_status_control') ? 'checked="checked"' : '';
            ?>
            <th scope="row"><?php _e('Control Custom Statuses', 'capabilities-pro'); ?></th>
            <td>
                <label for="" title="<?php _e('Control selection of custom post statuses.', 'capabilities-pro');?>"> 
                <input type="checkbox" name="cme_custom_status_control" id="cme_custom_status_control" autocomplete="off" value="1" <?php echo $checked;?>>  
                </label>
                <br>
            </td>
            <?php endif;?>
        </tr>

        <tr>
            <?php 
                $all_options []= 'cme_display_branding';
                $checked = get_option('cme_display_branding', 1) ? 'checked="checked"' : '';
            ?>
            <th scope="row"> <?php _e('Display PublishPress Branding', 'capabilities-pro'); ?></th>
            <td>
                <label for="" title="<?php _e('Hide the PublishPress footer and other branding.', 'capabilities-pro');?>"> 
                <input type="checkbox" name="cme_display_branding" id="cme_display_branding" autocomplete="off" value="1" <?php echo $checked;?>>  
                </label>
                <br>
            </td>
        </tr>

        <tr>
            <?php 
                $all_options []= 'cme_admin_menus_restriction_priority';
                $checked = get_option('cme_admin_menus_restriction_priority', 1) ? 'checked="checked"' : '';
            ?>
            <th scope="row"> <?php _e('Admin Menu Restrictions', 'capabilities-pro'); ?></th>
            <td>
                <label for="" title="<?php _e('Admin Menus: treatment of multiple roles', 'capabilities-pro');?>"> 
                <select name="cme_admin_menus_restriction_priority" id="cme_admin_menus_restriction_priority" autocomplete="off">  
                <option value="0"<?php echo ($checked) ? '' : ' selected';?>><?php _e('Any non-restricted user role allows access', 'capabilities-pro');?></option>
                <option value="1"<?php echo ($checked) ? ' selected' : '';?>><?php _e('Any restricted user role prevents access', 'capabilities-pro');?></option>
                </select>
                <div class='cme-subtext'>
                <?php _e('How are restrictions applied when a user has multiple roles?', 'capabilities-pro');?>
                </div>
                </label>
                <br>
            </td>
        </tr>

        </tbody>
        </table>

        <br />
    <?php
        echo "<input type='hidden' name='all_options_pro' value='" . implode(',', $all_options) . "' />";
    }
} // end class
