<?php
namespace CleanRegex;

use CleanRegex\Exception\CleanRegex\FlagNotAllowedException;
use CleanRegex\Exception\CleanRegex\InternalCleanRegexException;
use SafeRegex\Exception\SafeRegexException;
use SafeRegex\preg;
use CleanRegex\Internal\Arguments;

class FlagsValidator
{
    private $flags = [
        'i', // PCRE_CASELESS
        'm', // PCRE_MULTILINE
        'x', // PCRE_EXTENDED
        's', // PCRE_DOTALL

        'U', // PCRE_UNGREEDY
        'X', // PCRE_EXTRA
        'A', // PCRE_ANCHORED
        'D', // PCRE_DOLLAR_ENDONLY
        'S', // Studying a pattern, before executing
    ];

    /**
     * @param string $flags
     * @return void
     * @throws FlagNotAllowedException
     */
    public function validate($flags)
    {
        Arguments::string($flags);

        if (empty($flags)) {
            return;
        }

        if ($this->containWhitespace($flags)) {
            throw new FlagNotAllowedException("Flags cannot contain whitespace");
        }

        $this->validateFlags($flags);
    }

    /**
     * @param string $flags
     * @return bool
     */
    private function containWhitespace($flags)
    {
        try {
            return preg::match('/\s/', $flags) === 1;
        } catch (SafeRegexException $exception) {
            throw new InternalCleanRegexException();
        }
    }

    /**
     * @param string $flags
     * @return void
     * @throws FlagNotAllowedException
     */
    private function validateFlags($flags)
    {
        foreach (str_split($flags) as $flag) {
            if (!$this->isAllowed($flag)) {
                throw new FlagNotAllowedException("Regular expression flag '$flag' is not allowed");
            }
        }
    }

    /**
     * @param string $character
     * @return bool
     */
    private function isAllowed($character)
    {
        return in_array($character, $this->flags);
    }
}
