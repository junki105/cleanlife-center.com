<?php # -*- coding: utf-8 -*-

namespace Inpsyde\BackWPup\Pro\License\Api;

use Inpsyde\BackWPup\Pro\License\ErrorHandler;
use Inpsyde\BackWPup\Pro\License\LicenseInterface;
use Inpsyde\BackWPup\Pro\License\RequestHandler;

class LicenseActivation
{
    use RequestHandler;

    const WC_API = 'wc-am-api';
    const WC_API_URL = 'https://backwpup.com/';

    /**
     * @var array
     */
    protected $pluginData;

    public function __construct($pluginData)
    {
        $this->pluginData = $pluginData;
    }

    /**
     * @param LicenseInterface $license
     * @return array
     */
    public function activate(LicenseInterface $license)
    {
        $args = [
            'wc-api' => self::WC_API,
            'wc_am_action' => 'activate',
            'instance' => $license->instanceId(),
            'product_id' => $license->productId(),
            'api_key' => $license->apiKey(),
            'object' => str_ireplace(['http://', 'https://'], '', home_url()),
            'version' => $this->pluginData['version'],
        ];

        $response = $this->doRequest(self::WC_API_URL, $args);

        if (isset($response->error)) {
            return [
                'error' => $response->error,
                'code' => isset($response->code) ? $response->code : 0,
            ];
        }

        return [
            'activated' => isset($response->activated) ? $response->activated : '',
            'message' => isset($response->message) ? $response->message : '',
        ];
    }
}
