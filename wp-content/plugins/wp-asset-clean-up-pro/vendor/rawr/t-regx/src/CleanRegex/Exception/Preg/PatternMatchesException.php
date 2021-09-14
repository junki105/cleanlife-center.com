<?php
namespace CleanRegex\Exception\Preg;

class PatternMatchesException extends PregException
{
    /** @var int */
    private $lastError;

    /**
     * @param int $lastError
     */
    public function __construct($lastError)
    {
        parent::__construct("Last error code: $lastError");
        $this->lastError = $lastError;
    }
}
