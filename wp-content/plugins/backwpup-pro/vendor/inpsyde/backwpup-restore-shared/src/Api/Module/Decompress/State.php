<?php # -*- coding: utf-8 -*-

/*
 * This file is part of the BackWPup Restore Shared package.
 *
 * (c) Inpsyde GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Inpsyde\Restore\Api\Module\Decompress;

use Inpsyde\Restore\Api\Module\Registry;

/**
 * Class State
 *
 * TODO Class Hold State, but it's contains `clean` command method, may be the class
 *      have to be split into two different classes because of concerns.
 *
 * @author Guido Scialfa <dev@guidoscialfa.com>
 */
class State
{
    const STATUS_DONE = 'done';
    const STATUS_PROGRESS = 'progress';
    const STATUS_DEFAULT = 'unknown';

    const KEY_FILENAME = 'filename';
    const KEY_INDEX = 'index';
    const KEY_STATE = 'state';
    const KEY_FILES_COUNTER = 'files_counter';

    /**
     * @var Registry
     */
    private $registry;

    /**
     * State constructor
     * @param Registry $registry
     */
    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * Get the Index
     *
     * @return int
     */
    public function index()
    {
        return $this->stateProperty(self::KEY_INDEX, -1);
    }

    /**
     * Get File Name
     *
     * @return string
     */
    public function fileName()
    {
        return $this->stateProperty(self::KEY_FILENAME, '');
    }

    /**
     * Get the State
     *
     * @return string
     */
    public function state()
    {
        return $this->stateProperty(self::KEY_STATE, '');
    }

    // TODO Introduce Files Counter.

    /**
     * Retrieve the Property Decompression State or default value
     *
     * @param $property
     * @param $default
     * @return mixed
     */
    private function stateProperty($property, $default)
    {
        $state = $this->registry->decompression_state;

        return isset($state[$property]) ? $state[$property] : $default;
    }
}
