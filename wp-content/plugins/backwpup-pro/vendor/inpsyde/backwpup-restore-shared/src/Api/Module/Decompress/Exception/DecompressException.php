<?php # -*- coding: utf-8 -*-

/*
 * This file is part of the BackWPup Restore Shared package.
 *
 * (c) Inpsyde GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Inpsyde\Restore\Api\Decompress\Exception;

use RuntimeException;
use Inpsyde\Restore\Api\Exception\RestoreExceptionInterface;

/**
 * Class DecompressException
 *
 * @package Inpsyde\Restore\Api\Decompress
 */
final class DecompressException extends RuntimeException implements RestoreExceptionInterface
{

}
