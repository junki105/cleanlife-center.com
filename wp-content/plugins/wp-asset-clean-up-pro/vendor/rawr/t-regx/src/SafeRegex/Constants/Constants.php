<?php
namespace SafeRegex\Constants;

use CleanRegex\Internal\Arguments;

abstract class Constants
{
    /**
     * @param int $error
     * @return string
     */
    public function getConstant($error)
    {
        Arguments::integer($error);

        $constants = $this->getConstants();

        if (array_key_exists($error, $constants)) {
            return $constants[$error];
        }

        return $this->getDefault();
    }

    /**
     * @return array
     */
    abstract protected function getConstants();

    /**
     * @return string
     */
    abstract protected function getDefault();
}
