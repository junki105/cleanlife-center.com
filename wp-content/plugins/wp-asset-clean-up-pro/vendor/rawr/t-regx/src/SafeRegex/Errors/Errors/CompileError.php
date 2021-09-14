<?php
namespace SafeRegex\Errors\Errors;

use SafeRegex\Errors\HostError;
use SafeRegex\Exception\CompileSafeRegexException;
use SafeRegex\Exception\SafeRegexException;
use SafeRegex\PhpError;

abstract class CompileError implements HostError
{
    /** @var PhpError|null */
    private $error;

    public function __construct(PhpError $error = null)
    {
        $this->error = $error;
    }

    /**
     * @return null|PhpError
     */
    protected function getError()
    {
        return $this->error;
    }

    /**
     * @param string $methodName
     * @return SafeRegexException
     */
    public function getSafeRegexpException($methodName)
    {
        return new CompileSafeRegexException($methodName, $this->error);
    }

    /**
     * @return CompileError
     */
    public static function getLast()
    {
        $phpError = PhpError::getLast();

        if (is_callable('error_clear_last')) {
            return new StandardCompileError($phpError);
        }

        return new OvertriggerCompileError($phpError);
    }
}
