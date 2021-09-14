<?php
namespace SafeRegex;

use CleanRegex\Internal\Arguments;

class PhpError
{
    /** @var int */
    private $type;
    /** @var string */
    private $message;
    /** @var string */
    private $file;
    /** @var int */
    private $line;

    /**
     * @param int    $type
     * @param string $message
     * @param string $file
     * @param int    $line
     */
    public function __construct($type, $message, $file, $line)
    {
        Arguments::integer($type)->string($message)->string($file)->integer($line);

        $this->type = $type;
        $this->message = $message;
        $this->file = $file;
        $this->line = $line;
    }

    /**
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @return int
     */
    public function getLine()
    {
        return $this->line;
    }

    /**
     * @param array $array
     * @return PhpError
     */
    public static function fromArray(array $array)
    {
        return new self($array['type'], $array['message'], $array['file'], $array['line']);
    }

    /**
     * @return null|PhpError
     */
    public static function getLast()
    {
        $error = error_get_last();
        if ($error === null) {
            return null;
        }
        return PhpError::fromArray($error);
    }
}
