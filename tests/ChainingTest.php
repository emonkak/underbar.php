<?php

class ChainingTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provider
     */
    public function testMapFlattenReduce($_)
    {
        $lyrics = array(
            "I'm a lumberjack and I'm okay",
            "I sleep all night and I work all day",
            "He's a lumberjack and he's okay",
            "He sleeps all night and he works all day"
        );
        $counts = $_::chain($lyrics)
            ->map(function($line) { return str_split($line); })
            ->flatten()
            ->reduce(function($hash, $l) {
                if (!isset($hash[$l])) $hash[$l] = 0;
                $hash[$l]++;
                return $hash;
            }, array())
            ->value();

        $this->assertEquals(16, $counts['a'], 'counted all the letters in the song');
        $this->assertEquals(10, $counts['e'], 'counted all the letters in the song');
    }

    /**
     * @dataProvider provider
     */
    public function testSelectRejectSortBy($_)
    {
        $numbers = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10);
        $numbers = $_::chain($numbers)->select(function($n) {
            return $n % 2 === 0;
        })->reject(function($n) {
            return $n % 4 === 0;
        })->sortBy(function($n) {
            return -$n;
        })->value();
        $this->assertEquals(array(10, 6, 2), $_::toArray($numbers), 'filtered and reversed the numbers');
    }

    /**
     * @dataProvider provider
     */
    public function testReverseConcatUnshiftPopMap($_)
    {
        $numbers = $_::chain(array(1, 2, 3, 4, 5))
            ->reverse()
            ->concat(array(5, 5, 5))
            ->unshift(17)
            ->pop()
            ->map(function($n) { return $n * 2; })
            ->toArray()
            ->value();
        $shouldBe = array(34, 10, 8, 6, 4, 2, 10, 10);
        $this->assertEquals($shouldBe, $numbers, 'can chain together array functions.');
    }

    public function provider()
    {
        return provideClasses();
    }
}

// __END__
// vim: expandtab softtabstop=4 shiftwidth=4
