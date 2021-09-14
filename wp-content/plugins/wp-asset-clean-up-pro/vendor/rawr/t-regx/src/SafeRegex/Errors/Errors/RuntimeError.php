<?php
namespace SafeRegex\Errors\Errors;

use CleanRegex\Internal\Arguments;
use SafeRegex\Errors\HostError;
use SafeRegex\Exception\RuntimeSafeRegexException;
use SafeRegex\Exception\SafeRegexException;

class RuntimeError implements HostError
{
    /** @var int */
    private $pregError;

    public function __construct($pregError)
    {
        Arguments::integer($pregError);
        $this->pregError = $pregError;
    }

    /**
     * @return bool
     */
    public function occurred()
    {
        return $this->pregError !== PREG_NO_ERROR;
    }

    /**
     * @return void
     */
    public function clear()
    {
        preg_match('//', '');
    }

    /**
     * @return RuntimeError
     */
    public static function getLast()
    {
        return new RuntimeError(preg_last_error());
    }

    /**
     * @param string $methodName
     * @return SafeRegexException
     */
    public function getSafeRegexpException($methodName)
    {
        return new RuntimeSafeRegexException($methodName, $this->pregError);
    }
}
