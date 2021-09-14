<?php
namespace CleanRegex\Match;

use CleanRegex\Exception\CleanRegex\NonexistentGroupException;
use InvalidArgumentException;
use CleanRegex\Internal\Arguments;

class Match
{
    const WHOLE_MATCH = 0;

    /** @var string */
    protected $subject;
    /** @var int */
    protected $index;
    /** @var array */
    protected $matches;

    /**
     * @param string $subject
     * @param int    $index
     * @param array  $matches
     */
    public function __construct($subject, $index, array $matches)
    {
        Arguments::string($subject)->integer($index);

        $this->subject = $subject;
        $this->index = $index;
        $this->matches = $matches;
    }

    /**
     * @return string
     */
    public function subject()
    {
        return $this->subject;
    }

    /**
     * @return int
     */
    public function index()
    {
        return $this->index;
    }

    /**
     * @return string
     */
    public function match()
    {
        list($match, $offset) = $this->matches[self::WHOLE_MATCH][$this->index];
        return $match;
    }

    /**
     * @param string|int $nameOrIndex
     * @return string
     * @throws NonexistentGroupException
     */
    public function group($nameOrIndex)
    {
        $this->validateGroupName($nameOrIndex);

        if ($this->hasGroup($nameOrIndex)) {
            list($match, $offset) = $this->matches[$nameOrIndex][$this->index];
            return $match;
        }

        throw new NonexistentGroupException();
    }

    /**
     * @return string[]
     */
    public function namedGroups()
    {
        $namedGroups = [];

        foreach ($this->matches as $groupNameOrIndex => $match) {
            if (is_string($groupNameOrIndex)) {
                list($value, $offset) = $match[$this->index];
                $namedGroups[$groupNameOrIndex] = $value;
            }
        }

        return $namedGroups;
    }

    /**
     * @return string[]
     */
    public function groupNames()
    {
        return array_values(array_filter(array_keys($this->matches), function ($key) {
            return is_string($key);
        }));
    }

    /**
     * @param string|int $nameOrIndex
     * @return bool
     */
    public function hasGroup($nameOrIndex)
    {
        $this->validateGroupName($nameOrIndex);

        return array_key_exists($nameOrIndex, $this->matches);
    }

    /**
     * @param string|int $nameOrIndex
     * @return bool
     */
    public function matched($nameOrIndex)
    {
        return $this->matches[$nameOrIndex][$this->index] !== '';
    }

    /**
     * @return string[]
     */
    public function all()
    {
        return array_map(function ($match) {
            list($value, $offset) = $match;
            return $value;
        }, $this->matches[self::WHOLE_MATCH]);
    }

    /**
     * @return int
     */
    public function offset()
    {
        list($value, $offset) = $this->matches[self::WHOLE_MATCH][$this->index];
        return $offset;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->match();
    }

    private function validateGroupName($nameOrIndex)
    {
        if (!is_string($nameOrIndex) && !is_int($nameOrIndex)) {
            throw new InvalidArgumentException("Group index can only be an integer or string");
        }
    }
}
