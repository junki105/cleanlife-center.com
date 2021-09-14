<?php
namespace CleanRegex\Internal\Delimiter;

use CleanRegex\Internal\Arguments;

class Delimiterer
{
    /** @var DelimiterParser */
    private $parser;

    public function __construct()
    {
        $this->parser = new DelimiterParser();
    }

    /**
     * @param string $pattern
     * @return string
     */
    public function delimiter($pattern)
    {
        Arguments::string($pattern);

        if ($this->parser->isDelimitered($pattern)) {
            return $pattern;
        }

        return $this->tryDelimiter($pattern);
    }

    private function tryDelimiter($pattern)
    {
        $delimiter = $this->getPossibleDelimiter($pattern);

        if ($delimiter === null) {
            throw new ExplicitDelimiterRequiredException($pattern);
        }

        return $delimiter . $pattern . $delimiter;
    }

    /**
     * @param string $pattern
     * @return null|string
     */
    public function getPossibleDelimiter($pattern)
    {
        foreach ($this->parser->getDelimiters() as $delimiter) {
            if (strpos($pattern, $delimiter) === false) {
                return $delimiter;
            }
        }
        return null;
    }
}
