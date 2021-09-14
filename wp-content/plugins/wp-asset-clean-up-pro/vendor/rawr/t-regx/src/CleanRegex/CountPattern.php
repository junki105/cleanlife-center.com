<?php
namespace CleanRegex;

use CleanRegex\Exception\CleanRegex\InternalCleanRegexException;
use CleanRegex\Internal\Pattern as InternalPattern;
use SafeRegex\preg;

class CountPattern
{
    /** @var InternalPattern */
    private $pattern;
    /** @var string */
    private $subject;

    /**
     * @param InternalPattern $pattern
     * @param string  $subject
     */
    public function __construct(InternalPattern $pattern, $subject)
    {
        $this->pattern = $pattern;
        $this->subject = $subject;
    }

    /**
     * @return int
     * @throws InternalCleanRegexException
     */
    public function count()
    {
        $result = preg::match_all($this->pattern->pattern, $this->subject, $matches);
        if ($result !== count($matches[0])) {
            throw new InternalCleanRegexException();
        }
        return $result;
    }
}
