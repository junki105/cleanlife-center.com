<?php # -*- coding: utf-8 -*-

/*
 * This file is part of the php5.3-backcompact package.
 *
 * (c) Guido Scialfa <dev@guidoscialfa.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

if (class_exists('JsonSerializable')) {
    return;
}

/**
 * Class JsonSerializable
 *
 * @author Guido Scialfa <dev@guidoscialfa.com>
 */
interface JsonSerializable
{
    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize();
}
