<?php # -*- coding: utf-8 -*-

namespace Inpsyde\BackWPup\Pro\License;

class License implements LicenseInterface
{

    /**
     * @var int
     */
    protected $productId;

    /**
     * @var string
     */
    protected $apiKey;

    /**
     * @var string
     */
    protected $instanceId;

    /**
     * @var string
     */
    protected $status;

    /**
     * @param int $productId
     * @param string $apiKey
     * @param string $instanceId
     * @param string $status
     */
    public function __construct($productId, $apiKey, $instanceId, $status)
    {

        $this->productId = $productId;
        $this->apiKey = $apiKey;
        $this->instanceId = $instanceId;
        $this->status = $status;
    }

    /**
     * @return int
     */
    public function productId()
    {
        return (int)$this->productId;
    }

    /**
     * @return string
     */
    public function apiKey()
    {
        return (string)$this->apiKey;
    }

    /**
     * @link https://docs.woocommerce.com/document/woocommerce-api-manager/#section-52
     * @return string
     */
    public function instanceId()
    {
        return (string)$this->instanceId;
    }

    /**
     * @return string
     */
    public function status()
    {
        return (string)$this->status;
    }
}
