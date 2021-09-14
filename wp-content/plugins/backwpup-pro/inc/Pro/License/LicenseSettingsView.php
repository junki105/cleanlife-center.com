<?php # -*- coding: utf-8 -*-

namespace Inpsyde\BackWPup\Pro\License;

use Inpsyde\BackWPup\Pro\License\Api\LicenseActivation;
use Inpsyde\BackWPup\Pro\License\Api\LicenseDeactivation;
use Inpsyde\BackWPup\Pro\License\Api\LicenseStatusRequest;
use Inpsyde\BackWPup\Settings\SettingTab;

class LicenseSettingsView implements SettingTab
{
    const LICENSE_INSTANCE_KEY = 'license_instance_key';
    const LICENSE_API_KEY = 'license_api_key';
    const LICENSE_PRODUCT_ID = 'license_product_id';
    const LICENSE_STATUS = 'license_status';

    /**
     * @var LicenseActivation
     */
    private $activate;

    /**
     * @var LicenseDeactivation
     */
    private $deactivate;

    /**
     * @var LicenseStatusRequest
     */
    private $status;

    public function __construct(
        LicenseActivation $activate,
        LicenseDeactivation $deactivate,
        LicenseStatusRequest $status
    ) {
        $this->activate = $activate;
        $this->deactivate = $deactivate;
        $this->status = $status;
    }

    public function tab()
    {
        if (!\BackWPup::is_pro()) {
            return;
        }

        ?>

        <div class="table ui-tabs-hide" id="backwpup-tab-license">
            <table class="form-table">
                <tr>
                    <td><?= wp_kses_post(_x('This version of BackWPup has a new licensing system that requires a Master Api Key and a Product ID in order to be activated. These values are available in your <a href="https://backwpup.com/my-account" target="_blank">My Account</a> section. Further information is available <a href="https://backwpup.com/docs/backwpup-license-update/" target="_blank">here</a>.', 'License', 'backwpup')) ?></td>
                </tr>
            </table>
            <table class="form-table">
                <tbody>
                <tr>
                    <th scope="row">
                        <label for="license-status"><?= esc_html__('Status', 'backwpup') ?></label>
                        </label>
                    </th>
                    <td>
                        <?php
                        $instanceKey = get_site_option(
                            self::LICENSE_INSTANCE_KEY
                        ) ?: wp_generate_password(12, false);

                        $license = new License(
                            get_site_option(self::LICENSE_PRODUCT_ID, ''),
                            get_site_option(self::LICENSE_API_KEY, ''),
                            $instanceKey,
                            get_site_option(self::LICENSE_STATUS, 'inactive')
                        );

                        $status = $this->status->requestStatusFor($license);

                        update_site_option(
                            self::LICENSE_STATUS,
                            isset($status) ? $status : 'inactive'
                        );

                        echo esc_html(ucfirst($status));
                        ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="license-api-key"><?= esc_html__(
                                'Master API Key',
                                'backwpup'
                            ) ?></label>
                        </label>
                    </th>
                    <td>
                        <input name="license_api_key"
                               type="text"
                               id="license-api-key"
                               value="<?= get_site_option(self::LICENSE_API_KEY) ?>"
                               class="regular-text code"/>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="license-email"><?= esc_html__(
                                'Product ID',
                                'backwpup'
                            ) ?></label>
                    </th>
                    <td>
                        <input name="license_product_id"
                               type="number"
                               id="license-product-id"
                               value="<?= get_site_option(self::LICENSE_PRODUCT_ID) ?>"
                               class="regular-text code"/>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="license-deactivate"><?= esc_html__(
                                'Deactivate license',
                                'backwpup'
                            ) ?></label>
                    </th>
                    <td>
                        <input name="license_deactivate"
                               type="checkbox"
                               id="license-deactivate"/>
                    </td>
                </tr>
                <input type="hidden" name="license_instance_key"
                       value="<?= $instanceKey ?>"/>
                </tbody>
            </table>
        </div>

        <?php
    }
}
