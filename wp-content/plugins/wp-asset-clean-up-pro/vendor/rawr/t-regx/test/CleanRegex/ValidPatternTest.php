<?php
namespace Test\CleanRegex;

use CleanRegex\Internal\Pattern as InternalPattern;
use CleanRegex\ValidPattern;
use PHPUnit\Framework\TestCase;

class ValidPatternTest extends TestCase
{
    /**
     * @test
     * @dataProvider validPatterns
     * @param string $string
     */
    public function shouldValidatePattern( $string)
    {
        // given
        $pattern = new ValidPattern(new InternalPattern($string));

        // when
        $isValid = $pattern->isValid();

        // then
        $this->assertTrue($isValid, "Failed asserting that pattern is valid");
    }

    public function validPatterns()
    {
        return [
            ['~((https?|ftp)://(\S*?\.\S*?))([\s)\[\]{},;"\':<]|\.\s | $)~'],
            ['!exclamation marks!'],
        ];
    }

    /**
     * @test
     * @dataProvider \Test\DataProviders::invalidPregPatterns()
     * @param string $string
     */
    public function shouldNotValidatePattern( $string)
    {
        // given
        $pattern = new ValidPattern(new InternalPattern($string));

        // when
        $isValid = $pattern->isValid();

        // then
        $this->assertFalse($isValid, "Failed asserting that pattern is invalid");
    }
}
