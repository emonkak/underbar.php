<?php

class ObjectsTest extends Underbar_TestCase
{
    /**
     * @dataProvider provider
     */
    public function testKeys($_)
    {
        $xs = array('one' => 1, 'two' => 2);

        $result = $_::keys($xs);
        $this->assertEquals(array('one', 'two'), $_::toList($result), 'can extract the keys from an object');

        $result = $_::keys(new ArrayIterator($xs));
        $this->assertEquals(array('one', 'two'), $_::toList($result), 'works well Iterator');

        $result = $_::keys(array(1 => 0));
        $this->assertEquals(array(1), $_::toList($result), 'is not fooled by sparse arrays');

    }

    /**
     * @dataProvider provider
     */
    public function testValues($_)
    {
        $xs = array('one' => 1, 'two' => 2);

        $result = $_::values($xs);
        $this->assertEquals(array(1, 2), $_::toList($result), 'can extract the values from an object');

        $result = $_::values(new ArrayIterator($xs));
        $this->assertEquals(array(1, 2), $_::toList($result), 'works well Iterator');
    }

    /**
     * @dataProvider provider
     */
    public function testInvert($_)
    {
        $obj = array('first' => 'Moe', 'second' => 'Larry', 'third' => 'Curly');

        $result = $_::chain($obj)->invert()->keys()->toList();
        $this->assertEquals(array('Moe', 'Larry', 'Curly'), $result, 'can invert an object');

        $result = $_::chain($obj)->invert()->invert()->toArray();
        $this->assertEquals($obj, $result, 'two inverts gets you back where you started');
    }

    /**
     * @dataProvider provider
     */
    public function testExtend($_)
    {
        $result = $_::extend(array(), array('a' => 'b'));
        $this->assertEquals('b', $result['a'], 'can extend an array');

        $result = $_::extend(array('a' => 'x'), array('a' => 'b'));
        $this->assertEquals('b', $result['a'], 'properties in source override destination');

        $result = $_::extend(array('x' => 'x'), array('a' => 'b'));
        $this->assertEquals('x', $result['x'], 'properties not in source dont get overriden');

        $result = $_::extend(array('x' => 'x'), array('a' => 'a'), array('b' => 'b'));
        $this->assertEquals(array('x' => 'x', 'a' => 'a', 'b' => 'b'), $result, 'can extend from multiple source objects');

        $result = $_::extend(array('x' => 'x'), array('a' => 'a', 'x' => 2), array('a' => 'b'));
        $this->assertEquals($result, array('x' => 2, 'a' => 'b'), 'extending from multiple source objects last property trumps');

        $result = $_::extend(array(), array('a' => null, 'b' => null));
        $this->assertEquals(array('a' => null, 'b' => null), $result, 'extend does not copy undefined values');
    }

    /**
     * @dataProvider provider
     */
    public function testGet($_)
    {
        $obj = array('a' => 1, 'b' => 2, 'c' => 3);

        $this->assertEquals(1, $_::get($obj, 'a'), 'works with array');
        $this->assertEquals(2, $_::get(new ArrayIterator($obj), 'b'), 'works with ArrayIterator');
        $this->assertEquals(3, $_::get(new IteratorIterator(new ArrayIterator($obj)), 'c'), 'works with IteratorIterator');

        $this->assertNull($_::get($obj, 'd'), 'undefined key');
        $this->assertNull($_::get(new IteratorIterator(new ArrayIterator($obj)), 'd'), 'undefined key with IteratorIterator');
    }

    /**
     * @dataProvider provider
     */
    public function testGetOrElse($_)
    {
        $obj = array('a' => 1, 'b' => 2, 'c' => 3);

        $this->assertEquals(1, $_::getOrElse($obj, 'a', 10), 'works with array');
        $this->assertEquals(2, $_::getOrElse(new ArrayIterator($obj), 'b', '10'), 'works with ArrayIterator');
        $this->assertEquals(3, $_::getOrElse(new IteratorIterator(new ArrayIterator($obj)), 'c', '10'), 'works with IteratorIterator');

        $this->assertEquals(10, $_::getOrElse($obj, 'd', 10), 'undefined key');
        $this->assertEquals(10, $_::getOrElse(new IteratorIterator(new ArrayIterator($obj)), 'd', '10'), 'undefined key with IteratorIterator');
    }

    /**
     * @dataProvider provider
     */
    public function testPick($_)
    {
        $obj = array('a' => 1, 'b' => 2, 'c' => 3);

        $result = $_::pick($obj, 'a', 'c');
        $this->assertEquals(array('a' => 1, 'c' => 3), $_::toArray($result), 'can restrict properties to those named');

        $result = $_::pick($obj, array('b', 'c'));
        $this->assertEquals(array('b' => 2, 'c' => 3), $_::toArray($result), 'can restrict properties to those named in an array');

        $result = $_::pick($obj, array('a'), 'b');
        $this->assertEquals(array('a' => 1, 'b' => 2), $_::toArray($result), 'can restrict properties to those named in mixed args');
    }

    /**
     * @dataProvider provider
     */
    public function testOmit($_)
    {
        $obj = array('a' => 1, 'b' => 2, 'c' => 3);

        $result = $_::omit($obj, 'b');
        $this->assertEquals(array('a' => 1, 'c' => 3), $_::toArray($result), 'can omit a single named property');

        $result = $_::omit($obj, 'a', 'c');
        $this->assertEquals(array('b' => 2), $_::toArray($result), 'can omit several named properties');

        $result = $_::omit($obj, array('b', 'c'));
        $this->assertEquals(array('a' => 1), $_::toArray($result), 'can omit properties named in an array');
    }

    /**
     * @dataProvider provider
     */
    public function testDefaults($_)
    {
        $options = array(
            'zero' => 0,
            'one' => 1,
            'empty' => "",
            'string' => "string"
        );

        $result = $_::defaults($options, array('zero' => 1, 'one' => 10, 'twenty' => 20));
        $this->assertEquals(0, $result['zero'], 'value exists');
        $this->assertEquals(1, $result['one'], 'value exists');
        $this->assertEquals(20, $result['twenty'], 'default applied');

        $result = $_::defaults(
            $options,
            array('empty' => "full"),
            array('nan' => "nan"),
            array('word' => "word"),
            array('word' => "dog")
        );
        $this->assertEquals('', $result['empty'], 'value exists');
        $this->assertEquals('word', $result['word'], 'new value is added, first one wins');

        $result = $_::defaults(new IteratorIterator(new ArrayIterator($options)), array('zero' => 1, 'one' => 10, 'twenty' => 20));
        $this->assertEquals(0, $result['zero'], 'value exists');
        $this->assertEquals(1, $result['one'], 'value exists');
        $this->assertEquals(20, $result['twenty'], 'value exists');
    }

    /**
     * @dataProvider provider
     */
    public function testTap($_)
    {
        $intercepted = null;
        $interceptor = function($obj) use (&$intercepted) { $intercepted = $obj; };
        $returned = $_::tap(1, $interceptor);
        $this->assertEquals(1, $intercepted, 'passes tapped object to interceptor');
        $this->assertEquals(1, $returned, 'returns tapped object');
    }

    /**
     * @dataProvider provider
     */
    public function testIsEmptry($_)
    {
        $this->assertTrue($_::isEmpty([]), 'with array');
        $this->assertTrue($_::isEmpty(new ArrayIterator([])), 'with Countable object');
        $this->assertTrue($_::isEmpty(new EmptyIterator()), 'with Iterator object');
        $this->assertTrue($_::isEmpty(new InfiniteIterator(new EmptyIterator())), 'with Iterator object');
        $this->assertTrue($_::isEmpty($_::lazy(function() {
            return new EmptyIterator();
        })), 'with IteratorAggregate object');

        $this->assertFalse($_::isEmpty([1]), 'with array');
        $this->assertFalse($_::isEmpty(new ArrayIterator([1])), 'with Countable object');
        $this->assertFalse($_::isEmpty(new InfiniteIterator(new ArrayIterator([1]))), 'with Iterator object');
        $this->assertFalse($_::isEmpty($_::lazy(function() {
            return new ArrayIterator([1]);
        })), 'with IteratorAggregate object');
    }
}

// __END__
// vim: expandtab softtabstop=4 shiftwidth=4
