<?php

namespace SafeRegex\Guard;

class Arrays
{
    /**
     * @param array $array1
     * @param array $array2
     * @return bool
     */
    public static function equal(array $array1, array $array2)
    {
        return !array_diff_key($array1, $array2) && !array_diff_key($array2, $array1);
    }
}
