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
 * Class NullLogData
 *
 * @author Guido Scialfa <dev@guidoscialfa.com>
 */
class NullLogData implements Log
{
    /**
     * @inheritDoc
     */
    public function date()
    {
        return '';
    }

    /**
     * @inheritDoc
     */
    public function level()
    {
        return '';
    }

    /**
     * @inheritDoc
     */
    public function message()
    {
        return '';
    }
}
