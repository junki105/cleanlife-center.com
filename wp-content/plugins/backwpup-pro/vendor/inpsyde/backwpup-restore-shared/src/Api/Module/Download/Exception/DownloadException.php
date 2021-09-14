<?php # -*- coding: utf-8 -*-

/*
 * This file is part of the BackWPup Restore Shared package.
 *
 * (c) Inpsyde GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Inpsyde\Restore\Api\Module\Download\Exception;

use Inpsyde\Restore\Api\Exception\RestoreExceptionInterface;
use RuntimeException;

/**
 * Class DownloadException
 *
 * @author Guido Scialfa <dev@guidoscialfa.com>
 */
class DownloadException extends RuntimeException implements RestoreExceptionInterface
{

}
