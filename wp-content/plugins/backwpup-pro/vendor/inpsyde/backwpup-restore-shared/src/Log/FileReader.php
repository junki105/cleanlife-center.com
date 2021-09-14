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
use SplFileObject;

/**
 * Class FileReader
 *
 * @internal
 *
 * @author Guido Scialfa <dev@guidoscialfa.com>
 */
class FileReader
{
    /**
     * Read the Given File Line By Line
     *
     * @param SplFileObject $file
     * @return string[]
     * @throws InvalidArgumentException
     */
    public function lineByLine(SplFileObject $file)
    {
        Assert::readable($file->getPathname());

        $lines = array();

        $file->rewind();

        while (!$file->eof()) {
            $logData = $file->fgets();
            $logData and $lines[] = $logData;
        }

        // Clean Lines
        $lines = array_map(function ($line) {
            return preg_replace('~[\r\n]+~', '', $line);
        }, $lines);
        $lines = array_filter($lines);

        return $lines;
    }
}
