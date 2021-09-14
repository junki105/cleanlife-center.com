<?php
namespace Test;

trait Warnings
{
    public function causeRuntimeWarning()
    {
        @preg_match('/pattern/u', "\xc3\x28");
    }

    public function causeCompileWarning()
    {
        @preg_match('/unclosed pattern', '');
    }
}
