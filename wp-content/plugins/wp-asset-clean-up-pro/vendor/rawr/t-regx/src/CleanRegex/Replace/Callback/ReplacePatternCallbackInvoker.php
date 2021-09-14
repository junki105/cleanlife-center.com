<?php
namespace CleanRegex\Replace\Callback;

use CleanRegex\Exception\Preg\PatternReplaceException;
use SafeRegex\preg;
use CleanRegex\Internal\Arguments;
use CleanRegex\Internal\Pattern as InternalPattern;

class ReplacePatternCallbackInvoker
{
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
     * @param callable $callback
     * @return string
     * @throws PatternReplaceException
     */
    public function invoke(callable $callback)
    {
        $result = $this->performReplaceCallback($callback);

        if ($result === null) {
            throw new PatternReplaceException();
        }

        return $result;
    }

    /**
     * @param callable $callback
     * @return string
     * @throws \SafeRegex\Exception\SafeRegexException
     */
    private function performReplaceCallback(callable $callback)
    {
        $object = new ReplaceCallbackObject($callback, $this->subject, $this->analyzePattern());

        return preg::replace_callback($this->pattern->pattern, $object->getCallback(), $this->subject);
    }

    /**
     * @return array
     * @throws \SafeRegex\Exception\SafeRegexException
     */
    private function analyzePattern()
    {
        $matches = [];
        preg::match_all($this->pattern->pattern, $this->subject, $matches, PREG_OFFSET_CAPTURE);

        return $matches;
    }
}
