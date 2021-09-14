<?php
/*
 * This file is part of the Inpsyde BackWpUp package.
 *
 * (c) Inpsyde GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Inpsyde\Restore;

/**
 * Class DestinationFactory
 *
 * @package Inpsyde\Restore
 */
class DestinationFactory
{

    /**
     * Destination
     *
     * @var string The destination identifier
     */
    private $destination;

    /**
     * Class Prefix
     *
     * @var string The class prefix. The part before the destination
     */
    private static $prefix = 'BackWPup_Destination_';

    /**
     * Class Prefix for Pro Classes
     *
     * @since 3.5.0
     *
     * @var string The class prefix for pro class
     */
    private static $pro_prefix = 'BackWPup_Pro_Destination_';

    /**
     * BackWPup_Destination_Factory constructor
     *
     * @param string $destination The destination name.
     */
    public function __construct($destination)
    {
        $this->destination = $destination;
    }

    /**
     * Create
     *
     * Creates the specified destination object
     *
     * @return \RuntimeException
     */
    public function create()
    {
        // Build the class name.
        $class = self::$prefix . $this->destination;

        // If class doesn't exists, try within the Pro directory.
        if (!class_exists($class)) {
            $class = str_replace(self::$prefix, self::$pro_prefix, $class);
        }

        if (!class_exists($class)) {
            throw new \RuntimeException(
                sprintf(
                    'No way to instantiate class %s. Class doesn\'t exists.',
                    $class
                )
            );
        }

        return new $class();
    }
}
