<?php # -*- coding: utf-8 -*-

namespace Inpsyde\BackWPup\Pro\License\Api;

use Inpsyde\BackWPup\Pro\License\LicenseInterface;
use Inpsyde\BackWPup\Pro\License\RequestHandler;
use stdClass;

class PluginUpdate
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
     * @param $pluginData
     */
    public function __construct(
        LicenseInterface $license,
        $pluginData
    ) {
        $this->license = $license;
        $this->pluginData = $pluginData;
    }

    /**
     * @param stdClass $transient
     * @return stdClass
     */
    public function execute(stdClass $transient)
    {
        if (!did_action('load-update-core.php')) {
            return $transient;
        }

        if ($this->license->status() !== 'active') {
            return $transient;
        }

        $args = [
            'wc-api' => self::WC_API,
            'wc_am_action' => 'update',
            'instance' => $this->license->instanceId(),
            'api_key' => $this->license->apiKey(),
            'product_id' => $this->license->productId(),
            'version' => $this->pluginData['version'],
            'plugin_name' => $this->pluginData['pluginName'],
            'slug' => $this->pluginData['slug'],
        ];

        $response = $this->doRequest(self::WC_API_URL, $args);

        if (isset($response->error)) {
            return $transient;
        }

        if (isset($response->data->package->new_version)) {
            if (version_compare(
                $response->data->package->new_version,
                $this->pluginData['version'],
                '>'
            )) {
                $pluginBaseName = $this->pluginData['pluginName'];
                $transient->response[$pluginBaseName] = $response->data->package;
            }
        }

        return $transient;
    }
}
