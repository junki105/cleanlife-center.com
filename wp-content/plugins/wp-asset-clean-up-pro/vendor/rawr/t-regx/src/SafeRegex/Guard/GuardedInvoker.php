<?php
namespace SafeRegex\Guard;

use CleanRegex\Internal\Arguments;
use SafeRegex\Errors\ErrorsCleaner;
use SafeRegex\ExceptionFactory;

class GuardedInvoker
{
    /** @var callable */
    private $callback;
    /** @var string */
    private $methodName;

    /**
     * @param string   $methodName
     * @param callable $callback
     */
    public function __construct($methodName, callable $callback)
    {
        Arguments::string($methodName);

        $this->callback = $callback;
        $this->methodName = $methodName;
    }

    /**
     * @return GuardedInvocation
     */
    public function catched()
    {
        $this->clearObsoleteCompileAndRuntimeErrors();

        $result = call_user_func($this->callback);

        return new GuardedInvocation($result, $this->exception($result));
    }

    private function clearObsoleteCompileAndRuntimeErrors()
    {
        (new ErrorsCleaner())->clear();
    }

    private function exception($result)
    {
        return (new ExceptionFactory())->retrieveGlobals($this->methodName, $result);
    }
}
