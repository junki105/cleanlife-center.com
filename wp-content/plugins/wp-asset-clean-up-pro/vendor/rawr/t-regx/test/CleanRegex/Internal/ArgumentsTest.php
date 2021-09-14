<?php
namespace CleanRegex\Internal;

use PHPUnit\Framework\TestCase;
use InvalidArgumentException;

class ArgumentsTest extends TestCase
{
    /**
     * @test
     */
    public function shouldValidateString()
    {
        // when
        Arguments::string('text');

        // then
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function shouldNotValidateString()
    {
        // then
        $this->expectException(InvalidArgumentException::class);

        // when
        Arguments::string(2);

        // then
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function shouldValidateInteger()
    {
        // when
        Arguments::integer(2);

        // then
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function shouldNotValidateInteger()
    {
        // then
        $this->expectException(InvalidArgumentException::class);

        // when
        Arguments::integer('text');

        // then
        $this->assertTrue(true);
    }
}
