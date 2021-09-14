<?php
namespace CleanRegex;

use CleanRegex\Internal\Pattern as InternalPattern;
use SafeRegex\Exception\SafeRegexException;
use SafeRegex\preg;

class MatchesPattern
{
    /** @var InternalPattern */
    private $pattern;
    /** @var string */
    private $subject;

    /**
     * @param InternalPattern  $pattern
     * @param string|int|mixed $subject
     */
    public function __construct(InternalPattern $pattern, $subject)
    {
        $this->pattern = $pattern;
        $this->subject = $subject;
    }

    /**
     * @return bool
     * @throws Exception\CleanRegex\ArgumentNotAllowedException
     * @throws SafeRegexException
     */
    public function matches()
    {
        $argument = ValidPattern::matchableArgument($this->subject);
        $result = preg::match($this->pattern->pattern, $argument);

        return $result === 1;
    }
}
