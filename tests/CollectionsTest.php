<?php

class CollectionsTest extends Underbar_TestCase
{
    /**
     * @dataProvider provider
     */
    public function testEach($_)
    {
        $_::each(array(1, 2, 3), function($num, $i) {
            PHPUnit_Framework_TestCase::assertEquals($num, $i + 1, 'each iterators provide value and iteration count');
        });

        $answers = array();
        $obj = array('one' => 1, 'two' => 2, 'three' => 3);
        $_::each($obj, function($value, $key) use (&$answers) {
            $answers[] = $key;
        });
        $this->assertEquals(array('one', 'two', 'three'), $answers, 'iterating over objects works.');

        $answer = false;
        $_::each(array(1, 2, 3), function($num, $index, $arr) use ($_, &$answer) {
            if ($_::contains($arr, $num)) $answer = true;
        });
        $this->assertTrue($answer, 'can reference the original collection from inside the iterator');
    }

    /**
     * @dataProvider provider
     */
    public function testMap($_)
    {
        $doubled = $_::map(array(1, 2, 3), function($num) {
            return $num * 2;
        });
        $this->assertEquals(array(2, 4, 6), $_::toArray($doubled), 'doubled numbers');

        $doubled = $_::collect(array(1, 2, 3), function($num) {
            return $num * 2;
        });
        $this->assertEquals(array(2, 4, 6), $_::toArray($doubled), 'aliased as "collect"');
    }

    /**
     * @dataProvider provider
     */
    public function testMapKey($_)
    {
        $xs = array(1 => 'one', 2 => 'two', 3 => 'three', 4 => 'four');

        $result = $_::mapKey($xs, function($x, $i) {
            return $i * 2;
        });
        $shouldBe = array(2 => 'one', 4 => 'two', 6 => 'three', 8 => 'four');
        $this->assertEquals($shouldBe, $_::toArray($result, true));

        $result = $_::collectKey($xs, function($x, $i) {
            return $i * 2;
        });
        $shouldBe = array(2 => 'one', 4 => 'two', 6 => 'three', 8 => 'four');
        $this->assertEquals($shouldBe, $_::toArray($result, true));

        $result = $_::mapKey($xs, function($x, $i) {
            return $i % 2;
        });
        $shouldBe = array(0 => 'four', 1 => 'three');
        $this->assertEquals($shouldBe, $_::toArray($result, true));

    }

    /**
     * @dataProvider provider
     */
    public function testParallelMap($_)
    {
        $time = $_::bench(function() use ($_) {
            $sum = $_::chain($_::range(10))
                ->parallelMap(function($x) {
                    usleep(100000);
                    return $x * 100;
                }, 10)
                ->sum()
                ->value();
            $this->assertEquals(4500, $sum, 'sum numbers');
        });
        $this->assertLessThan(1.0, $time, 'work to parallel');

        $sum = $_::chain($_::range(10))
            ->parallelCollect(function($x) {
                return $x * 100;
            }, 10)
            ->sum()
            ->value();
        $this->assertEquals(4500, $sum, 'aliased as "collectMap"');
    }

    /**
     * @dataProvider provider
     */
    public function testReduce($_)
    {
        $sum = $_::reduce(array(1, 2, 3), function($sum, $num) {
            return $sum + $num;
        }, 0);
        $this->assertEquals(6, $sum, 'can sum up an array');

        $sum = $_::inject(array(1, 2, 3), function($sum, $num) {
            return $sum + $num;
        }, 0);
        $this->assertEquals(6, $sum, 'aliased as "inject"');
    }

    /**
     * @dataProvider provider
     */
    public function testReduceRight($_)
    {
        $list = $_::reduceRight(array('foo', 'bar', 'baz'), function($memo, $str) {
            return $memo . $str;
        }, '');
        $this->assertEquals($list, 'bazbarfoo', 'can perform right folds');

        $list = $_::foldr(array("foo", "bar", "baz"), function($memo, $str) {
            return $memo . $str;
        }, '');
        $this->assertEquals($list, 'bazbarfoo', 'aliased as "foldr"');

        $sum = $_::reduceRight(array('a' => 1, 'b' => 2, 'c' => 3), function($sum, $num) {
            return $sum + $num;
        }, 0);
        $this->assertEquals(6, $sum, 'on object');

        // Assert that the correct arguments are being passed.
        $args = null;
        $memo = array();
        $object = array('a' => 1, 'b' => 2);
        $lastKey = $_::last($_::keys($object));
        $expected = array($memo, 2, 'b', $object);

        $_::reduceRight($object, function() use (&$args) {
            $args || ($args = func_get_args());
        }, $memo);
        $this->assertEquals($args, $expected);

        // And again, with numeric keys.
        $args = null;
        $memo = array();
        $object = array('2' => 1, '1' => 2);
        $lastKey = $_::last($_::keys($object));
        $expected = array($memo, 2, '1', $object);

        $_::reduceRight($object, function() use (&$args) {
            $args || ($args = func_get_args());
        }, $memo);
        $this->assertEquals($args, $expected);
    }

    /**
     * @dataProvider provider
     */
    public function testFind($_)
    {
        $array = array(1, 2, 3, 4);
        $this->assertEquals(3, $_::find($array, function($n) { return $n > 2; }), 'should return first found `value`');
        $this->assertNull($_::find($array, function() { return false; }), 'should return `undefined` if `value` is not found');

        $result = $_::find(array(1, 2, 3), function($num) { return $num * 2 == 4; });
        $this->assertEquals(2, $result, 'found the first "2" and broke the loop');

        $result = $_::detect(array(1, 2, 3), function($num) { return $num * 2 == 4; });
        $this->assertEquals(2, $result, 'alias as "detect"');
    }

    /**
     * @dataProvider provider
     */
    public function testFilter($_)
    {
        $evens = $_::filter(array(1, 2, 3, 4, 5, 6), function($num) { return $num % 2 == 0; });
        $this->assertEquals(array(2, 4, 6), $_::toArray($evens, false), 'selected each even number');

        $evens = $_::select(array(1, 2, 3, 4, 5, 6), function($num) { return $num % 2 == 0; });
        $this->assertEquals(array(2, 4, 6), $_::toArray($evens, false), 'aliased as "select"');
    }

    /**
     * @dataProvider provider
     */
    public function testReject($_)
    {
        $odds = $_::reject(array(1, 2, 3, 4, 5, 6), function($num) { return $num % 2 == 0; });
        $this->assertEquals(array(1, 3, 5), $_::toArray($odds, false), 'rejected each even number');
    }

    /**
     * @dataProvider provider
     */
    public function testAll($_)
    {
        $this->assertTrue($_::all(array(), "$_::identity"), 'the empty set');
        $this->assertTrue($_::all(array(true, true, true), "$_::identity"), 'all true values');
        $this->assertFalse($_::all(array(true, false, true), "$_::identity"), 'one false value');
        $this->assertTrue($_::all(array(0, 10, 28), function($num) { return $num % 2 == 0; }), 'even numbers');
        $this->assertFalse($_::all(array(0, 11, 28), function($num) { return $num % 2 == 0; }), 'an odd number');
        $this->assertTrue($_::all(array(1), "$_::identity") === true, 'cast to boolean - true');
        $this->assertTrue($_::all(array(0), "$_::identity") === false, 'cast to boolean - false');
        $this->assertTrue($_::every(array(true, true, true), "$_::identity"), 'aliased as "every"');
        $this->assertFalse($_::all(array(null, null, null), "$_::identity"), 'works with arrays of null');
    }

    /**
     * @dataProvider provider
     */
    public function testAny($_)
    {
        $this->assertFalse($_::any(array()), 'the empty set');
        $this->assertFalse($_::any(array(false, false, false)), 'all false values');
        $this->assertTrue($_::any(array(false, false, true)), 'one true value');
        $this->assertTrue($_::any(array(null, 0, 'yes', false)), 'a string');
        $this->assertFalse($_::any(array(null, 0, '', false)), 'falsy values');
        $this->assertFalse($_::any(array(1, 11, 29), function($num) { return $num % 2 == 0; }), 'all odd numbers');
        $this->assertTrue($_::any(array(1, 10, 29), function($num) { return $num % 2 == 0; }), 'an even number');
        $this->assertTrue($_::any(array(1), "$_::identity") === true, 'cast to boolean - true');
        $this->assertTrue($_::any(array(0), "$_::identity") === false, 'cast to boolean - false');
        $this->assertTrue($_::some(array(false, false, true)), 'aliased as "some"');
    }

    /**
     * @dataProvider provider
     */
    public function testContains($_)
    {
        $this->assertTrue($_::contains(array(1, 2, 3), 2), 'two is in the array');
        $this->assertFalse($_::contains(array(1, 3, 9), 2), 'two is not in the array');
        $this->assertTrue($_::contains(array('moe' => 1, 'larry' => 3, 'curly' => 9), 3) === true, '$_::include on objects checks their values');
    }

    /**
     * @dataProvider provider
     */
    public function testInvoke($_)
    {
        $list = array($_::chain(array(5, 1, 7)), $_::chain(array(3, 2, 1)));
        $result = $_::chain($list)->invoke('sort')->map("$_::toArray");
        $this->assertEquals(array(array(1, 5, 7), array(1, 2, 3)), $_::toArray($result), 'first array sorted');
    }

    /**
     * @dataProvider provider
     */
    public function testPluck($_)
    {
        $people = array(
            array('name' => 'moe', 'age' => 30),
            array('name' => 'curly', 'age' => 50)
        );
        $result = $_::pluck($people, 'name');
        $this->assertEquals(array('moe', 'curly'), $_::toArray($result), 'pulls names out of objects');
    }

    /**
     * @dataProvider provider
     */
    public function testWhere($_)
    {
        $list = array(
            array('a' => 1, 'b' => 2),
            array('a' => 2, 'b' => 2),
            array('a' => 1, 'b' => 3),
            array('a' => 1, 'b' => 4)
        );
        $result = $_::chain($list)->where(array('a' => 1))->toArray();
        $this->assertCount(3, $result);
        $this->assertEquals(array('a' => 1, 'b' => 4), $_::last($result));

        $result = $_::chain($list)->where(array('b' => 2))->toArray();
        $this->assertCount(2, $result);
        $this->assertEquals(array('a' => 1, 'b' => 2), $_::first($result));
    }

    /**
     * @dataProvider provider
     */
    public function testMax($_)
    {
        $this->assertEquals(3, $_::max(array(1, 2, 3)), 'can perform a regular max()');

        $neg = $_::max(array(1, 2, 3), function($num) { return -$num; });
        $this->assertEquals(1, $neg, 'can perform a computation-based max');

        $this->assertEquals(-INF, $_::max(array()), 'Maximum value of an empty array');
        $this->assertEquals('a', $_::max(array('a' => 'a')), 'Maximum value of a non-numeric collection');

        $this->assertEquals(299999, $_::max($_::range(1, 300000)), 'Maximum value of a too-big array');
    }

    /**
     * @dataProvider provider
     */
    public function testMin($_)
    {
        $this->assertEquals(1, $_::min(array(1, 2, 3)), 'can perform a regular min()');

        $neg = $_::min(array(1, 2, 3), function($num) { return -$num; });
        $this->assertEquals(3, $neg, 'can perform a computation-based min');

        $this->assertEquals(INF, $_::min(array()), 'Minimum value of an empty object');
        $this->assertEquals('a', $_::min(array('a' => 'a')), 'Minimum value of a non-numeric collection');

        $now = new DateTime();
        $now->setTimestamp(9999999999);
        $then = new DateTime();
        $then->setTimestamp(0);
        $this->assertEquals($then, $_::min(array($now, $then)));
        $this->assertEquals($then, $_::min(array($then, $now)));

        $this->assertEquals(1, $_::min($_::range(1, 300000)), 'Minimum value of a too-big array');
    }

    /**
     * @dataProvider provider
     */
    public function testSortBy($_)
    {
        $people = array(
            array('name' => 'curly', 'age' => 50),
            array('name' => 'moe', 'age' => 30)
        );
        $people = $_::sortBy($people, function($person) { return $person['age']; });
        $result = $_::pluck($people, 'name');
        $this->assertEquals(array('moe', 'curly'), $_::toArray($result), 'stooges sorted by age');

        $list = array(null, 4, 1, null, 3, 2);
        $sorted = $_::sortBy($list, "$_::identity");
        $this->assertEquals(array(null, null, 1, 2, 3, 4), $_::toArray($sorted), 'sortBy with undefined values');

        $list = array('one', 'two', 'three', 'four', 'five');
        $sorted = $_::sortBy($list, function($str) { return strlen($str); });
        $this->assertEquals(array('one', 'two', 'four', 'five', 'three'), $_::toArray($sorted), 'sorted by length');

        $collection = array(
            array(null, 1), array(null, 2),
            array(null, 3), array(null, 4),
            array(null, 5), array(null, 6),
            array(1, 1), array(1, 2),
            array(1, 3), array(1, 4),
            array(1, 5), array(1, 6),
            array(2, 1), array(2, 2),
            array(2, 3), array(2, 4),
            array(2, 5), array(2, 6)
        );
        $actual = $_::sortBy($collection, function($pair) {
            return $pair[0];
        });
        $this->assertEquals($_::toArray($actual), $collection,  'sortBy should be stable');
    }

    /**
     * @dataProvider provider
     */
    public function testGroupBy($_)
    {
        $parity = $_::groupBy(array(1, 2, 3, 4, 5, 6), function($num){ return $num % 2; });
        $this->assertArrayHasKey(0, $parity, 'created a group for each value');
        $this->assertArrayHasKey(1, $parity, 'created a group for each value');

        $this->assertEquals(array(1, 3, 5), $parity[1], 'put each even number in the right group');
        $this->assertEquals(array(2, 4, 6), $parity[0], 'put each even number in the right group');

        $list = array('one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten');
        $result = $_::groupBy($list, function($x) { return strlen($x); });
        $shouldBe = array(
            3 => array('one', 'two', 'six', 'ten'),
            4 => array('four', 'five', 'nine'),
            5 => array('three', 'seven', 'eight'),
        );
        $this->assertEquals($shouldBe, $result);

        $grouped = $_::groupBy(array(4.2, 6.1, 6.4), function($num) {
            return floor($num) > 4 ? 'one' : 'two';
        });
        $this->assertEquals(array(6.1, 6.4), $grouped['one']);
        $this->assertEquals(array(4.2), $grouped['two']);

        $array = array(1, 2, 1, 2, 3);
        $grouped = $_::groupBy($array);
        $this->assertCount(2, $grouped['1']);
        $this->assertCount(1, $grouped['3']);
    }

    /**
     * @dataProvider provider
     */
    public function testCountBy($_)
    {
        $parity = $_::countBy(array(1, 2, 3, 4, 5), function($num){ return $num % 2; });
        $this->assertEquals(2, $parity[0]);
        $this->assertEquals(3, $parity[1]);

        $list = array('one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten');
        $grouped = $_::countBy($list, function($x) { return strlen($x); });
        $this->assertEquals(4, $grouped['3']);
        $this->assertEquals(3, $grouped['4']);
        $this->assertEquals(3, $grouped['5']);

        $grouped = $_::countBy(array(4.2, 6.1, 6.4), function($num) {
            return floor($num) > 4 ? 'one' : 'two';
        });
        $this->assertEquals(2, $grouped['one']);
        $this->assertEquals(1, $grouped['two']);

        $array = array(1, 2, 1, 2, 3);
        $grouped = $_::countBy($array);
        $this->assertEquals(2, $grouped['1']);
        $this->assertEquals(1, $grouped['3']);
    }

    /**
     * @dataProvider provider
     */
    public function testSortedIndex($_)
    {
        $numbers = array(10, 20, 30, 40, 50);
        $indexForNum = $_::sortedIndex($numbers, 35);
        $this->assertEquals(3, $indexForNum, '35 should be inserted at index 3');

        $indexFor30 = $_::sortedIndex($numbers, 30);
        $this->assertEquals(2, $indexFor30, '30 should be inserted at index 2');

        $objects = array(
            array('x' => 10),
            array('x' => 20),
            array('x' => 30),
            array('x' => 40),
        );
        $iterator = function($obj) { return $obj['x']; };
        $this->assertEquals(2, $_::sortedIndex($objects, array('x' => 25), $iterator));
        $this->assertEquals(3, $_::sortedIndex($objects, array('x' => 35), 'x'));

        $context = array(1 => 2, 2 => 3, 3 => 4);
        $iterator = function($i) use ($context) { return $context[$i]; };
        $this->assertEquals(1, $_::sortedIndex(array(1, 3), 2, $iterator));
    }

    /**
     * @dataProvider provider
     */
    public function testShuffle($_)
    {
        $numbers = $_::toArray($_::range(10));
        $shuffled = $_::chain($numbers)->shuffle()->sort()->toArray()->value();
        $this->assertEquals($numbers, $shuffled, 'contains the same members before and after shuffle');
    }

    /**
     * @dataProvider provider
     */
    public function testToArray($_)
    {
        $array = array(1, 2, 3);
        $this->assertEquals($array, $_::toArray($array), 'cloned array contains same elements');

        $numbers = $_::toArray(array('one' => 1, 'two' => 2, 'three' => 3));
        $this->assertEquals($numbers, $_::toArray($numbers, true), 'object flattened into array');
        $this->assertEquals(array(1, 2, 3), $_::toArray($numbers, false), 'object flattened into array');
    }

    /**
     * @dataProvider provider
     */
    public function testSize($_)
    {
        $this->assertEquals(3, $_::size(array('one' => 1, 'two' => 2, 'three' => 3)), 'can compute the size of an object');
        $this->assertEquals(3, $_::size($_::range(3)), 'can compute the size of an array');
        $this->assertEquals(5, $_::size('hello'), 'can compute the size of a string');
        $this->assertEquals(0, $_::size(null), 'handles nulls');
    }

    /**
     * @dataProvider provider
     */
    public function testSpan($_)
    {
        $result = $_::span(array(1, 2, 3, 4, 1, 2, 3, 4), function($x) {
            return $x < 3;
        });
        $shouldBe = array(array(1, 2), array(3, 4, 1, 2, 3, 4));
        $this->assertEquals($shouldBe, $result);

        $result = $_::span(array(1, 2, 3), function($x) {
            return $x < 9;
        });
        $shouldBe = array(array(1, 2, 3), array());
        $this->assertEquals($shouldBe, $result);

        $result = $_::span(array(1, 2, 3), function($x) {
            return $x < 0;
        });
        $shouldBe = array(array(), array(1, 2, 3));
        $this->assertEquals($shouldBe, $result);

        $result = $_::span(array(), function($x) { return $x < 3; });
        $this->assertEquals(array(array(), array()), $result, 'empty array');
    }
}
