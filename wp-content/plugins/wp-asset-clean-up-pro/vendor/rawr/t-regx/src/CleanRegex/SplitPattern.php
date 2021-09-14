<?php
namespace CleanRegex;

use CleanRegex\Internal\Arguments;
use CleanRegex\Internal\Pattern as InternalPattern;
use SafeRegex\Exception\SafeRegexException;
use SafeRegex\preg;

class SplitPattern
{
    /** @var InternalPattern */
    private $pattern;

    /** @var string */
    private $subject;

    /**
     * @param InternalPattern $pattern
     * @param string          $subject
     */
    public function __construct(InternalPattern $pattern, $subject)
    {
        Arguments::string($subject);

        $this->pattern = $pattern;
        $this->subject = $subject;
    }

    /**
     * @return array
     * @throws SafeRegexException
     */
    public function split()
    {
        return preg::split($this->pattern->pattern, $this->subject);
    }

    /**
     * @return array
     * @throws SafeRegexException
     */
    public function separate()
    {
        return preg::split($this->pattern->pattern, $this->subject, -1, PREG_SPLIT_DELIM_CAPTURE);
    }
}
