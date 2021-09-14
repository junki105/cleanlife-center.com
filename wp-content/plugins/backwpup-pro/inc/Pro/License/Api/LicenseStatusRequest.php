<?php # -*- coding: utf-8 -*-

namespace Inpsyde\BackWPup\Pro\License\Api;

use Inpsyde\BackWPup\Pro\License\ErrorHandler;
use Inpsyde\BackWPup\Pro\License\LicenseInterface;
use Inpsyde\BackWPup\Pro\License\RequestHandler;

class LicenseStatusRequest
{
    use RequestHandler;

    const WC_API = 'wc-am-api';
    const WC_API_URL = 'https://backwpup.com/';

    /**
     * @param LicenseInterface $license
     * @return string
     */
    public function requestStatusFor(LicenseInterface $license)
    {
        $args = [
            'wc-api' => self::WC_API,
            'wc_am_action' => 'status',
            'instance' => $license->instanceId(),
            'product_id' => $license->productId(),
            'api_key' => $license->apiKey(),
        ];

        $response = $this->doRequest(self::WC_API_URL, $args);

        return isset($response->status_check) ? $response->status_check : '';
    }
}
