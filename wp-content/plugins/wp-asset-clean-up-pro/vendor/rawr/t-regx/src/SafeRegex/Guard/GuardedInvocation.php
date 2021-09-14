<?php

namespace SafeRegex\Guard;

use Exception;

class GuardedInvocation
{
    /** @var mixed */
    private $result;
    /** @var Exception|null */
    private $exception;

    /**
     * @param mixed          $result
     * @param Exception|null $exception
     */
    public function __construct($result, Exception $exception = null)
    {
        $this->result = $result;
        $this->exception = $exception;
    }

    /**
     * @return Exception|null
     */
    public function getException()
    {
        return $this->exception;
    }

    /**
     * @return bool
     */
    public function hasException()
    {
        return $this->exception !== null;
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }
}
