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

use Exception;
use Inpsyde\BackWPup\Archiver\CurrentExtractInfo;
use Inpsyde\Restore\Api\Module\Registry;

/**
 * Class StateUpdater
 *
 * @author Guido Scialfa <dev@guidoscialfa.com>
 */
class StateUpdater
{
    /**
     * @var Registry
     */
    private $registry;

    /**
     * StateUpdater constructor
     * @param Registry $registry
     */
    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * Update Status
     *
     * @param CurrentExtractInfo $data
     */
    public function updateStatus(CurrentExtractInfo $data)
    {
        $remains = $data->remains;
        $index = $data->index;
        $state = $index === $remains ? State::STATUS_DONE : State::STATUS_PROGRESS;

        $this->saveStatus(
            array(
                State::KEY_FILENAME => $data->fileName,
                State::KEY_INDEX => $index,
                State::KEY_STATE => $state,
                State::KEY_FILES_COUNTER => $remains,
            )
        );
    }

    /**
     * Clean Registry Decompression state
     *
     * @return void
     */
    public function clean()
    {
        $this->registry->decompression_state = array();
    }

    /**
     * Set the current state within the registry
     *
     * @param array $args The arguments to store into the registry.
     *
     * @throws Exception If the registry cannot be saved.
     */
    private function saveStatus(array $args)
    {
        // TODO Assert not empty args

        $defaults = array(
            State::KEY_FILENAME => '',
            State::KEY_INDEX => 0,
            State::KEY_STATE => 'unknown',
            State::KEY_FILES_COUNTER => 0,
        );

        $status = array_merge($defaults, $args);
        $this->registry->decompression_state = $status;
    }
}
