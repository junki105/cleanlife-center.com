<?php
namespace SafeRegex;

use CleanRegex\Internal\Arguments;
use SafeRegex\Errors\ErrorsCleaner;
use SafeRegex\Errors\FailureIndicators;
use SafeRegex\Errors\HostError;
use SafeRegex\Exception\SafeRegexException;
use SafeRegex\Exception\SuspectedReturnSafeRegexException;

class ExceptionFactory
{
    /** @var FailureIndicators */
    private $failureIndicators;

    public function __construct()
    {
        $this->failureIndicators = new FailureIndicators();
    }

    /**
     * @param string $methodName
     * @param mixed  $pregResult
     * @return SafeRegexException|null
     */
    public function retrieveGlobals($methodName, $pregResult)
    {
        Arguments::string($methodName);

        return (new ExceptionFactory())->create($methodName, $pregResult, (new ErrorsCleaner())->getError());
    }

    /**
     * @param string         $methodName
     * @param                $pregResult
     * @param null|HostError $hostError
     * @return null|SafeRegexException
     */
    private function create($methodName, $pregResult, HostError $hostError = null)
    {
        Arguments::string($methodName);

        if ($hostError->occurred()) {
            return $hostError->getSafeRegexpException($methodName);
        }

        if ($this->failureIndicators->suspected($methodName, $pregResult)) {
            return new SuspectedReturnSafeRegexException($methodName, $pregResult);
        }

        return null;
    }
}
