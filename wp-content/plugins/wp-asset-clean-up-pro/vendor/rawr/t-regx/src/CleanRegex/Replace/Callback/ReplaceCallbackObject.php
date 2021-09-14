<?php
namespace CleanRegex\Replace\Callback;

use CleanRegex\Internal\Arguments;
use CleanRegex\Match\ReplaceMatch;

class ReplaceCallbackObject
{
    /** @var callable */
    private $callback;
    /** @var string */
    private $subject;
    /** @var array */
    private $analyzedPattern;

    /** @var int */
    private $counter = 0;
    /** @var int */
    private $offsetModification = 0;

    /**
     * @param callable $callback
     * @param string   $subject
     * @param array    $analyzedPattern
     */
    public function __construct(callable $callback, $subject, array $analyzedPattern)
    {
        Arguments::string($subject);

        $this->callback = $callback;
        $this->subject = $subject;
        $this->analyzedPattern = $analyzedPattern;
    }

    public function invoke(array $match)
    {
        $replacement = call_user_func($this->callback, $this->createMatchObject());

        $this->modifyOffset($replacement, $match[0]);

        return $replacement;
    }

    private function createMatchObject()
    {
        return new ReplaceMatch(
            $this->subject,
            $this->counter++,
            $this->analyzedPattern,
            $this->offsetModification
        );
    }

    /**
     * @param string $replacement
     * @param string $search
     */
    public function modifyOffset($replacement, $search)
    {
        $this->offsetModification += strlen($replacement) - strlen($search);
    }

    /**
     * @return callable
     */
    public function getCallback()
    {
        return [$this, 'invoke'];
    }
}
