<?php
namespace CleanRegex\Internal;

use InvalidArgumentException;

class Arguments
{
    public static function string($variable)
    {
        if (!is_string($variable)) {
            throw new InvalidArgumentException();
        }

        return new Arguments();
    }

    public static function integer($variable)
    {
        if (!is_int($variable)) {
            throw new InvalidArgumentException();
        }
        return new Arguments();
    }

    public static function stringOrNull($variable)
    {
        if (!is_string($variable) && $variable !== null) {
            throw new InvalidArgumentException();
        }

        return new Arguments();
    }
}
