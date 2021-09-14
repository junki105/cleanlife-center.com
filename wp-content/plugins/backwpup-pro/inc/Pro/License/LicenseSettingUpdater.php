<?php # -*- coding: utf-8 -*-

namespace Inpsyde\BackWPup\Pro\License;

use Inpsyde\BackWPup\Pro\License\Api\LicenseActivation;
use Inpsyde\BackWPup\Pro\License\Api\LicenseDeactivation;
use Inpsyde\BackWPup\Pro\License\Api\LicenseStatusRequest;
use Inpsyde\BackWPup\Settings\SettingUpdatable;

class LicenseSettingUpdater implements SettingUpdatable
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
    public function update()
    {
        $licenseInstanceKey = filter_input(
            INPUT_POST,
            self::LICENSE_INSTANCE_KEY,
            FILTER_SANITIZE_STRING
        );
        $licenseApiKey = filter_input(INPUT_POST, self::LICENSE_API_KEY, FILTER_SANITIZE_STRING);
        $licenseProductId = filter_input(INPUT_POST,
            self::LICENSE_PRODUCT_ID, FILTER_SANITIZE_STRING);
        $licenseDeactivate = filter_input(INPUT_POST, 'license_deactivate', FILTER_SANITIZE_STRING);

        update_site_option(self::LICENSE_INSTANCE_KEY, $licenseInstanceKey);
        update_site_option(self::LICENSE_API_KEY, $licenseApiKey);
        update_site_option(self::LICENSE_PRODUCT_ID, $licenseProductId);

        $license = new License(
            $licenseProductId,
            $licenseApiKey,
            $licenseInstanceKey,
            get_site_option(self::LICENSE_STATUS, 'inactive')
        );

        $licenseDeactivate === 'on'
            ? $response = $this->deactivate->deactivate($license)
            : $response = $this->activate->activate($license);

        if(isset($response['activated'])) {
            $message = $response['activated'] === true ? _x('Activated', 'License', 'backwpup') . ' | ' : '';
            $message .= isset($response['message']) ? $response['message'] : '';
            \BackWPup_Admin::message($message);
        }

        if(isset($response['deactivated'])) {
            $message = $response['deactivated'] === true ? _x('Deactivated', 'License', 'backwpup') . ' | ' : '';
            $message .= isset($response['activations_remaining']) ? $response['activations_remaining'] : '';
            \BackWPup_Admin::message($message);
        }

        if(isset($response['error'])) {
            \BackWPup_Admin::message($response['error'], true);
        }
    }

    public function reset()
    {
        delete_site_option(self::LICENSE_INSTANCE_KEY);
        delete_site_option(self::LICENSE_API_KEY);
        delete_site_option(self::LICENSE_PRODUCT_ID);
        delete_site_option(self::LICENSE_STATUS);
    }
}
