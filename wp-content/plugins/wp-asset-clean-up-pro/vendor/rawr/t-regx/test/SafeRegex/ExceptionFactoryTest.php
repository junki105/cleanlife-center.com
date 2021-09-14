<?php
namespace Test\SafeRegex;

use PHPUnit\Framework\TestCase;
use SafeRegex\Errors\ErrorsCleaner;
use SafeRegex\Exception\CompileSafeRegexException;
use SafeRegex\Exception\RuntimeSafeRegexException;
use SafeRegex\Exception\SuspectedReturnSafeRegexException;
use SafeRegex\ExceptionFactory;

class ExceptionFactoryTest extends TestCase
{
    protected function setUp()
    {
        (new ErrorsCleaner())->clear();
    }

    /**
     * @test
     * @dataProvider \Test\DataProviders::invalidPregPatterns()
     * @param string $invalidPattern
     */
    public function testPregErrors($invalidPattern)
    {
        // given
        $result = @preg_match($invalidPattern, '');

        // when
        $exception = (new ExceptionFactory())->retrieveGlobals('preg_match', $result);

        // then
        $this->assertInstanceOf(CompileSafeRegexException::class, $exception);
    }

    /**
     * @test
     * @dataProvider \Test\DataProviders::invalidUtf8Sequences()
     * @param string $description
     * @param string $utf8
     */
    public function test($description, $utf8)
    {
        // given
        $result = @preg_match("/pattern/u", $utf8);

        // when
        $exception = (new ExceptionFactory())->retrieveGlobals('preg_match', $result);

        // then
        $this->assertInstanceOf(RuntimeSafeRegexException::class, $exception);
    }

    /**
     * @test
     * @dataProvider \Test\DataProviders::invalidPregPatterns()
     * @param string $invalidPattern
     */
    public function testUnexpectedReturnError($invalidPattern)
    {
        // given
        $result = @preg_match($invalidPattern, '');
        (new ErrorsCleaner)->clear();

        // when
        $exception = (new ExceptionFactory())->retrieveGlobals('preg_match', $result);

        // then
        $this->assertInstanceOf(SuspectedReturnSafeRegexException::class, $exception);
    }
}
