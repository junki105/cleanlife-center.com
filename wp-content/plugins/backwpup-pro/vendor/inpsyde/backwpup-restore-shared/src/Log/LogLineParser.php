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

use Inpsyde\Assert\Assert;
use InvalidArgumentException;

/**
 * Class LogLineParser
 *
 * @internal
 *
 * @author Guido Scialfa <dev@guidoscialfa.com>
 */
class LogLineParser
{
    private static $regexp = '/^(\[[0-9\-\s\:]+\])\s(restore\.[a-zA-Z\.]+\:)(\s.[^\[\]]*)/';

    /**
     * Extract Data from Log Entry
     *
     * @param $string
     * @return Log
     * @throws InvalidArgumentException
     */
    public function extractData($string)
    {
        Assert::stringNotEmpty($string);

        $matched = preg_match(self::$regexp, $string, $matches);

        // TODO Why this is not covered?
        if (!$matched) {
            return new NullLogData();
        }

        array_shift($matches);
        list($date, $level, $message) = $matches;

        $date = str_replace(array('[', ']'), '', $date);
        $level = strtolower(str_replace(array('restore.', ':'), '', $level));
        $message = trim($message);

        return new LogData($date, $level, $message);
    }
}
