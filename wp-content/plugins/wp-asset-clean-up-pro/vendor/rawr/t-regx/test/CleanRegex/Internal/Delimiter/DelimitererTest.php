<?php
namespace Test\CleanRegex\Internal\Delimiter;

use CleanRegex\Internal\Delimiter\Delimiterer;
use CleanRegex\Internal\Delimiter\ExplicitDelimiterRequiredException;
use PHPUnit\Framework\TestCase;

class DelimitererTest extends TestCase
{
    public function patternsAndResults()
    {
        return [
            ['siema', '/siema/'],
            ['sie#ma', '/sie#ma/'],
            ['sie/ma', '#sie/ma#'],
            ['si/e#ma', '%si/e#ma%'],
            ['si/e#m%a', '~si/e#m%a~'],
            ['s~i/e#m%a', '+s~i/e#m%a+'],
            ['s~i/e#++m%a', '!s~i/e#++m%a!'],
        ];
    }

    /**
     * @test
     * @dataProvider patternsAndResults
     * @param string $pattern
     * @param string $expectedResult
     */
    public function shouldDelimiterPattern($pattern, $expectedResult)
    {
        // given
        $delimiterer = new Delimiterer();

        // when
        $result = $delimiterer->delimiter($pattern);

        // then
        $this->assertEquals($expectedResult, $result);
    }

    public function alreadyDelimitered()
    {
        return [
            ['/a/'],
            ['#a#'],
            ['%a%'],
            ['~a~'],
            ['+a+'],
            ['!a!'],
        ];
    }

    /**
     * @test
     * @dataProvider alreadyDelimitered
     * @param string $pattern
     */
    public function shouldDelimiterAlreadyDelimitered($pattern)
    {
        // given
        $delimiterer = new Delimiterer();

        // when
        $result = $delimiterer->delimiter($pattern);

        // then
        $this->assertEquals($pattern, $result);
    }

    /**
     * @test
     */
    public function shouldThrowOnNotEnoughDelimiters()
    {
        // given
        $delimiterer = new Delimiterer();

        // then
        $this->expectException(ExplicitDelimiterRequiredException::class);

        // when
        $delimiterer->delimiter('s~i/e#++m%a!');
    }
}
