<?php
/**
 * @package     PublishPress\Capabilities\
 * @author      PublishPress <help@publishpress.com>
 * @copyright   Copyright (C) 2018 PublishPress. All rights reserved.
 * @license     GPLv2 or later
 * @since       1.0.3
 */

namespace PublishPress\Capabilities;

defined('ABSPATH') or die('No direct script access allowed.');

/*
if ( ! defined('PP_PERMISSIONS_LOADED')) {
    require_once __DIR__ . '/../includes.php';
}
*/

/**
 * Class Factory
 */
abstract class Factory
{
    /**
     * @var Container
     */
    protected static $container = null;

    /**
     * @return Container
     */
    public static function get_container()
    {
        if (static::$container === null) {
            /*
            if ( ! isset(PublishPress()->permissions)) {
                return false;
            }
            */

            require_once(PUBLISHPRESS_CAPS_ABSPATH . '/includes-pro/library/Services.php');
            $module   = publishpress_caps_pro(); //PublishPress()->permissions;
            $services = new Services($module);

            require_once(PUBLISHPRESS_CAPS_ABSPATH . '/includes-pro/library/Container.php');
            static::$container = new Container();
            static::$container->register($services);
        }

        return static::$container;
    }
}
