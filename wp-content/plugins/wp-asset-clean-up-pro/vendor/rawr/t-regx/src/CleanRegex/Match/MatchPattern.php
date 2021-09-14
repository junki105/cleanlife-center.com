<?php
namespace CleanRegex\Match;

use CleanRegex\Exception\Preg\PatternMatchException;
use CleanRegex\Internal\Pattern as InternalPattern;
use SafeRegex\preg;
use CleanRegex\Internal\Arguments;
use SafeRegex\Exception\SafeRegexException;

class MatchPattern
{
    const WHOLE_MATCH = 0;

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
        Arguments::string($subject);
        $this->pattern = $pattern;
        $this->subject = $subject;
    }

    /**
     * @return array
     * @throws PatternMatchException
     */
    public function all()
    {
        $matches = [];
        preg::match_all($this->pattern->pattern, $this->subject, $matches);

        return $matches[0];
    }

    /**
     * @param callable $callback
     * @return void
     */
    public function iterate(callable $callback)
    {
        foreach ($this->getMatchObjects() as $object) {
            call_user_func($callback, $object);
        }
    }

    /**
     * @param callable $callback
     * @return array
     */
    public function map(callable $callback)
    {
        $results = [];
        foreach ($this->getMatchObjects() as $object) {
            $results[] = call_user_func($callback, $object);
        }
        return $results;
    }

    /**
     * @param callable|null $callback
     * @return null|string
     */
    public function first(callable $callback = null)
    {
        $matches = $this->performMatchAll();
        if (empty($matches[0])) return null;

        if ($callback !== null) {
            call_user_func($callback, new Match($this->subject, 0, $matches));
        }

        list($value, $offset) = $matches[self::WHOLE_MATCH][0];
        return $value;
    }

    /**
     * @return Match[]
     */
    private function getMatchObjects()
    {
        return $this->constructMatchObjects($this->performMatchAll());
    }

    /**
     * @return array
     */
    private function performMatchAll()
    {
        $matches = [];
        preg::match_all($this->pattern->pattern, $this->subject, $matches, PREG_OFFSET_CAPTURE);

        return $matches;
    }

    /**
     * @param array $matches
     * @return Match[]
     */
    private function constructMatchObjects(array $matches)
    {
        $matchObjects = [];

        foreach ($matches[0] as $index => $match) {
            $matchObjects[] = new Match($this->subject, $index, $matches);
        }

        return $matchObjects;
    }

    /**
     * @return bool
     * @throws SafeRegexException
     */
    public function matches()
    {
        $result = preg::match($this->pattern->pattern, $this->subject);

        return $result === 1;
    }

    /**
     * @return int
     * @throws SafeRegexException
     */
    public function count()
    {
        return preg::match_all($this->pattern->pattern, $this->subject);
    }
}
