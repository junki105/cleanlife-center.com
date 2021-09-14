<?php
/*
 * No direct access to this file
 */
if (! isset($data)) {
    exit;
}

$licenseKeyValue = $licenseStatus = '';

include_once '_top-area.php';

$isLicenseInDb       = ($licenseKeyValue !== '' && strlen($licenseKeyValue) === 32);
$isLicenseWithStatus = in_array($licenseStatus, array('valid', 'invalid', 'expired', 'disabled'));
?>
<div class="wpacu-wrap wpacu-license-area">
    <h2><?php _e('License Settings', 'wp-asset-clean-up'); ?></h2>

    <?php
    do_action('wpacu_admin_notices');
    ?>

    <p>Activating the license allows you to update the plugin within the Dashboard. To access your downloads, license(s), purchase history &amp; account settings, you can <a href="<?php echo WPACU_PRO_PLUGIN_STORE_URL; ?>/customer-dashboard/">log in to your customer dashboard</a>.</p>

    <p><strong>Note:</strong> Local Host URLs (including staging/dev environments) are automatically excluded from the total activation count if the website's URL matches any one of these: <em>localhost, 10.0.0.0/8, 172.16.0.0/12, 192.168.0.0/16, *.dev, .*local, dev.*, staging.*</em></p>

    <hr style="margin: 15px 0;" />

    <form id="wpacu-license-form" method="post" action="">
        <table id="wpacu-license-table-info" class="wpacu-form-table wpacu-license">
            <tbody>
            <?php
            if (! $isLicenseInDb || ! $isLicenseWithStatus) {
                /*
                ----------------------------
                Status: License is inactive
                ----------------------------
                */

                // Something is not right or the license has never been entered; clean any traces
	            delete_option( WPACU_PLUGIN_ID . '_pro_license_key');
	            delete_option( WPACU_PLUGIN_ID . '_pro_license_status');
                ?>
                <tr valign="top">
                    <th scope="row" valign="top">
			            <?php _e('Status', 'wp-asset-clean-up'); ?>
                    </th>
                    <td style="padding-bottom: 18px;">
                        <span id="wpacu-license-status-area"><span style="color: #cc0000; font-weight: bold;"><span class="dashicons dashicons-warning"></span> <?php _e('inactive', 'wp-asset-clean-up'); ?>&nbsp;&nbsp;</span></span>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row" valign="top">
                        <?php _e('Key', 'wp-asset-clean-up'); ?>
                    </th>
                    <td>
                        <p style="margin: 0;"><label class="description" for="<?php echo WPACU_PLUGIN_ID . '_pro_license_key'; ?>"><em><?php _e('Add the license key of 32 characters you received in your purchase email receipt.', 'wp-asset-clean-up'); ?></em></label></p>

                            <p style="margin-bottom: 10px;">
                                <input id="<?php echo WPACU_PLUGIN_ID . '_pro_license_key'; ?>"
                                   name="<?php echo WPACU_PLUGIN_ID . '_pro_license_key'; ?>"
                                   type="text"
                                   required="required"
                                   pattern=".{32}"
                                   class="regular-text" />
                                <?php wp_nonce_field( WPACU_PLUGIN_ID . '_pro_nonce', WPACU_PLUGIN_ID . '_pro_nonce' ); ?>
                            </p>

                            <div class="wpacu-license-action-btn-area">
                                <input type="hidden" name="wpacu_license_activate" value="1" />
                                <?php submit_button(__('Activate License', 'wp-asset-clean-up'), 'primary', 'edd_license_activate_btn', false); ?>
                                <span class="wpacu-license-spinner"><img alt="" src="<?php echo includes_url('images/spinner-2x.gif'); ?>" /></span>
                            </div>
                    </td>
                </tr>
            <?php
            } elseif ($isLicenseInDb && $isLicenseWithStatus) {
                /*
                ------------------------------------------------------------------
                Status: License is activated (either active, expired or disabled)
                ------------------------------------------------------------------
                */
	            $licenseKeyValueHidden = substr_replace($licenseKeyValue, '************************', 4, 24);
	            ?>
                <tr valign="top" id="wpacu-initial-license-status">
                    <th scope="row" valign="top">
			            <?php _e('Status', 'wp-asset-clean-up'); ?>
                    </th>
                    <td style="padding-top: 5px; padding-bottom: 16px;" id="wpacu-license-status-cell">

                        <?php if ($licenseStatus === 'valid') { ?>
                            <span id="wpacu-license-status-area"><span style="color: green; font-weight: bold;"><span class="dashicons dashicons-yes"></span> <?php _e('active', 'wp-asset-clean-up'); ?>&nbsp;&nbsp;</span></span>
                        <?php } elseif ($licenseStatus === 'expired') {
                            ?>
                            <span id="wpacu-license-status-area"><span style="color: #cc0000; font-weight: bold;"><?php _e('expired', 'wp-asset-clean-up'); ?>&nbsp;&nbsp;</span> <span style="display: none; vertical-align: top;" id="wpacu-license-renewal-link">&nbsp;<a href="#" class="button button-primary">Renew License</a></span></span>
                            <?php
                        } elseif ($licenseStatus === 'disabled') {
	                        ?>
                            <span id="wpacu-license-status-area"><span style="color: #cc0000; font-weight: bold;"><?php _e('disabled', 'wp-asset-clean-up'); ?>&nbsp;&nbsp;</span> * <small style="font-weight: 300; font-style: italic;">It looks like the license has been disabled. It usually happens when a refund has been issued.</small></span>
	                        <?php
                        }
                        ?>

                        <span id="wpacu-license-spinner-for-info">
                            <img alt=""
                                 style="width: 20px; height: 20px; vertical-align: middle;"
                                 src="<?php echo includes_url('images/spinner-2x.gif'); ?>"></span>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row" valign="top">
		                <?php _e('Key', 'wp-asset-clean-up'); ?>
                    </th>
                    <td>
                        <p style="margin-top: 0;"><input type="text" size="32" disabled="disabled" value="<?php echo $licenseKeyValueHidden; ?>" /></p>
                        <?php wp_nonce_field( WPACU_PLUGIN_ID . '_pro_nonce', WPACU_PLUGIN_ID . '_pro_nonce' ); ?>

                        <div class="wpacu-license-action-btn-area">
                            <input type="hidden" name="wpacu_license_deactivate" value="1" />
                            <input type="submit" class="button-secondary" name="wpacu_license_deactivate_btn" id="wpacu_license_deactivate_btn" value="<?php _e('Deactivate License', 'wp-asset-clean-up'); ?>"/>
                            <span class="wpacu-license-spinner"><img alt="" src="<?php echo includes_url('images/spinner-2x.gif'); ?>" /></span>
                        </div>

                        <p style="margin: 20px 0 0 0;">Reasons to deactivate the license include:</p>
                        <ul style="list-style: disc; margin: 10px 0 0 0; padding: 0 0 0 20px;">
                            <li>You don't want to use <?php echo WPACU_PLUGIN_TITLE; ?> anymore on this website</li>
                            <li>You want to activate the license key on a different website (e.g. you have the Single or Plus license and reached the usage limit)</li>
                            <li>You want to use a different license key for this website</li>
                        </ul>
                    </td>
                </tr>
            <?php
            }
            ?>
            </tbody>
        </table>
    </form>

    <?php if (! $isLicenseInDb || ! $isLicenseWithStatus) { ?>
        <form id="wpacu-license-form" method="post" action="">
            <div id="wpacu-activation-issues-info">
                <p><strong>If you're getting timeout errors or a message such as "The link you followed has expired." after you try to activate the license, please follow these steps one after another to make sure the license will be properly marked as active:</strong></p>
                <ol>
                    <li>Go to <a href="https://www.gabelivan.com/customer-dashboard/">https://www.gabelivan.com/customer-dashboard/</a>, login to your account and then click on "View Licenses" from the "Purchase History" area.</li>
                    <li>Within the "Purchase History" area, click on "Manage Sites" and under "Use this form to authorize a new site URL for this license. Enter the full site URL." add the URL to your website: <code><?php echo home_url(); ?></code></li>
                    <li>Finally, click on the button below to mark the license as active on your end. Once that's done, and you verified that the license key is correct, you will be able to download updates of the plugin from the Dashboard.
                        <div class="wpacu-clearfix"></div>
                        <div id="wpacu-mark-license-area-wrap">
                            <input id="<?php echo WPACU_PLUGIN_ID . '_pro_license_key'; ?>"
                                   name="<?php echo WPACU_PLUGIN_ID . '_pro_license_key'; ?>"
                                   placeholder="Your 32 characters license key"
                                   type="text"
                                   required="required"
                                   pattern=".{32}"
                                   class="regular-text" /> &nbsp; <input id="wpacu-mark-license-valid-button"
                                                                         name="wpacu_mark_license_valid_button"
                                                                         type="submit"
                                                                         class="button-secondary"
                                                                         value="<?php _e('Mark the license as active for this website', 'wp-asset-clean-up'); ?>" />
                        </div>
                    </li>
                </ol>
            </div>
        </form>
    <?php } ?>
</div>