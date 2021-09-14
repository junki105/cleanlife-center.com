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
 * Class Extractor
 *
 * @author Guido Scialfa <dev@guidoscialfa.com>
 */
class LevelExtractor
{
    const LEVEL_EMERGENCY = 'emergency';
    const LEVEL_ALERT = 'alert';
    const LEVEL_CRITICAL = 'critical';
    const LEVEL_ERROR = 'error';
    const LEVEL_WARNING = 'warning';
    const LEVEL_NOTICE = 'notice';
    const LEVEL_INFO = 'info';
    const LEVEL_DEBUG = 'debug';

    /**
     * @var FileReader
     */
    private $fileReader;

    /**
     * @var LogLineParser
     */
    private $logLineParser;

    /**
     * LevelExtractor constructor
     * @param FileReader $fileReader
     * @param LogLineParser $logLineParser
     */
    public function __construct(FileReader $fileReader, LogLineParser $logLineParser)
    {
        $this->fileReader = $fileReader;
        $this->logLineParser = $logLineParser;
    }

    /**
     * Extract Log Entries for Level Emergency
     *
     * @param SplFileObject $file
     * @return Log[]
     * @throws InvalidArgumentException
     */
    public function extractEmergency(SplFileObject $file)
    {
        return $this->extract($file, self::LEVEL_EMERGENCY);
    }

    /**
     * Extract Log Entries for Level Alert
     *
     * @param SplFileObject $file
     * @return Log[]
     * @throws InvalidArgumentException
     */
    public function extractAlert(SplFileObject $file)
    {
        return $this->extract($file, self::LEVEL_ALERT);
    }

    /**
     * Extract Log Entries for Level Critical
     *
     * @param SplFileObject $file
     * @return Log[]
     * @throws InvalidArgumentException
     */
    public function extractCritical(SplFileObject $file)
    {
        return $this->extract($file, self::LEVEL_CRITICAL);
    }

    /**
     * Extract Log Entries for Level Error
     *
     * @param SplFileObject $file
     * @return Log[]
     * @throws InvalidArgumentException
     */
    public function extractError(SplFileObject $file)
    {
        return $this->extract($file, self::LEVEL_ERROR);
    }

    /**
     * Extract Log Entries for Level Warning
     *
     * @param SplFileObject $file
     * @return Log[]
     * @throws InvalidArgumentException
     */
    public function extractWarning(SplFileObject $file)
    {
        return $this->extract($file, self::LEVEL_WARNING);
    }

    /**
     * Extract Log Entries for Level Notice
     *
     * @param SplFileObject $file
     * @return Log[]
     * @throws InvalidArgumentException
     */
    public function extractNotice(SplFileObject $file)
    {
        return $this->extract($file, self::LEVEL_NOTICE);
    }

    /**
     * Extract Log Entries for Level Info
     *
     * @param SplFileObject $file
     * @return Log[]
     * @throws InvalidArgumentException
     */
    public function extractInfo(SplFileObject $file)
    {
        return $this->extract($file, self::LEVEL_INFO);
    }

    /**
     * Extract Log Entries for Level Debug
     *
     * @param SplFileObject $file
     * @return Log[]
     * @throws InvalidArgumentException
     */
    public function extractDebug(SplFileObject $file)
    {
        return $this->extract($file, self::LEVEL_DEBUG);
    }

    /**
     * Extract the Log Entries equal to the Given Level
     *
     * @param SplFileObject $file
     * @param $level
     * @return Log[]
     * @throws InvalidArgumentException
     */
    private function extract(SplFileObject $file, $level)
    {
        Assert::readable($file->getPathname());
        Assert::stringNotEmpty($level);

        $lines = $this->fileReader->lineByLine($file);

        if (!$lines) {
            return array();
        }

        $logDataList = array();

        foreach ($lines as $line) {
            // TODO May throw InvalidArgumentException
            $logData = $this->logLineParser->extractData($line);
            $logDataList[] = $logData;
        }

        $logDataList = array_filter($logDataList, function (Log $logData) use ($level) {
            return $level === $logData->level();
        });

        return $logDataList;
    }
}
