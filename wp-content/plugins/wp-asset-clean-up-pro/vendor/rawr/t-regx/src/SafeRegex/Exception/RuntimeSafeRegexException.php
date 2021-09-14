<?php
namespace SafeRegex\Exception;

use CleanRegex\Internal\Arguments;
use SafeRegex\Constants\PregConstants;

class RuntimeSafeRegexException extends SafeRegexException
{
    /** @var int */
    private $errorCode;

    /**
     * @param string $methodName
     * @param int    $errorCode
     */
    public function __construct($methodName, $errorCode)
    {
        Arguments::string($methodName)->integer($errorCode);

        $this->errorCode = $errorCode;
        parent::__construct("After invoking $methodName(), preg_last_error() returned " . self::getErrorName() . ".");
    }

    /**
     * @return int
     */
    public function getError()
    {
        return $this->errorCode;
    }

    /**
     * @return string
     */
    public function getErrorName()
    {
        return (new PregConstants())->getConstant($this->errorCode);
    }
}
