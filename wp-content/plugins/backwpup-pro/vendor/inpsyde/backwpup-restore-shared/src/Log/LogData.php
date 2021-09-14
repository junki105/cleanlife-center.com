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

/**
 * Class LogData
 *
 * @author Guido Scialfa <dev@guidoscialfa.com>
 */
class LogData implements Log
{
    /**
     * @var string
     */
    private $date;

    /**
     * @var string
     */
    private $level;

    /**
     * @var string
     */
    private $message;

    /**
     * LogData constructor
     * @param $date
     * @param $level
     * @param $message
     */
    public function __construct($date, $level, $message)
    {
        Assert::string($date);
        Assert::stringNotEmpty($level);
        Assert::stringNotEmpty($message);

        $this->date = $date;
        $this->level = $level;
        $this->message = $message;
    }

    /**
     * @inheritDoc
     */
    public function date()
    {
        return $this->date;
    }

    /**
     * @inheritDoc
     */
    public function level()
    {
        return $this->level;
    }

    /**
     * @inheritDoc
     */
    public function message()
    {
        return $this->message;
    }
}
