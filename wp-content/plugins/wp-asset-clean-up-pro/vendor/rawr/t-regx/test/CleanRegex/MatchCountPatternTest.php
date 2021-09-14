<?php
namespace Test\CleanRegex;

use CleanRegex\Internal\Pattern;
use CleanRegex\Match\MatchPattern;
use PHPUnit\Framework\TestCase;

class MatchCountPatternTest extends TestCase
{
    /**
     * @test
     * @dataProvider patternsAndSubjects
     * @param string $pattern
     * @param string $subject
     * @param int    $expectedCount
     */
    public function shouldCountMatches($pattern, $subject, $expectedCount)
    {
        // given
        $matchPattern = new MatchPattern(new Pattern($pattern), $subject);

        // when
        $count = $matchPattern->count();

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
