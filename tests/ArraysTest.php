<?php

class ArraysTest extends Underbar_TestCase
{
    /**
     * @dataProvider provider
     */
    public function testFirst($_)
    {
        $result = $_::first(array(1, 2, 3));
        $this->assertEquals(1, $result, 'can pull out the first element of an array');

        $result = $_::first(array(1, 2, 3), 0);
        $this->assertEquals(array(), $_::toArray($result), 'can pass an index to first');

        $result = $_::first(array(1, 2, 3), 2);
        $this->assertEquals(array(1, 2), $_::toArray($result), 'can pass an index to first');

        $result = $_::first(array(1, 2, 3), 5);
        $this->assertEquals(array(1, 2, 3), $_::toArray($result), 'can pass an index to first');

        $result = $_::map(array(array(1, 2, 3), array(1, 2, 3)), "$_::first");
        $this->assertEquals(array(1, 1), $_::toArray($result), 'works well with _.map');

        $result = $_::take(array(1, 2, 3), 2);
        $this->assertEquals(array(1, 2), $_::toArray($result), 'aliased as take');
    }

    /**
     * @dataProvider provider
     */
    public function testRest($_)
    {
        $numbers = array(1, 2, 3, 4);

        $result = $_::rest($numbers);
        $this->assertEquals(array(2, 3, 4), $_::toArray($result), 'working rest()');

        $result = $_::rest($numbers, 0);
        $this->assertEquals(array(1, 2, 3, 4), $_::toArray($result), 'working rest(0)');

        $result = $_::rest($numbers, 2);
        $this->assertEquals(array(3, 4), $_::toArray($result), 'rest can take an index');

        $result = $_::tail($numbers);
        $this->assertEquals(array(2, 3, 4), $_::toArray($result), 'aliased as tail');

        $result = $_::map(array(array(1, 2, 3), array(1, 2, 3)), "$_::rest");
        $this->assertEquals(array(2, 3, 2, 3), $_::toArray($_::flatten($result)), 'works well with _.map');

        $result = $_::drop($numbers);
        $this->assertEquals(array(2, 3, 4), $_::toArray($result), 'aliased as drop');
    }

    /**
     * @dataProvider provider
     */
    public function testTakeWhile($_)
    {
        $result = $_::takeWhile(array(1, 2, 3, 4, 5, 1, 2, 3), function($x) {
            return $x < 3;
        });
        $this->assertEquals(array(1, 2), $_::toArray($result, false));

        $result = $_::takeWhile(array(1, 2, 3), function($x) {
            return $x < 9;
        });
        $this->assertEquals(array(1, 2, 3), $_::toArray($result));

        $result = $_::takeWhile(array(1, 2, 3), function($x) {
            return $x < 0;
        });
        $this->assertEquals(array(), $_::toArray($result, false));

        $result = $_::takeWhile(array(), function($x) {
            return $x > 0;
        });
        $this->assertEquals(array(), $_::toArray($result, false));
    }

    /**
     * @dataProvider provider
     */
    public function testDropWhile($_)
    {
        $result = $_::dropWhile(array(1, 2, 3, 4, 5, 1, 2, 3), function($x) {
            return $x < 3;
        });
        $this->assertEquals(array(3, 4, 5, 1, 2, 3), $_::toArray($result, false));

        $result = $_::dropWhile(array(1, 2, 3), function($x) {
            return $x < 9;
        });
        $this->assertEquals(array(), $_::toArray($result));

        $result = $_::dropWhile(array(1, 2, 3), function($x) {
            return $x < 0;
        });
        $this->assertEquals(array(1, 2, 3), $_::toArray($result, false));

        $result = $_::dropWhile(array(), function($x) {
            return $x > 0;
        });
        $this->assertEquals(array(), $_::toArray($result, false));
    }

    /**
     * @dataProvider provider
     */
    public function testInitial($_)
    {
        $result = $_::initial(array(1, 2, 3, 4, 5));
        $this->assertEquals(array(1, 2, 3, 4), $_::toArray($result), 'working initial()');

        $result = $_::initial(array(1, 2, 3, 4), 2);
        $this->assertEquals(array(1, 2), $_::toArray($result), 'initial can take an index');

        $result = $_::map(array(array(1, 2, 3), array(1, 2, 3)), "$_::initial");
        $this->assertEquals(array(1, 2, 1, 2), $_::toArray($_::flatten($result)), 'initial works with _.map');
    }

    /**
     * @dataProvider provider
     */
    public function testLast($_)
    {
        $result = $_::last(array(1, 2, 3));
        $this->assertEquals(3, $result, 'can pull out the last element of an array');

        $result = $_::last(array(1, 2, 3), 0);
        $this->assertEquals(array(), $_::toArray($result), 'can pass an index to last');

        $result = $_::last(array(1, 2, 3), 2);
        $this->assertEquals(array(2, 3), $_::toArray($result), 'can pass an index to last');

        $result = $_::last(array(1, 2, 3), 5);
        $this->assertEquals(array(1, 2, 3), $_::toArray($result), 'can pass an index to last');

        $result = $_::map(array(array(1, 2, 3), array(1, 2, 3)), "$_::last");
        $this->assertEquals(array(3, 3), $_::toArray($result), 'works well with _.map');
    }

    /**
     * @dataProvider provider
     */
    public function testCompact($_)
    {
        $result = $_::compact(array(0, 1, false, 2, false, 3));
        $this->assertCount(3, $_::toArray($result), 'can trim out all falsy values');
    }

    /**
     * @dataProvider provider
     */
    public function testFlatten($_)
    {
        $list = array(1, array(2), array(3, array(array(array(4)))));

        $result = $_::flatten($list);
        $shouldBe = array(1, 2, 3, 4);
        $this->assertEquals($shouldBe, $_::toArray($result), 'can flatten nested arrays');

        $result = $_::flatten($list, true);
        $shouldBe = array(1, 2, 3, array(array(array(4))));
        $this->assertEquals($shouldBe, $_::toArray($result), 'can shallowly flatten nested arrays');
    }

    /**
     * @dataProvider provider
     */
    public function testWithout($_)
    {
        $list = array(1, 2, 1, 0, 3, 1, 4);
        $result = $_::values($_::without($list, 0, 1));
        $shouldBe = array(2, 3, 4);
        $this->assertEquals($shouldBe, $_::toArray($result), 'can remove all instances of an object');

        $list = array(array('one' => 1), array('two' => 2));
        $result = $_::without($list, array('one' => 1), 1);
        $this->assertCount(1, $_::toArray($result), 'uses real object identity for comparisons.');

        $result = $_::without($list, $list[0]);
        $this->assertCount(1, $_::toArray($result), 'ditto.');
    }

    /**
     * @dataProvider provider
     */
    public function testUniq($_)
    {
        $list = array(1, 2, 1, 3, 1, 4);
        $shouldBe = array(1, 2, 3, 4);
        $this->assertEquals($shouldBe, $_::toArray($_::uniq($list), false), 'can find the unique values');
        $this->assertEquals($shouldBe, $_::toArray($_::unique($list), false), 'alias as unique');

        $list = array(array('name' => 'moe'), array('name' => 'curly'), array('name' => 'larry'), array('name' => 'curly'));
        $iterator = function($value) { return $value['name']; };
        $result = $_::map($_::uniq($list, $iterator), $iterator);
        $shouldBe = array('moe', 'curly', 'larry');
        $this->assertEquals($shouldBe, $_::toArray($result, false), 'can find the unique values of an array using a custom iterator');

        $list = array(1, 2, 2, 3, 4, 4);
        $result = $_::uniq($list, function($value) { return $value + 1; });
        $shouldBe = array(1, 2, 3, 4);
        $this->assertEquals($shouldBe, $_::toArray($result, false), 'iterator works');
    }

    /**
     * @dataProvider provider
     */
    public function testIntersection($_)
    {
        $stooges = array('moe', 'curly', 'larry');
        $leaders = array('moe', 'groucho');
        $shouldBe = array('moe');
        $this->assertEquals($shouldBe, $_::intersection($stooges, $leaders), 'can take the set intersection of two arrays');
    }

    /**
     * @dataProvider provider
     */
    public function testUnion($_)
    {
        $result = $_::union(array(1, 2, 3), array(2, 30, 1), array(1, 40));
        $shouldBe = array(1, 2, 3, 30, 40);
        $this->assertEquals($shouldBe, $_::toArray($result, false), 'takes the union of a list of arrays');

        $result = $_::union(array(1, 2, 3), array(2, 30, 1), array(1, 40, array(1)));
        $shouldBe = array(1, 2, 3, 30, 40, array(1));
        $this->assertEquals($shouldBe, $_::toArray($result, false), 'takes the union of a list of nested arrays');
    }

    /**
     * @dataProvider provider
     */
    public function testDifference($_)
    {
        $result = $_::difference(array(1, 2, 3), array(2, 30, 40));
        $shouldBe = array(1, 3);
        $this->assertEquals($shouldBe, $_::toArray($result, false), 'takes the difference of two arrays');

        $result = $_::difference(array(1, 2, 3, 4), array(2, 30, 40), array(1, 11, 111));
        $shouldBe = array(3, 4);
        $this->assertEquals($shouldBe, $_::toArray($result, false), 'takes the difference of three arrays');
    }

    /**
     * @dataProvider provider
     */
    public function testZip($_)
    {
        $names = array('moe', 'larry', 'curly');
        $ages = array(30, 40, 50, 60);
        $leaders = array(true, false);

        $stooges = $_::zip($names, $ages, $leaders);
        $shouldBe = array(array('moe', 30, true), array('larry', 40, false));
        $this->assertEquals($shouldBe, $_::toArray($stooges), 'zipped together arrays of different lengths');
    }

    /**
     * @dataProvider provider
     */
    public function testZipWith($_)
    {
        $plus = function($x, $y) {
            return $x + $y;
        };

        $result = $_::zipWith(array(1, 2, 3), array(4, 5, 6), $plus);
        $this->assertEquals(array(5, 7, 9), $_::toArray($result));

        $result = $_::zipWith(array(1, 2, 3), array(4, 5), $plus);
        $this->assertEquals(array(5, 7), $_::toArray($result));

        $result = $_::zipWith(array(1, 2), array(4, 5, 6), $plus);
        $this->assertEquals(array(5, 7), $_::toArray($result));

        $result = $_::zipWith(array(), array(), $plus);
        $this->assertEquals(array(), $_::toArray($result));

        if ($_ === 'Underbar\\Strict') {
            $this->setExpectedException('OverflowException');
        }
        $result = $_::zipWith(array(1, 2, 3), $_::cycle(array(1, 0)), $plus);
        $this->assertEquals(array(2, 2, 4), $_::toArray($result));

        $result = $_::zipWith($_::range(1, INF), $_::cycle(array(1, 0)), $plus);
        $this->assertEquals(array(2, 2, 4), $_::toArray($_::take($result, 3)));

        $productPlus = function($x, $y, $z) {
            return $x * $y + $z;
        };

        $result = $_::zipWith(array(1, 2, 3), array(4, 5, 6), array(7, 8, 9), $productPlus);
        $this->assertEquals(array(11, 18, 27), $_::toArray($result));

        $result = $_::zipWith(array(1, 2, 3), array(4, 5, 6), array(7, 8), $productPlus);
        $this->assertEquals(array(11, 18), $_::toArray($result));

        $result = $_::zipWith(array(1, 2, 3), array(4, 5), array(7, 8, 9), $productPlus);
        $this->assertEquals(array(11, 18), $_::toArray($result));

        $result = $_::zipWith(array(1, 2), array(4, 5, 6), array(7, 8, 9), $productPlus);
        $this->assertEquals(array(11, 18), $_::toArray($result));

        $result = $_::zipWith(array(), array(), array(), $productPlus);
        $this->assertEquals(array(), $_::toArray($result));

        $result = $_::zipWith(array(1, 2, 3), array(4, 5, 6), $_::range(7, INF), $productPlus);
        $this->assertEquals(array(11, 18, 27), $_::toArray($result));

        $result = $_::zipWith(array(1, 2, 3), $_::range(4, INF), $_::range(7, INF), $productPlus);
        $this->assertEquals(array(11, 18, 27), $_::toArray($result));

        $result = $_::zipWith($_::range(1, INF), $_::range(4, INF), $_::range(7, INF), $productPlus);
        $this->assertEquals(array(11, 18, 27), $_::toArray($_::take($result, 3)));
    }

    /**
     * @dataProvider provider
     */
    public function testObject($_)
    {
        $result = $_::object(array('moe', 'larry', 'curly'), array(30, 40, 50));
        $shouldBe = array('moe' => 30, 'larry' => '40', 'curly' => 50);
        $this->assertEquals($shouldBe, $_::toArray($result, true), 'two arrays zipped together into an object');

        $result = $_::object(array(array('one', 1), array('two', 2), array('three', 3)));
        $shouldBe = array('one' =>  1, 'two' =>  2, 'three' =>  3);
        $this->assertEquals($shouldBe, $_::toArray($result, true), 'an array of pairs zipped together into an object');

        $stooges = array('moe' =>  30, 'larry' => 40, 'curly' =>  50);
        $result = $_::object($_::pairs($stooges));
        $this->assertEquals($stooges, $_::toArray($result, true), 'an object converted to pairs and back to an object');
    }

    /**
     * @dataProvider provider
     */
    public function testIndexOf($_)
    {
        $numbers = array(1, 2, 3);
        $this->assertEquals(1, $_::indexOf($numbers, 2), 'can compute indexOf');

        $numbers = array(10, 20, 30, 40, 50);
        $this->assertEquals(-1, $_::indexOf($numbers, 35, true), '35 is not in the list');
        $this->assertEquals(3, $_::indexOf($numbers, 40, true), '40 is not in the list');

        $numbers = array(1, 40, 40, 40, 40, 40, 40, 40, 50, 60, 70);
        $this->assertEquals(1, $_::indexOf($numbers, 40, true), '40 is not in the list');

        $numbers = array(1, 2, 3, 1, 2, 3, 1, 2, 3);
        $this->assertEquals(7, $_::indexOf($numbers, 2, 5), 'supports the fromIndex argument');
    }

    /**
     * @dataProvider provider
     */
    public function testLastIndexOf($_)
    {
        $numbers = array(1, 0, 1);
        $this->assertEquals(2, $_::lastIndexOf($numbers, 1));

        $numbers = array(1, 0, 1, 0, 0, 1, 0, 0, 0);
        $this->assertEquals(5, $_::lastIndexOf($numbers, 1), 'can compute lastIndexOf');
        $this->assertEquals(8, $_::lastIndexOf($numbers, 0), 'can compute lastIndexOf');

        $numbers = array(1, 2, 3, 1, 2, 3, 1, 2, 3);
        $this->assertEquals(1, $_::lastIndexOf($numbers, 2, 2), 'supports the fromIndex argument');
    }

    /**
     * @dataProvider provider
     */
    public function testRange($_)
    {
        $result = $_::range(0);
        $this->assertEmpty($_::toArray($result), 'range with 0 as a first argument generates an empty array');

        $result = $_::range(4);
        $shouldBe = array(0, 1, 2, 3);
        $this->assertEquals($shouldBe, $_::toArray($result), 'range with a single positive argument generates an array of elements 0, 1, 2, ..., n-1');

        $result = $_::range(5, 8);
        $shouldBe = array(5, 6, 7);
        $this->assertEquals($shouldBe, $_::toArray($result), 'range with two arguments a & b, a < b generates an array of elements a, a + 1, a + 2, ..., b - 2, b - 1');

        $result = $_::range(8, 5);
        $this->assertEmpty($_::toArray($result), 'range with two arguments a & b, b < a generates an empty array');

        $result = $_::range(3, 10, 3);
        $shouldBe = array(3, 6, 9);
        $this->assertEquals($shouldBe, $_::toArray($result), 'range with three arguments a & b & c, c < b - a, a < b generates an array of elements a, a + c, a + 2c, ..., b - (multiplier of a) < c');

        $result = $_::range(3, 10, 15);
        $shouldBe = array(3);
        $this->assertEquals($shouldBe, $_::toArray($result), 'range with three arguments a & b & c, c > b - a, a < b generates an array with a single element, equal to a');

        $result = $_::range(12, 7, -2);
        $shouldBe = array(12, 10, 8);
        $this->assertEquals($shouldBe, $_::toArray($result), 'range with three arguments a & b & c, a > b, c < 0 generates an array of elements a,a-c,a-2c and ends with the number not less than b');

        $result = $_::range(0, -10, -1);
        $shouldBe = array(0, -1, -2, -3, -4, -5, -6, -7, -8, -9);
        $this->assertEquals($shouldBe, $_::toArray($result), 'final example in the Python docs');
    }

    /**
     * @dataProvider provider
     */
    public function testCycle($_)
    {
        $result = $_::cycle(array(1, 2), 2);
        $this->assertEquals(array(1, 2, 1, 2), $_::toArray($result));

        $result = $_::cycle(array(1, 2), 1);
        $this->assertEquals(array(1, 2), $_::toArray($result));

        $result = $_::cycle(array(1, 2), 0);
        $this->assertEmpty($_::toArray($result));

        $result = $_::cycle(array(), 2);
        $this->assertEmpty($_::toArray($result));

        if ($_ === 'Underbar\\Strict') {
            $this->setExpectedException('OverflowException');
        }
        $result = $_::cycle(array(1, 2));
        $this->assertEquals(array(1, 2, 1, 2, 1), $_::toArray($_::take($result, 5)));
    }

    /**
     * @dataProvider provider
     */
    public function testRepeat($_)
    {
        $result = $_::repeat(1, 2);
        $this->assertEquals(array(1, 1), $_::toArray($result));

        $result = $_::repeat(array(1, 2), 2);
        $this->assertEquals(array(array(1, 2), array(1, 2)), $_::toArray($result));

        $result = $_::repeat(1, 0);
        $this->assertEmpty($_::toArray($result));

        if ($_ === 'Underbar\\Strict') {
            $this->setExpectedException('OverflowException');
        }
        $result = $_::repeat(1);
        $this->assertEquals(array(1, 1, 1, 1), $_::toArray($_::take($result, 4)));
    }

    /**
     * @dataProvider provider
     */
    public function testIterate($_)
    {
        if ($_ === 'Underbar\\Strict') {
            $this->setExpectedException('OverflowException');
        }

        $result = $_::iterate(2, function($x) { return $x * $x; });
        $shouldBe = array(2, 4, 16, 256);
        $this->assertEquals($shouldBe, $_::toArray($_::take($result, 4)), 'square');

        $result = $_::chain(array())
            ->iterate(function($xs) { return range(1, count($xs) + 1); })
            ->map(function($xs) { return array_product($xs); })
            ->take(10);
        $shouldBe = array(1, 1, 2, 6, 24, 120, 720, 5040, 40320, 362880);
        $this->assertEquals($shouldBe, $_::toArray($result), 'factorial');

        $result = $_::chain(array(0, 1))
            ->iterate(function($xs) {
                return array($xs[1], $xs[0] + $xs[1]);
            })
            ->map(array($_, 'first'))
            ->take(10);
        $shouldBe = array(0, 1, 1, 2, 3, 5, 8, 13, 21, 34);
        $this->assertEquals($shouldBe, $_::toArray($result), 'fibonacci numbers');
    }
}

// __END__
// vim: expandtab softtabstop=4 shiftwidth=4
