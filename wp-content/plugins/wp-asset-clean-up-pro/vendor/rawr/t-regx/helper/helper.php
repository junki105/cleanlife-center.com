<?php

if (!function_exists('pattern')) {
    /**
     * @param string $pattern
     * @return \CleanRegex\Pattern
     */
    function pattern($pattern)
    {
        return new \CleanRegex\Pattern($pattern);
    }
}
