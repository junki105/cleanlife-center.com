<?php
namespace Test;

class ClassWithToString
{
    /** @var string */
    private $text;

    /**
     * @param string $text
     */
    public function __construct($text)
    {
        $this->text = $text;
    }

    public function __toString()
    {
        return $this->text;
    }
}
