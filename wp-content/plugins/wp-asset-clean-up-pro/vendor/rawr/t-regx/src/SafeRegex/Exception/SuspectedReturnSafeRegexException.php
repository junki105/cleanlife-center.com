<?php
namespace SafeRegex\Exception;

class SuspectedReturnSafeRegexException extends SafeRegexException
{
    /** @var mixed */
    private $returnValue;

    /**
     * @param string      $methodName
     * @param null|string $returnValue
     */
    public function __construct($methodName, $returnValue)
    {
        parent::__construct("Invoking $methodName() resulted in 'false'.");
        $this->returnValue = $returnValue;
    }
}
