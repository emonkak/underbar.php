<?php

namespace Underbar\Tests;

use Underbar\Collection;
use Underbar\Provider\ArrayProvider;
use Underbar\Provider\IteratorProvider;
use Underbar\Provider\GeneratorProvider;

class CollectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provideCollectionFactory
     */
    public function testEach($factory)
    {
        $factory([1, 2, 3])->each(function($num, $i) {
            $this->assertSame($num, $i + 1, 'each iterators provide value and iteration count');
        });

        $answers = [];
        $obj = ['one' => 1, 'two' => 2, 'three' => 3];
        $factory($obj)->each(function($value, $key) use (&$answers) {
            $answers[] = $key;
        });
        $this->assertSame(['one', 'two', 'three'], $answers, 'iterating over objects works.');

        $answer = false;
        $factory([1, 2, 3])->each(function($num, $index, $arr) use ($factory, &$answer) {
            if ($factory($arr)->contains($num)) $answer = true;
        });
        $this->assertTrue($answer, 'can reference the original collection from inside the iterator');
    }

    /**
     * @dataProvider provideCollectionFactory
     */
    public function testMap($factory)
    {
        $doubled = $factory([1, 2, 3])->map(function($num) {
            return $num * 2;
        })->toArray();
        $this->assertSame([2, 4, 6], $doubled, 'doubled numbers');

        $doubled = $factory([1, 2, 3])->collect(function($num) {
            return $num * 2;
        })->toArray();
        $this->assertSame([2, 4, 6], $doubled, 'aliased as "collect"');
    }

    /**
     * @dataProvider provideCollectionFactory
     */
    public function testReduce($factory)
    {
        $sum = function($acc, $n) {
            return $acc + $n;
        };

        $result = $factory([1, 2, 3])->reduce($sum, 0);
        $this->assertSame(6, $result, 'can sum up an array');

        $result = $factory([1, 2, 3])->inject($sum, 0);
        $this->assertSame(6, $result, 'aliased as "inject"');

        $result = $factory([1, 2, 3])->foldl($sum, 0);
        $this->assertSame(6, $result, 'aliased as "foldl"');
    }

    /**
     * @dataProvider provideCollectionFactory
     */
    public function testReduceRight($factory)
    {
        $list = $factory(['foo', 'bar', 'baz'])->reduceRight(function($memo, $str) {
            return $memo . $str;
        }, '');
        $this->assertSame($list, 'bazbarfoo', 'can perform right folds');

        $list = $factory(["foo", "bar", "baz"])->foldr(function($memo, $str) {
            return $memo . $str;
        }, '');
        $this->assertSame($list, 'bazbarfoo', 'aliased as "foldr"');

        $sum = $factory(['a' => 1, 'b' => 2, 'c' => 3])->reduceRight(function($sum, $num) {
            return $sum + $num;
        }, 0);
        $this->assertSame(6, $sum, 'on object');

        // Assert that the correct arguments are being passed.
        $args = null;
        $memo = [];
        $object = ['a' => 1, 'b' => 2];

        $factory($object)->reduceRight(function() use (&$args) {
            $args || ($args = func_get_args());
        }, $memo);
        $this->assertEmpty($args[0]);
        $this->assertSame(2, $args[1]);

        $result = $factory(range(0, 9))->reduceRight(function($x, $y) {
            return $x - $y;
        }, 0);
        $this->assertSame(-45, $result);
    }

    /**
     * @dataProvider provideCollectionFactory
     */
    public function testFind($factory)
    {
        $array = [1, 2, 3, 4];
        $this->assertSame(3, $factory($array)->find(function($n) { return $n > 2; }), 'should return first found `value`');
        $this->assertNull($factory($array)->find(function() { return false; }), 'should return `undefined` if `value` is not found');

        $result = $factory([1, 2, 3])->find(function($num) { return $num * 2 == 4; });
        $this->assertSame(2, $result, 'found the first "2" and broke the loop');

        $result = $factory([1, 2, 3])->detect(function($num) { return $num * 2 == 4; });
        $this->assertSame(2, $result, 'alias as "detect"');
    }

    /**
     * @dataProvider provideCollectionFactory
     */
    public function testFilter($factory)
    {
        $evens = $factory([1, 2, 3, 4, 5, 6])->filter(function($num) { return $num % 2 == 0; })->toList();
        $this->assertSame([2, 4, 6], $evens, 'selected each even number');

        $evens = $factory([1, 2, 3, 4, 5, 6])->select(function($num) { return $num % 2 == 0; })->toList();
        $this->assertSame([2, 4, 6], $evens, 'aliased as "select"');
    }

    /**
     * @dataProvider provideCollectionFactory
     */
    public function testWhere($factory)
    {
        $list = [
            ['a' => 1, 'b' => 2],
            ['a' => 2, 'b' => 2],
            ['a' => 1, 'b' => 3],
            ['a' => 1, 'b' => 4]
        ];
        $result = $factory($list)->where(['a' => 1])->toList();
        $this->assertCount(3, $result);
        $this->assertSame([['a' => 1, 'b' => 2], ['a' => 1, 'b' => 3], ['a' => 1, 'b' => 4]], $result);

        $result = $factory($list)->where(['b' => 2])->toList();
        $this->assertCount(2, $result);
        $this->assertSame([['a' => 1, 'b' => 2], ['a' => 2, 'b' => 2]], $result);
    }

    /**
     * @dataProvider provideCollectionFactory
     */
    public function testFindWhere($factory)
    {
        $list = [
            ['a' => 1, 'b' => 2],
            ['a' => 2, 'b' => 2],
            ['a' => 1, 'b' => 3],
            ['a' => 1, 'b' => 4],
            ['a' => 2, 'b' => 4]
        ];

        $result = $factory($list)->findWhere(['a' => 1]);
        $this->assertSame($list[0], $result);
        $result = $factory($list)->findWhere(['b' => 4]);
        $this->assertSame($list[3], $result);
        $result = $factory($list)->findWhere(['c' => 0]);
        $this->assertNull($result);
    }

    /**
     * @dataProvider provideCollectionFactory
     */
    public function testReject($factory)
    {
        $odds = $factory([1, 2, 3, 4, 5, 6])->reject(function($num) { return $num % 2 == 0; })->toList();
        $this->assertSame([1, 3, 5], $odds, 'rejected each even number');
    }

    /**
     * @dataProvider provideCollectionFactory
     */

    public function testEvery($factory)
    {
        $this->assertTrue($factory([])->every(), 'the empty set');
        $this->assertTrue($factory([true, true, true])->every(), 'all true values');
        $this->assertFalse($factory([true, false, true])->every(), 'one false value');
        $this->assertTrue($factory([0, 10, 28])->every(function($num) { return $num % 2 == 0; }), 'even numbers');
        $this->assertFalse($factory([0, 11, 28])->every(function($num) { return $num % 2 == 0; }), 'an odd number');
        $this->assertTrue($factory([1])->every(), 'cast to boolean - true');
        $this->assertFalse($factory([0])->every(), 'cast to boolean - false');
        $this->assertTrue($factory([true, true, true])->all(), 'aliased as "all"');
        $this->assertFalse($factory([null, null, null])->every(), 'works with arrays of null');
    }
    /**
     * @dataProvider provideCollectionFactory
     */
    public function testSome($factory)
    {
        $this->assertFalse($factory([])->some(), 'the empty set');
        $this->assertFalse($factory([false, false, false])->some(), 'all false values');
        $this->assertTrue($factory([false, false, true])->some(), 'one true value');
        $this->assertTrue($factory([null, 0, 'yes', false])->some(), 'a string');
        $this->assertFalse($factory([null, 0, '', false])->some(), 'falsy values');
        $this->assertFalse($factory([1, 11, 29])->some(function($num) { return $num % 2 == 0; }), 'all odd numbers');
        $this->assertTrue($factory([1, 10, 29])->some(function($num) { return $num % 2 == 0; }), 'an even number');
        $this->assertTrue($factory([1])->some(), 'cast to boolean - true');
        $this->assertFalse($factory([0])->some(), 'cast to boolean - false');
        $this->assertTrue($factory([false, false, true])->any(), 'aliased as "any"');
    }

    /**
     * @dataProvider provideCollectionFactory
     */
    public function testContains($factory)
    {
        $this->assertTrue($factory([1, 2, 3])->contains(2), 'two is in the array');
        $this->assertFalse($factory([1, 3, 9])->contains(2), 'two is not in the array');
        $this->assertTrue($factory(['moe' => 1, 'larry' => 3, 'curly' => 9])->includes(3), 'on objects checks their values');
    }

    /**
     * @dataProvider provideCollectionFactory
     */
    public function testInvoke($factory)
    {
        $list = [Collection::from([5, 1, 7]), Collection::from([3, 2, 1])];
        $result = $factory($list)->invoke('sort')->toArrayRec();
        $this->assertSame([[1, 5, 7], [1, 2, 3]], $result, 'first array sorted');
    }

    /**
     * @dataProvider provideCollectionFactory
     */
    public function testPluck($factory)
    {
        $people = [
            ['name' => 'moe', 'age' => 30],
            ['name' => 'curly', 'age' => 50]
        ];
        $result = $factory($people)->pluck('name')->toArray();
        $this->assertSame(['moe', 'curly'], $result, 'pulls names out of arrays');

        $people = array(
            (object) ['name' => 'moe', 'age' => 30],
            (object) ['name' => 'curly', 'age' => 50]
        );
        $result = $factory($people)->pluck('name')->toArray();
        $this->assertSame(['moe', 'curly'], $result, 'pulls names out of objects');
    }

    /**
     * @dataProvider provideCollectionFactory
     */
    public function testMax($factory)
    {
        $this->assertSame(3, $factory([1, 2, 3])->max(), 'can perform a regular max()');

        $neg = $factory([1, 2, 3])->max(function($num) { return -$num; });
        $this->assertSame(1, $neg, 'can perform a computation-based max');

        $this->assertSame(-INF, $factory([])->max(), 'Maximum value of an empty array');
        $this->assertSame('a', $factory(['a' => 'a'])->max(), 'Maximum value of a non-numeric collection');

        $this->assertSame(9999, $factory(range(1, 9999))->max(), 'Maximum value of a too-big array');
    }

    /**
     * @dataProvider provideCollectionFactory
     */
    public function testMin($factory)
    {
        $this->assertSame(1, $factory([1, 2, 3])->min(), 'can perform a regular min()');

        $neg = $factory([1, 2, 3])->min(function($num) { return -$num; });
        $this->assertSame(3, $neg, 'can perform a computation-based min');

        $this->assertSame(INF, $factory([])->min(), 'Minimum value of an empty object');
        $this->assertSame('a', $factory(['a' => 'a'])->min(), 'Minimum value of a non-numeric collection');

        $now = new \DateTime();
        $now->setTimestamp(9999999999);
        $then = new \DateTime();
        $then->setTimestamp(0);
        $this->assertSame($then, $factory([$now, $then])->min(function($d) { return $d->getTimestamp(); }));
        $this->assertSame($then, $factory([$then, $now])->min(function($d) { return $d->getTimestamp(); }));

        $this->assertSame(1, $factory(range(1, 9999))->min(), 'Minimum value of a too-big array');
    }

    /**
     * @dataProvider provideCollectionFactory
     */
    public function testSum($factory)
    {
        $this->assertSame(45, $factory(range(0, 9))->sum(), 'sum 0..9');
        $this->assertSame(0, $factory([])->sum(), 'sum empty array');
    }

    /**
     * @dataProvider provideCollectionFactory
     */
    public function testProduct($factory)
    {
        $this->assertSame(362880, $factory(range(1, 9))->product(), 'product 1..9');
        $this->assertSame(1, $factory([])->product(), 'product empty array');
    }

    /**
     * @dataProvider provideCollectionFactory
     */
    public function testAverage($factory)
    {
        $this->assertSame(4.5, $factory(range(0, 9))->average(), 'agverage between 0-9');
        $this->assertSame(INF, $factory([])->average(), 'average empty array');
    }

    /**
     * @dataProvider provideCollectionFactory
     */
    public function testSortBy($factory)
    {
        $people = [
            ['name' => 'curly', 'age' => 50],
            ['name' => 'moe', 'age' => 30]
        ];
        $people = $factory($people)->sortBy(function($person) { return $person['age']; })->toArray();
        $result = $factory($people)->pluck('name')->toArray();
        $this->assertSame(['moe', 'curly'], $result, 'stooges sorted by age');

        $list = [null, 4, 1, null, 3, 2];
        $sorted = $factory($list)->sortBy()->toArray();
        $this->assertSame([null, null, 1, 2, 3, 4], $sorted, 'sortBy with undefined values');

        $list = ['one', 'two', 'three', 'four', 'five'];
        $sorted = $factory($list)->sortBy(function($str) { return strlen($str); })->toArray();
        $this->assertSame(['one', 'two', 'four', 'five', 'three'], $sorted, 'sorted by length');

        $collection = [
            [null, 1], [null, 2],
            [null, 3], [null, 4],
            [null, 5], [null, 6],
            [1, 1], [1, 2],
            [1, 3], [1, 4],
            [1, 5], [1, 6],
            [2, 1], [2, 2],
            [2, 3], [2, 4],
            [2, 5], [2, 6]
        ];
        $actual = $factory($collection)->sortBy(function($pair) {
            return $pair[0];
        })->toArray();
        $this->assertSame($collection, $actual, 'sortBy should be stable');
    }

    /**
     * @dataProvider provideCollectionFactory
     */
    public function testGroupBy($factory)
    {
        $parity = $factory([1, 2, 3, 4, 5, 6])
            ->groupBy(function($num) { return $num % 2; })
            ->toArray();
        $this->assertArrayHasKey(0, $parity, 'created a group for each value');
        $this->assertArrayHasKey(1, $parity, 'created a group for each value');

        $this->assertSame([1, 3, 5], $parity[1], 'put each even number in the right group');
        $this->assertSame([2, 4, 6], $parity[0], 'put each even number in the right group');

        $list = ['one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten'];
        $result = $factory($list)
            ->groupBy(function($x) { return strlen($x); })
            ->toArray();
        $shouldBe = [
            3 => ['one', 'two', 'six', 'ten'],
            4 => ['four', 'five', 'nine'],
            5 => ['three', 'seven', 'eight'],
        ];
        $this->assertEquals($shouldBe, $result);

        $grouped = $factory([4.2, 6.1, 6.4])
            ->groupBy(function($num) {
                return floor($num) > 4 ? 'one' : 'two';
            })->toArray();
        $this->assertSame([6.1, 6.4], $grouped['one']);
        $this->assertSame([4.2], $grouped['two']);

        $grouped = $factory([1, 2, 1, 2, 3])
            ->groupBy()
            ->toArray();
        $this->assertEquals([1 => [1, 1], 2 => [2, 2], 3 => [3]], $grouped);

        $dict = [
            ['key' => 'foo', 'value' => 1],
            ['key' => 'foo', 'value' => 2],
            ['key' => 'foo', 'value' => 3],
            ['key' => 'bar', 'value' => 4],
            ['key' => 'bar', 'value' => 5],
        ];
        $grouped = $factory($dict)
            ->groupBy('key')
            ->map(function($xs) use ($factory) { return $factory($xs)->pluck('value'); })
            ->toArrayRec();
        $this->assertEquals(['foo' => [1, 2, 3], 'bar' => [4, 5]], $grouped);
    }

    /**
     * @dataProvider provideCollectionFactory
     */
    public function testIndexBy($factory)
    {
        $parity = $factory([1, 2, 3, 4, 5])
            ->indexBy(function($num) {
                return $num % 2 == 0 ? 'true' : 'false';
            })
            ->toArray();
        $this->assertEquals(['true' => 4, 'false' => 5], $parity);

        $list = [
            ['string' => 'one', 'length' => strlen('one')],
            ['string' => 'two', 'length' => strlen('two')],
            ['string' => 'three', 'length' => strlen('three')],
            ['string' => 'four', 'length' => strlen('four')],
            ['string' => 'five', 'length' => strlen('five')],
            ['string' => 'six', 'length' => strlen('six')],
            ['string' => 'seven', 'length' => strlen('seven')],
            ['string' => 'eight', 'length' => strlen('eight')],
            ['string' => 'nine', 'length' => strlen('nine')],
            ['string' => 'ten', 'length' => strlen('ten')],
        ];
        $shouldBe = [
            3 => ['string' => 'ten', 'length' => 3],
            4 => ['string' => 'nine', 'length' => 4],
            5 => ['string' => 'eight', 'length' => 5]
        ];
        $grouped = $factory($list)
            ->indexBy('length')
            ->toArray();
        $this->assertEquals($shouldBe, $grouped);

        $array = [1, 2, 1, 2, 3];
        $shouldBe = [
            1 => 1,
            2 => 2,
            3 => 3
        ];
        $grouped = $factory($array)->indexBy()->toArray();
        $this->assertEquals($shouldBe, $grouped);
    }

    /**
     * @dataProvider provideCollectionFactory
     */
    public function testCountBy($factory)
    {
        $parity = $factory([1, 2, 3, 4, 5])
            ->countBy(function($num){ return $num % 2; })
            ->toArray();
        $this->assertEquals([0 => 2, 1 => 3], $parity);

        $list = ['one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten'];
        $grouped = $factory($list)
            ->countBy(function($x) { return strlen($x); })
            ->toArray();
        $this->assertEquals([3 => 4, 4 => 3, 5 => 3], $grouped);

        $grouped = $factory([4.2, 6.1, 6.4])
            ->countBy(function($num) {
                return floor($num) > 4 ? 'one' : 'two';
            })
            ->toArray();
        $this->assertEquals(['one' => 2, 'two' => 1], $grouped);

        $grouped = $factory([1, 2, 1, 2, 3])
            ->countBy()
            ->toArray();
        $this->assertEquals([1 => 2, 2 => 2, 3 => 1], $grouped);
    }

    /**
     * @dataProvider provideCollectionFactory
     */
    public function testShuffle($factory)
    {
        $numbers = range(0, 9);
        $shuffled = $factory($numbers)->shuffle()->sort()->toList();
        $this->assertSame($numbers, $shuffled, 'contains the same members before and after shuffle');
    }

    /**
     * @dataProvider provideCollectionFactory
     */
    public function testSample($factory)
    {
        $numbers = range(0, 9);
        $allSampled = $factory($numbers)->sample(10)->sort()->toList();
        $this->assertSame($numbers, $allSampled);

        $allSampled = $factory($numbers)->sample(100)->sort()->toList();
        $this->assertSame($numbers, $allSampled);

        $this->assertContains($factory($numbers)->sample(), $numbers);
        $this->assertNull($factory([])->sample());
        $this->assertEmpty($factory([])->sample(5)->toList());
        $this->assertEmpty($factory([])->sample(0)->toList());
        $this->assertEmpty($factory([1, 2, 3])->sample(0)->toList());
        $this->assertEmpty($factory([1, 2])->sample(-1)->toList());

        $numbers = ['a' => 1, 'b' => 2, 'c' => 3];
        $this->assertContains($factory($numbers)->sample(), $numbers);
    }

    /**
     * @dataProvider provideCollectionFactory
     */
    public function testMemoize($factory)
    {
        $counter = 0;
        $result = $factory([1, 2, 3])
            ->map(function($n) use (&$counter) { $counter++; return $n * 2; })
            ->memoize();

        foreach ($result as $value);
        foreach ($result as $value);
        $this->assertSame($counter, 3);
        $this->assertSame([2, 4, 6], $result->toArray());
    }

    /**
     * @dataProvider provideCollectionFactory
     */
    public function testToArray($factory)
    {
        $array = [1, 2, 3];
        $this->assertSame($array, $factory($array)->toArray(), 'cloned array contains same elements');

        $numbers = ['one' => 1, 'two' => 2, 'three' => 3];
        $this->assertSame($numbers, $factory($numbers)->toArray(), 'object flattened into array');
    }

    /**
     * @dataProvider provideCollectionFactory
     */
    public function testToList($factory)
    {
        $array = [1, 2, 3];
        $this->assertSame($array, $factory($array)->toList(), 'cloned array contains same elements');

        $numbers = ['one' => 1, 'two' => 2, 'three' => 3];
        $this->assertSame($array, $factory($numbers)->toList(), 'object flattened into array');
    }

    /**
     * @dataProvider provideCollectionFactory
     */
    public function testSize($factory)
    {
        $this->assertSame(3, $factory(['one' => 1, 'two' => 2, 'three' => 3])->size(), 'can compute the size of an object');
        $this->assertSame(3, $factory(range(0, 2))->size(), 'can compute the size of an array');
        $this->assertSame(3, $factory(new \ArrayObject([1, 2, 3]))->size(), 'works with Countable');
    }

    public function provideCollectionFactory()
    {
        return [
            [function($source) { return new Collection($source, ArrayProvider::getInstance()); }],
            [function($source) { return new Collection($source, IteratorProvider::getInstance()); }],
            [function($source) { return new Collection($source, GeneratorProvider::getInstance()); }],
        ];
    }
}
