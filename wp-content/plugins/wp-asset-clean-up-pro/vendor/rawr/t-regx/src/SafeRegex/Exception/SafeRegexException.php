<?php
namespace SafeRegex\Exception;

use CleanRegex\Internal\Arguments;

abstract class SafeRegexException extends \Exception
{
    /** @var string */
    private $methodName;

    /**
     * @param string      $methodName
     * @param string|null $message
     */
    public function __construct($methodName, $message = null)
    {
        Arguments::string($methodName)->stringOrNull($message);

        parent::__construct($message);
        $this->methodName = $methodName;
    }

    /**
     * @return string
     */
    public function getInvokingMethod()
    {
        return $this->methodName;
    }
}
