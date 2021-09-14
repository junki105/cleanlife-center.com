<?php
namespace SafeRegex\Errors\Errors;

use SafeRegex\Errors\HostError;

class BothHostError implements HostError
{
    /** @var CompileError */
    private $compile;
    /** @var RuntimeError */
    private $runtime;

    public function __construct(CompileError $compileError, RuntimeError $runtimeError)
    {
        $this->compile = $compileError;
        $this->runtime = $runtimeError;
    }

    public function occurred()
    {
        return $this->compile->occurred() || $this->runtime->occurred();
    }

    public function clear()
    {
        $this->compile->occurred() && $this->compile->clear();
        $this->runtime->occurred() && $this->runtime->clear();
    }

    public function getSafeRegexpException($methodName)
    {
        return $this->compile->getSafeRegexpException($methodName);
    }
}
