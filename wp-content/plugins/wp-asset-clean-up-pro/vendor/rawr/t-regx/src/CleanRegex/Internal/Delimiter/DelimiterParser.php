<?php
namespace CleanRegex\Internal\Delimiter;

use CleanRegex\Internal\Arguments;

class DelimiterParser
{
    /** @var array */
    private $validDelimiters = ['/', '#', '%', '~', '+', '!'];

    /**
     * @param string $pattern
     * @return bool
     */
    public function isDelimitered($pattern)
    {
        Arguments::string($pattern);
        return $this->getDelimiter($pattern) !== null;
    }

    /**
     * @param string $pattern
     * @return null|string
     */
    public function getDelimiter($pattern)
    {
        Arguments::string($pattern);

        if (strlen($pattern) < 2) {
            return null;
        }
        $firstLetter = $pattern[0];
        if ($this->isValidDelimiter($firstLetter)) {
            $lastOffset = strrpos($pattern, $firstLetter);
            if ($lastOffset === 0) {
                return null;
            }

            $flags = substr($pattern, $lastOffset);

            return $firstLetter;
        }

        return null;
    }

    /**
     * @param string $character
     * @return bool
     */
    public function isValidDelimiter($character)
    {
        Arguments::string($character);
        return in_array($character, $this->validDelimiters);
    }

    /**
     * @return array
     */
    public function getDelimiters()
    {
        return $this->validDelimiters;
    }
}
