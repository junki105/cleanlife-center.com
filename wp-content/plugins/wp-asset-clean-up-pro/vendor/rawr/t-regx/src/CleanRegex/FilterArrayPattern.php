<?php
namespace CleanRegex;

use CleanRegex\Internal\Pattern as InternalPattern;

class FilterArrayPattern
{
    /** @var InternalPattern */
    private $pattern;
    /** @var array */
    private $array;

    public function __construct(InternalPattern $pattern, array $array)
    {
        $this->pattern = $pattern;
        $this->array = $array;
    }

    /**
     * @return array
     */
    public function filter()
    {
        return array_values(array_filter($this->array, function ($element) {
            return (new MatchesPattern($this->pattern, $element))->matches();
        }));
    }
}
