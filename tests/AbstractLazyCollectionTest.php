<?php

namespace Underbar\Tests;

use Underbar\Collection;

abstract class AbstractLazyCollectionTest extends AbstractCollectionTest
{
    public function testRepeatOfInfiniteStream()
    {
        $result = Collection::repeat('foo')->take(4)->toList();
        $this->assertSame(['foo', 'foo', 'foo', 'foo'], $result);
    }

    public function testIterate()
    {
        $result = Collection::iterate(2, function($x) { return $x * $x; })->take(4)->toList();
        $shouldBe = array(2, 4, 16, 256);
        $this->assertSame($shouldBe, $result);

        $result = Collection::iterate([], function($xs) { return range(1, count($xs) + 1); })
            ->map(function($xs) { return Collection::from($xs)->product(); })
            ->take(10)
            ->toList();
        $shouldBe = [1, 1, 2, 6, 24, 120, 720, 5040, 40320, 362880];
        $this->assertSame($shouldBe, $result);

        $result = Collection::iterate([0, 1], function($xs) {
                return [$xs[1], $xs[0] + $xs[1]];
            })
            ->map(function($pair) {
                return $pair[0];
            })
            ->take(10)
            ->toList();
        $shouldBe = [0, 1, 1, 2, 3, 5, 8, 13, 21, 34];
        $this->assertSame($shouldBe, $result);
    }

    /**
     * @dataProvider provideCollectionFactory
     */
    public function testCycleOfInfiniteStream($factory)
    {
        $result = $factory([1, 2])->cycle()->take(5)->toList();
        $this->assertSame([1, 2, 1, 2, 1], $result);
    }
}
