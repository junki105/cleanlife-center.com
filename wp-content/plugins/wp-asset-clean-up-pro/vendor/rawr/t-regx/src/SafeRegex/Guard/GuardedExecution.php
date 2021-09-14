<?php

namespace SafeRegex\Guard;

use CleanRegex\Internal\Arguments;

class GuardedExecution
{
    /**
     * @param string   $methodName
     * @param callable $callback
     * @return mixed
     * @throws \Exception
     */
    public static function invoke($methodName, callable $callback)
    {
        $invocation = (new GuardedInvoker($methodName, $callback))->catched();
        if ($invocation->hasException()) {
            throw $invocation->getException();
        }
        return $invocation->getResult();
    }

    /**
     * @param string   $methodName
     * @param callable $callback
     * @return GuardedInvocation
     */
    public static function catched($methodName, callable $callback)
    {
        Arguments::string($methodName);

        return (new GuardedInvoker($methodName, $callback))->catched();
    }

    /**
     * @param string   $methodName
     * @param callable $callback
     * @return bool
     */
    public static function silenced($methodName, callable $callback)
    {
        Arguments::string($methodName);

        $invocation = (new GuardedInvoker($methodName, $callback))->catched();
        return $invocation->hasException();
    }
}
