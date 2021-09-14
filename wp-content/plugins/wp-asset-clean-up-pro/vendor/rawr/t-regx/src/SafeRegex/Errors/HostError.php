<?php
namespace SafeRegex\Errors;

use SafeRegex\Exception\SafeRegexException;

interface HostError
{
    /**
     * @return bool
     */
    public function occurred();

    /**
     * @return void
     */
    public function clear();

    /**
     * @param string $methodName
     * @return null|SafeRegexException
     */
    public function getSafeRegexpException($methodName);
}
