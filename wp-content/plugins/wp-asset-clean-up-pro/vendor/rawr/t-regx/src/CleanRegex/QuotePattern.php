<?php
namespace CleanRegex;

use CleanRegex\Internal\Pattern as InternalPattern;
use SafeRegex\preg;

class QuotePattern
{
    /** @var InternalPattern */
    private $pattern;

    public function __construct(InternalPattern $pattern)
    {
        $this->pattern = $pattern;
    }

    /**
     * @return string
     * @throws \SafeRegex\Exception\SafeRegexException
     */
    public function quote()
    {
        return preg::quote($this->pattern->originalPattern);
    }
}
