<?php # -*- coding: utf-8 -*-

namespace Inpsyde\BackWPup\Pro\License\Api;

use Inpsyde\BackWPup\Pro\License\ErrorHandler;
use Inpsyde\BackWPup\Pro\License\LicenseInterface;
use Inpsyde\BackWPup\Pro\License\RequestHandler;

class LicenseDeactivation
{
    use RequestHandler;

    const WC_API = 'wc-am-api';
    const WC_API_URL = 'https://backwpup.com/';

    /**
     * @var array
     */
    private $pluginData;

    public function __construct($pluginData)
    {
        $this->pluginData = $pluginData;
    }

    /**
     * @param LicenseInterface $license
     * @return array
     */
    public function deactivate(LicenseInterface $license)
    {
        $args = [
            'wc-api' => self::WC_API,
            'wc_am_action' => 'deactivate',
            'instance' => $license->instanceId(),
            'product_id' => $license->productId(),
            'api_key' => $license->apiKey(),
        ];

        $response = $this->doRequest(self::WC_API_URL, $args);

        if (isset($response->error)) {
            return [
                'error' => $response->error,
                'code' => isset($response->code) ? $response->code : 0,
            ];
        }

        return [
            'deactivated' => isset($response->deactivated) ? $response->deactivated : '',
            'activations_remaining' => isset($response->activations_remaining) ? $response->activations_remaining : '',
        ];
    }
}
