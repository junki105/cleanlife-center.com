<?php
namespace SafeRegex\Errors\Errors;

class StandardCompileError extends CompileError
{
    public function occurred()
    {
        return $this->getError() !== null;
    }

    public function clear()
    {
        error_clear_last();
    }
}
