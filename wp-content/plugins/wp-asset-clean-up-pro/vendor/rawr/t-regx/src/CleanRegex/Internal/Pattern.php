<?php
namespace CleanRegex\Internal;

use CleanRegex\Internal\Delimiter\Delimiterer;

class Pattern
{
    /** @var string */
    public $pattern;

    /** @var string */
    private $flags;

    /** @var string */
    public $originalPattern;

    /**
     * @param string $pattern
     * @param string $flags
     */
    public function __construct($pattern, $flags = '')
    {
        Arguments::string($pattern)->string($flags);

        $this->pattern = (new Delimiterer())->delimiter($pattern);
        $this->flags = $flags;
        $this->originalPattern = $pattern;
    }
}
