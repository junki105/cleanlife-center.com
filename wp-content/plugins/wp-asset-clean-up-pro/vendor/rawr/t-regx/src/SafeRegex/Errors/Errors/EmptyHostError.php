<?php
namespace SafeRegex\Errors\Errors;

use SafeRegex\Errors\HostError;

class EmptyHostError implements HostError
{
    public function occurred()
    {
        return false;
    }

    public function clear()
    {
    }

    public function getSafeRegexpException($methodName)
    {
        return null;
    }
}
