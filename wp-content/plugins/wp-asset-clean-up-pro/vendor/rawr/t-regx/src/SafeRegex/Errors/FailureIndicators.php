<?php
namespace SafeRegex\Errors;

use CleanRegex\Exception\CleanRegex\InternalCleanRegexException;
use CleanRegex\Internal\Arguments;

class FailureIndicators
{
    private $vague = [
        'preg_grep',
        'preg_quote',
    ];

    private $indicators = [
        'preg_match' => false,
        'preg_match_all' => false,
        'preg_replace' => null,
        'preg_filter' => null,
        'preg_replace_callback' => null,
        'preg_replace_callback_array' => null,
        'preg_split' => false,
    ];

    /**
     * @param string $methodName
     * @param        $value
     * @return bool
     */
    public function suspected($methodName, $value)
    {
        Arguments::string($methodName);

        if (in_array($methodName, $this->vague)) {
            return false;
        }

        if (array_key_exists($methodName, $this->indicators)) {
            return $this->indicators[$methodName] === $value;
        }

        throw new InternalCleanRegexException();
    }
}
