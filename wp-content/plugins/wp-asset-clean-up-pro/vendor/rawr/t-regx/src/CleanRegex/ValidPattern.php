<?php
namespace CleanRegex;

use CleanRegex\Exception\CleanRegex\ArgumentNotAllowedException;
use CleanRegex\Internal\Pattern as InternalPattern;
use SafeRegex\Guard\GuardedExecution;

class ValidPattern
{
    /** @var InternalPattern */
    private $pattern;

    public function __construct(InternalPattern $pattern)
    {
        $this->pattern = $pattern;
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        $hadError = GuardedExecution::silenced('preg_match', function () {
            return @preg_match($this->pattern->originalPattern, null);
        });

        return $hadError === false;
    }

    /**
     * @param $argument
     * @return string
     * @throws ArgumentNotAllowedException
     */
    public static function matchableArgument($argument)
    {
        if (is_string($argument)) {
            return $argument;
        }

        if (is_int($argument)) {
            return "$argument";
        }

        if (is_callable([$argument, '__toString'])) {
            return (string)$argument;
        }

        throw new ArgumentNotAllowedException('Argument should be a string, an integer or implement __toString() method!');
    }
}
