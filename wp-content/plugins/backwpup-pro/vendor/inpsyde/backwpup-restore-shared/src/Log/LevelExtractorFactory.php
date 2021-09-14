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
 * Class LevelExtractorFactory
 *
 * @author Guido Scialfa <dev@guidoscialfa.com>
 */
class LevelExtractorFactory
{
    /**
     * Create instance of LevelExtractor
     *
     * Unfortunately BWU doesn't make use of services so we have to fallback to a Factory
     *
     * @return LevelExtractor
     */
    public function create()
    {
        $fileReader = new FileReader();
        $logLineParser = new LogLineParser();

        return new LevelExtractor($fileReader, $logLineParser);
    }
}
