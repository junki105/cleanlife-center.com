<?php

namespace Inpsyde\BackWPup\Pro\License;

interface LicenseInterface
{
    /**
     * @return int
     */
    public function productId();

    /**
     * @return string
     */
    public function apiKey();

    /**
     * @return string
     */
    public function instanceId();

    /**
     * @return string
     */
    public function status();
}
