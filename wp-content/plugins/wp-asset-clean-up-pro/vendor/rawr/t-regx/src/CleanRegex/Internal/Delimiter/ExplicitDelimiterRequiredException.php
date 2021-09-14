<?php
namespace CleanRegex\Internal\Delimiter;

use CleanRegex\Exception\CleanRegex\CleanRegexException;
use CleanRegex\Internal\Arguments;

class ExplicitDelimiterRequiredException extends CleanRegexException
{
    /**
     * @param string $pattern
     */
    public function __construct($pattern)
    {
        Arguments::string($pattern);
        parent::__construct($this->getExceptionMessage($pattern));
    }

    private function getExceptionMessage($pattern)
    {
        return "Unfortunately, CleanRegex couldn't find any indistinct delimiter to match your pattern \"$pattern\". " .
            "Please specify the delimiter explicitly, and escape the delimiter character inside your pattern.";
    }
}
