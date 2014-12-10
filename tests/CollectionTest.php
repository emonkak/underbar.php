<?php

namespace Underbar\Tests;

use Underbar\Collection;
use Underbar\Provider\ArrayProvider;
use Underbar\Provider\IteratorProvider;
use Underbar\Provider\GeneratorProvider;

class CollectionTest extends \PHPUnit_Framework_TestCase
{
    public function testRange()
    {
        $result = Collection::range(0)->toList();
        $this->assertEmpty($result, 'range with 0 as a first argument generates an empty array');

        $result = Collection::range(4)->toList();
        $shouldBe = [0, 1, 2, 3];
        $this->assertSame($shouldBe, $result, 'range with a single positive argument generates an array of elements 0, 1, 2, ..., n-1');

        $result = Collection::range(5, 8)->toList();
        $shouldBe = [5, 6, 7];
        $this->assertSame($shouldBe, $result, 'range with two arguments a & b, a < b generates an array of elements a, a + 1, a + 2, ..., b - 2, b - 1');

        $result = Collection::range(8, 5)->toList();
        $this->assertEmpty($result, 'range with two arguments a & b, b < a generates an empty array');

        $result = Collection::range(3, 10, 3)->toList();
        $shouldBe = [3, 6, 9];
        $this->assertSame($shouldBe, $result, 'range with three arguments a & b & c, c < b - a, a < b generates an array of elements a, a + c, a + 2c, ..., b - (multiplier of a) < c');

        $result = Collection::range(3, 10, 15)->toList();
        $shouldBe = [3];
        $this->assertSame($shouldBe, $result, 'range with three arguments a & b & c, c > b - a, a < b generates an array with a single element, equal to a');

        $result = Collection::range(12, 7, -2)->toList();
        $shouldBe = [12, 10, 8];
        $this->assertSame($shouldBe, $result, 'range with three arguments a & b & c, a > b, c < 0 generates an array of elements a,a-c,a-2c and ends with the number not less than b');

        $result = Collection::range(0, -10, -1)->toList();
        $shouldBe = [0, -1, -2, -3, -4, -5, -6, -7, -8, -9];
        $this->assertSame($shouldBe, $result, 'final example in the Python docs');
    }

    public function testRepeat()
    {
        $result = Collection::repeat(1, 2)->toList();
        $this->assertSame([1, 1], $result);

        $result = Collection::repeat([1, 2], 2)->toList();
        $this->assertSame([[1, 2], [1, 2]], $result);

        $result = Collection::repeat('foo', 0)->toList();
        $this->assertEmpty($result);

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
    public function testConcatMap($factory)
    {
        $list = [1, 2, 3];
        $result = $factory($list)->concatMap(function($n) {
            return range(1, $n);
        })->toList();
        $shouldBe = [1, 1, 2, 1, 2, 3];
        $this->assertEquals($shouldBe, $result);
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

        $people = [
            (object) ['name' => 'moe', 'age' => 30],
            (object) ['name' => 'curly', 'age' => 50]
        ];
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

    /**
     * @dataProvider provideCollectionFactory
     */
    public function testFirst($factory)
    {
        $result = $factory([1, 2, 3])->first();
        $this->assertSame(1, $result, 'can pull out the first element of an array');

        $result = $factory([1, 2, 3])->first(0)->toList();
        $this->assertSame([], $result, 'can pass an index to first');

        $result = $factory([1, 2, 3])->first(2)->toList();
        $this->assertSame([1, 2], $result, 'can pass an index to first');

        $result = $factory([1, 2, 3])->first(5)->toList();
        $this->assertSame([1, 2, 3], $result, 'can pass an index to first');

        $result = $factory([1, 2, 3])->take(2)->toList();
        $this->assertSame([1, 2], $result, 'aliased as take');

        $result = $factory([1, 2, 3])->head(2)->toList();
        $this->assertSame([1, 2], $result, 'aliased as head');
    }

    /**
     * @dataProvider provideCollectionFactory
     * @expectedException \RuntimeException
     */
    public function testFirstOfEmptyCollectionThrowsException($factory)
    {
        $factory([])->first();
    }

    /**
     * @dataProvider provideCollectionFactory
     */
    public function testFirstOrElse($factory)
    {
        $result = $factory([1, 2, 3])->firstOrElse(10);
        $this->assertSame(1, $result);

        $result = $factory([])->firstOrElse(10);
        $this->assertSame(10, $result);
    }

    /**
     * @dataProvider provideCollectionFactory
     */
    public function testRest($factory)
    {
        $numbers = [1, 2, 3, 4];

        $result = $factory($numbers)->rest()->toList();
        $this->assertSame([2, 3, 4], $result, 'working rest()');

        $result = $factory($numbers)->rest(0)->toList();
        $this->assertSame([1, 2, 3, 4], $result, 'working rest(0)');

        $result = $factory($numbers)->rest(2)->toList();
        $this->assertSame([3, 4], $result, 'rest can take an index');

        $result = $factory($numbers)->tail()->toList();
        $this->assertSame([2, 3, 4], $result, 'aliased as tail');

        $result = $factory($numbers)->drop()->toList();
        $this->assertSame([2, 3, 4], $result, 'aliased as drop');
    }

    /**
     * @dataProvider provideCollectionFactory
     */
    public function testTakeWhile($factory)
    {
        $result = $factory([1, 2, 3, 4, 5, 1, 2, 3])->takeWhile(function($x) {
            return $x < 3;
        })->toList();
        $this->assertSame([1, 2], $result);

        $result = $factory([1, 2, 3])->takeWhile(function($x) {
            return $x < 9;
        })->toList();
        $this->assertSame([1, 2, 3], $result);

        $result = $factory([1, 2, 3])->takeWhile(function($x) {
            return $x < 0;
        })->toList();
        $this->assertSame([], $result);

        $result = $factory([])->takeWhile(function($x) {
            return $x > 0;
        })->toList();
        $this->assertSame([], $result);
    }

    /**
     * @dataProvider provideCollectionFactory
     */
    public function testDropWhile($factory)
    {
        $result = $factory([1, 2, 3, 4, 5, 1, 2, 3])->dropWhile(function($x) {
            return $x < 3;
        })->toList();
        $this->assertSame([3, 4, 5, 1, 2, 3], $result);

        $result = $factory([1, 2, 3])->dropWhile(function($x) {
            return $x < 9;
        })->toList();
        $this->assertSame([], $result);

        $result = $factory([1, 2, 3])->dropWhile(function($x) {
            return $x < 0;
        })->toList();
        $this->assertSame([1, 2, 3], $result);

        $result = $factory([])->dropWhile(function($x) {
            return $x > 0;
        })->toList();
        $this->assertSame([], $result);
    }

    /**
     * @dataProvider provideCollectionFactory
     */
    public function testInitial($factory)
    {
        $result = $factory([1, 2, 3, 4, 5])->initial()->toList();
        $this->assertSame([1, 2, 3, 4], $result, 'working initial()');

        $result = $factory([1, 2, 3, 4])->initial(2)->toList();
        $this->assertSame([1, 2], $result, 'initial can take an index');
    }

    /**
     * @dataProvider provideCollectionFactory
     */
    public function testLast($factory)
    {
        $result = $factory([1, 2, 3])->last();
        $this->assertSame(3, $result, 'can pull out the last element of an array');

        $result = $factory([1, 2, 3])->last(0)->toList();
        $this->assertSame([], $result, 'can pass an index to last');

        $result = $factory([1, 2, 3])->last(2)->toList();
        $this->assertSame([2, 3], $result, 'can pass an index to last');

        $result = $factory([1, 2, 3])->last(5)->toList();
        $this->assertSame([1, 2, 3], $result, 'can pass an index to last');
    }

    /**
     * @dataProvider provideCollectionFactory
     * @expectedException \RuntimeException
     */
    public function testLastOfEmptyCollection($factory)
    {
        $factory([])->last();
    }

    /**
     * @dataProvider provideCollectionFactory
     */
    public function testLastOrElse($factory)
    {
        $result = $factory([1, 2, 3])->lastOrElse(10);
        $this->assertSame(3, $result);

        $result = $factory([])->lastOrElse(10);
        $this->assertSame(10, $result);
    }

    /**
     * @dataProvider provideCollectionFactory
     */
    public function testCompact($factory)
    {
        $result = $factory([0, 1, false, 2, false, 3])->compact()->toList();
        $this->assertCount(3, $result, 'can trim out all falsy values');
    }

    /**
     * @dataProvider provideCollectionFactory
     */
    public function testFlatten($factory)
    {
        $list = [1, [2], [3, [[[4]]]]];

        $result = $factory($list)->flatten()->toList();
        $shouldBe = [1, 2, 3, 4];
        $this->assertSame($shouldBe, $result, 'can flatten nested arrays');

        $result = $factory($list)->flatten(true)->toList();
        $shouldBe = [1, 2, 3, [[[4]]]];
        $this->assertSame($shouldBe, $result, 'can shallowly flatten nested arrays');
    }

    /**
     * @dataProvider provideCollectionFactory
     */
    public function testWithout($factory)
    {
        $list = [1, 2, 1, 0, 3, 1, 4];
        $result = $factory($list)->without(0, 1)->toList();
        $shouldBe = [2, 3, 4];
        $this->assertSame($shouldBe, $result, 'can remove all instances of an object');

        $list = [['one' => 1], ['two' => 2]];
        $result = $factory($list)->without(['one' => 1], 1)->toList();
        $this->assertSame([['two' => 2]], $result, 'uses real object identity for comparisons.');

        $result = $factory($list)->without($list[0])->toList();
        $this->assertSame([['two' => 2]], $result, 'ditto.');
    }

    /**
     * @dataProvider provideCollectionFactory
     */
    public function testUniq($factory)
    {
        $list = [1, 2, 1, 3, 1, 4];
        $shouldBe = [1, 2, 3, 4];
        $this->assertSame($shouldBe, $factory($list)->uniq()->toList(), 'can find the unique values');
        $this->assertSame($shouldBe, $factory($list)->unique()->toList(), 'alias as unique');

        $list = [['name' => 'moe'], ['name' => 'curly'], ['name' => 'larry'], ['name' => 'curly']];
        $selector = function($value) { return $value['name']; };
        $result = $factory($list)->uniq($selector)->map($selector)->toList();
        $shouldBe = ['moe', 'curly', 'larry'];
        $this->assertSame($shouldBe, $result, 'can find the unique values of an array using a custom iterator');

        $list = [1, 2, 2, 3, 4, 4];
        $result = $factory($list)->uniq(function($value) { return $value + 1; })->toList();
        $shouldBe = [1, 2, 3, 4];
        $this->assertSame($shouldBe, $result, 'selector works');

        $obj1 = new \StdClass();
        $obj2 = new \StdClass();
        $obj3 = new \StdClass();
        $result = $factory([$obj1, $obj2, $obj2, $obj3])->uniq()->toList();
        $shouldBe = [$obj1];
        $this->assertSame($shouldBe, $result, 'works well objects');
    }

    /**
     * @dataProvider provideCollectionFactory
     */
    public function testIntersection($factory)
    {
        $stooges = ['moe', 'curly', 'larry', 'moe'];
        $leaders = ['moe', 'groucho'];
        $shouldBe = ['moe'];

        $result = $factory($stooges)->intersection($leaders)->toList();
        $this->assertSame($shouldBe, $result, 'can take the set intersection of two arrays');

        $result = $factory($stooges)->intersection($leaders, $leaders)->toList();
        $this->assertSame($shouldBe, $result, 'can take the set intersection of three arrays');
    }

    /**
     * @dataProvider provideCollectionFactory
     */
    public function testUnion($factory)
    {
        $result = $factory([1, 2, 3])->union([2, 30, 1], [1, 40])->toList();
        $shouldBe = [1, 2, 3, 30, 40];
        $this->assertSame($shouldBe, $result, 'takes the union of a list of arrays');

        $result = $factory([1, 2, 3])->union([2, 30, 1], [1, 40, [1]])->toList();
        $shouldBe = [1, 2, 3, 30, 40, [1]];
        $this->assertSame($shouldBe, $result, 'takes the union of a list of nested arrays');
    }

    /**
     * @dataProvider provideCollectionFactory
     */
    public function testDifference($factory)
    {
        $result = $factory([1, 2, 3])->difference([2, 30, 40])->toList();
        $shouldBe = [1, 3];
        $this->assertSame($shouldBe, $result, 'takes the difference of two arrays');

        $result = $factory([1, 2, 3, 4])->difference([2, 30, 40], [1, 11, 111])->toList();
        $shouldBe = [3, 4];
        $this->assertSame($shouldBe, $result, 'takes the difference of three arrays');
    }

    /**
     * @dataProvider provideCollectionFactory
     */
    public function testZip($factory)
    {
        $names = ['moe', 'larry', 'curly'];
        $ages = [30, 40, 50];
        $leaders = [true, false];

        $stooges = $factory($names)->zip($ages, $leaders)->toList();
        $shouldBe = [
            ['moe', 30, true],
            ['larry', 40, false]
        ];
        $this->assertSame($shouldBe, $stooges, 'zipped together arrays of different lengths');
    }

    /**
     * @dataProvider provideCollectionFactory
     */
    public function testUnzip($factory)
    {
        $stoogesUnzipped = $factory([
            ['moe', 30, 'stooge 1'],
            ['larry', 40, 'stooge 2'],
            ['curly', 50, 'stooge 3']
        ])->unzip()->toList();
        $shouldBe = [
            ['moe', 'larry', 'curly'],
            [30, 40, 50],
            ['stooge 1', 'stooge 2', 'stooge 3']
        ];
        $this->assertSame($shouldBe, $stoogesUnzipped, 'unzipped pairs');

        // In the case of difference lengths of the tuples undefineds
        // should be used as placeholder
        $stoogesUnzipped = $factory([
            ['moe', 30],
            ['larry', 40],
            ['curly', 50, 'extra data']
        ])->unzip()->toList();
        $shouldBe = [
            ['moe', 'larry', 'curly'],
            [30, 40, 50]
        ];
        $this->assertSame($shouldBe, $stoogesUnzipped,  'unzipped pairs');

        $emptyUnzipped = $factory(array())->unzip()->toList();
        $this->assertEmpty($emptyUnzipped);
    }

    /**
     * @dataProvider provideCollectionFactory
     */
    public function testObject($factory)
    {
        $result = $factory(['moe', 'larry', 'curly'])->object([30, 40, 50])->toArray();
        $shouldBe = ['moe' => 30, 'larry' => 40, 'curly' => 50];
        $this->assertSame($shouldBe, $result, 'two arrays zipped together into an object');

        $result = $factory(['moe', 'larry', 'curly'])->object([30, 40])->toArray();
        $shouldBe = ['moe' => 30, 'larry' => 40];
        $this->assertSame($shouldBe, $result, 'different length arrays');

        $result = $factory([['one', 1], ['two', 2], ['three', 3]])->object()->toArray();
        $shouldBe = ['one' => 1, 'two' =>  2, 'three' =>  3];
        $this->assertSame($shouldBe, $result, 'an array of pairs zipped together into an object');

        $stooges = array('moe' => 30, 'larry' => 40, 'curly' =>  50);
        $result = $factory($stooges)->pairs()->object()->toArray();
        $this->assertSame($stooges, $result, 'an object converted to pairs and back to an object');
    }

    /**
     * @dataProvider provideCollectionFactory
     */
    public function testIndexOf($factory)
    {
        $numbers = [1, 2, 3];
        $this->assertSame(1, $factory($numbers)->indexOf(2), 'can compute indexOf');
        $this->assertSame(-1, $factory($numbers)->indexOf(4), '4 is not in the list');

        $numbers = [10, 20, 30, 40, 50];
        $this->assertSame(-1, $factory($numbers)->indexOf(35, true), '35 is not in the list');
        $this->assertSame(3, $factory($numbers)->indexOf(40, true), '40 is not in the list');

        $numbers = [1, 40, 40, 40, 40, 40, 40, 40, 50, 60, 70];
        $this->assertSame(1, $factory($numbers)->indexOf(40, true), '40 is not in the list');

        $numbers = [1, 2, 3, 1, 2, 3, 1, 2, 3];
        $this->assertSame(7, $factory($numbers)->indexOf(2, 5), 'supports the fromIndex argument');
    }

    /**
     * @dataProvider provideCollectionFactory
     */
    public function testLastIndexOf($factory)
    {
        $numbers = [1, 0, 1];
        $this->assertSame(2, $factory($numbers)->lastIndexOf(1));
        $this->assertSame(-1, $factory($numbers)->lastIndexOf(2));

        $numbers = [1, 0, 1, 0, 0, 1, 0, 0, 0];
        $this->assertSame(5, $factory($numbers)->lastIndexOf(1), 'can compute lastIndexOf');
        $this->assertSame(8, $factory($numbers)->lastIndexOf(0), 'can compute lastIndexOf');

        $numbers = [1, 2, 3, 1, 2, 3, 1, 2, 3];
        $this->assertSame(1, $factory($numbers)->lastIndexOf(2, 2), 'supports the fromIndex argument');
    }

    /**
     * @dataProvider provideCollectionFactory
     */
    public function testSortedIndex($factory)
    {
        $numbers = [10, 20, 30, 40, 50];
        $indexForNum = $factory($numbers)->sortedIndex(35);
        $this->assertSame(3, $indexForNum, '35 should be inserted at index 3');

        $indexFor30 = $factory($numbers)->sortedIndex(30);
        $this->assertSame(2, $indexFor30, '30 should be inserted at index 2');

        $objects = [
            ['x' => 10],
            ['x' => 20],
            ['x' => 30],
            ['x' => 40],
        ];
        $iterator = function($obj) { return $obj['x']; };
        $this->assertSame(2, $factory($objects)->sortedIndex(['x' => 25], $iterator));
        $this->assertSame(3, $factory($objects)->sortedIndex(['x' => 35], 'x'));

        $context = [1 => 2, 2 => 3, 3 => 4];
        $iterator = function($i) use ($context) { return $context[$i]; };
        $this->assertSame(1, $factory([1, 3])->sortedIndex(2, $iterator));
    }

    /**
     * @dataProvider provideCollectionFactory
     */
    public function testCycle($factory)
    {
        $result = $factory([1, 2])->cycle(2)->toList();
        $this->assertSame([1, 2, 1, 2], $result);

        $result = $factory([1, 2])->cycle(1)->toList();
        $this->assertSame([1, 2], $result);

        $result = $factory([1, 2])->cycle(0)->toList();
        $this->assertEmpty($result);

        $result = $factory([])->cycle(2)->toList();
        $this->assertEmpty($result);
    }

    /**
     * @dataProvider provideLazyCollectionFactory
     */
    public function testCycleOfInfiniteStream($factory)
    {
        $result = $factory([1, 2])->cycle()->take(5)->toList();
        $this->assertSame([1, 2, 1, 2, 1], $result);
    }

    /**
     * @dataProvider provideArrayCollectionFactory
     * @expectedException \OverflowException
     */
    public function testCycleOfInfiniteStreamThrowsException($factory)
    {
        $factory([1, 2])->cycle();
    }

    /**
     * @dataProvider provideCollectionFactory
     */
    public function testReverse($factory)
    {
        $result = $factory([1, 2, 3])->reverse()->toList();
        $this->assertSame([3, 2, 1], $result);

        $result = $factory(range(0, 2))->reverse()->toList();
        $this->assertSame([2, 1, 0], $result);

        $result = $factory([])->reverse()->toList();
        $this->assertEmpty($result);
    }

    /**
     * @dataProvider provideCollectionFactory
     */
    public function testSort($factory)
    {
        $result = $factory([2, 3, 1])->sort()->toList();
        $this->assertSame([1, 2, 3], $result);

        $result = $factory([2, 3, 1])->sort(function($x, $y) {
            if ($x === $y) return 0;
            return $x < $y ? 1 : -1;
        })->toList();
        $this->assertSame([3, 2, 1], $result);

        $result = $factory([])->sort()->toList();
        $this->assertEmpty($result);
    }

    /**
     * @dataProvider provideCollectionFactory
     */
    public function testJoin($factory)
    {
        $xs = ['Wind', 'Rain', 'Fire'];
        $this->assertSame('Wind,Rain,Fire', $factory($xs)->join());
        $this->assertSame('Wind, Rain, Fire', $factory($xs)->join(', '));
        $this->assertSame('Wind + Rain + Fire', $factory($xs)->join(' + '));
        $this->assertSame('WindRainFire', $factory($xs)->join(''));

        $xs = new \ArrayIterator(['Wind', 'Rain', 'Fire']);
        $this->assertSame('Wind,Rain,Fire', $factory($xs)->join());
        $this->assertSame('Wind, Rain, Fire', $factory($xs)->join(', '));
        $this->assertSame('Wind + Rain + Fire', $factory($xs)->join(' + '));
        $this->assertSame('WindRainFire', $factory($xs)->join(''));
    }

    public function provideCollectionFactory()
    {
        return array_merge(
            $this->provideArrayCollectionFactory(),
            $this->provideLazyCollectionFactory()
        );
    }

    public function provideLazyCollectionFactory()
    {
        return [
            [function($source) { return new Collection($source, IteratorProvider::getInstance()); }],
            [function($source) { return new Collection($source, GeneratorProvider::getInstance()); }],
        ];
    }

    public function provideArrayCollectionFactory()
    {
        return [
            [function($source) { return new Collection($source, ArrayProvider::getInstance()); }],
        ];
    }
}
