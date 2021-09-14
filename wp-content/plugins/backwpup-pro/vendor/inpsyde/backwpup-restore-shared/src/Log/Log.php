<?php # -*- coding: utf-8 -*-

/*
 * This file is part of the BackWPup Restore Shared package.
 *
 * (c) Guido Scialfa <dev@guidoscialfa.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Inpsyde\Restore\Log;

/**
 * Class Log
 *
 * @author Guido Scialfa <dev@guidoscialfa.com>
 */
interface Log
{
    /**
     * Formatted Date of When the Log was Written
     *
     * @return string
     */
    public function date();

    /**
     * Log Level
     *
     * @return string
     */
    public function level();

    /**
     * Log Message
     *
     * @return string
     */
    public function message();
}
