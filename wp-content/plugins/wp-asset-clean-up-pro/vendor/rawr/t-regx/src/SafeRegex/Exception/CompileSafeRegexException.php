<?php
namespace SafeRegex\Exception;

use CleanRegex\Internal\Arguments;
use SafeRegex\Constants\PhpErrorConstants;
use SafeRegex\PhpError;

class CompileSafeRegexException extends SafeRegexException
{
    /** @var PhpError */
    private $error;

    /**
     * @param string   $methodName
     * @param PhpError $error
     */
    public function __construct($methodName, PhpError $error)
    {
        Arguments::string($methodName);
        $this->error = $error;

        parent::__construct($methodName, $this->formatMessage());
    }

    private function formatMessage()
    {
        return $this->getPregErrorMessage() . PHP_EOL . ' ' . PHP_EOL . '(caused by ' . $this->getErrorName() . ')';
    }

    /**
     * @return int
     */
    public function getError()
    {
        return $this->error->getType();
    }

    /**
     * @return string
     */
    public function getErrorName()
    {
        return (new PhpErrorConstants())->getConstant($this->error->getType());
    }

    /**
     * @return string
     */
    public function getPregErrorMessage()
    {
        return $this->error->getMessage();
    }
}
