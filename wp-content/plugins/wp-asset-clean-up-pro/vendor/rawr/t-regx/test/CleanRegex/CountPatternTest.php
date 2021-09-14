<?php
namespace Test\CleanRegex;

use CleanRegex\CountPattern;
use CleanRegex\Internal\Pattern as InternalPattern;
use PHPUnit\Framework\TestCase;

class CountPatternTest extends TestCase
{
    /**
     * @test
     * @dataProvider patternsAndSubjects
     * @param $pattern
     * @param $subject
     * @param $expectedCount
     */
    public function shouldCountMatches($pattern, $subject,$expectedCount)
    {
        // given
        $countPattern = new CountPattern(new InternalPattern($pattern), $subject);

        // when
        $count = $countPattern->count();

        // then
        $this->assertEquals($expectedCount, $count, "Failed asserting that count() returned $expectedCount.");
    }

    public function patternsAndSubjects()
    {
        return [
            ['/dog/', 'cat', 0],
            ['/[aoe]/', 'match vowels', 3],
            ['/car(pet)?/', 'car carpet', 2],
            ['/car(p(e(t)))?/', 'car carpet car carpet', 4],
        ];
    }
}
