<?php # -*- coding: utf-8 -*-

namespace Inpsyde\BackWPup\Pro\License\Api;

use Inpsyde\BackWPup\Pro\License\LicenseInterface;
use Inpsyde\BackWPup\Pro\License\RequestHandler;
use stdClass;

class PluginInformation
{
    use RequestHandler;

    const WC_API = 'wc-am-api';
    const WC_API_URL = 'https://backwpup.com/';

    /** @var LicenseInterface */
    private $license;

    /** @var array */
    private $pluginData;

    /**
     * @param LicenseInterface $license
     * @param array $pluginData
     */
    public function __construct(
        LicenseInterface $license,
        $pluginData
    ) {
        $this->license = $license;
        $this->pluginData = $pluginData;
    }

    /**
     * @param bool $result
     * @param string $action
     * @param stdClass $args
     * @return bool
     */
    public function execute($result, $action, stdClass $args)
    {
        if ($action !== 'plugin_information'
            || $args->slug !== $this->pluginData['slug']) {
            return $result;
        }

        if ($this->license->status() !== 'active') {
            return $result;
        }

        $args = [
            'wc-api' => self::WC_API,
            'wc_am_action' => 'information',
            'instance' => $this->license->instanceId(),
            'api_key' => $this->license->apiKey(),
            'product_id' => $this->license->productId(),
            'version' => $this->pluginData['version'],
            'plugin_name' => $this->pluginData['pluginName'],
        ];

        $response = $this->doRequest(self::WC_API_URL, $args);

        if (isset($response->error)) {
            return $result;
        }

        $response->data->info->sections = (array)$response->data->info->sections;

        return $response->data->info;
    }
}
