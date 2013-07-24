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

        $result = $_::chain($obj)->invert()->keys()->value();
        $this->assertEquals(array('Moe', 'Larry', 'Curly'), $_::toList($result), 'can invert an object');

        $result = $_::chain($obj)->invert()->invert()->value();
        $this->assertEquals($obj, $_::toArray($result), 'two inverts gets you back where you started');
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

        $returned = $_::chain(array(1, 2, 3))
            ->map(function($n) { return $n * 2; })
            ->max()
            ->tap($interceptor)
            ->value();
        $this->assertTrue(6 === $intercepted && 6 === $returned, 'can use tapped objects in a chain');
    }

    /**
     * @dataProvider provider
     */
    public function testIsArray($_)
    {
        $this->assertTrue($_::isArray(array()));
        $this->assertTrue($_::isArray(new ArrayObject()));
        $this->assertFalse($_::isArray(new EmptyIterator()));
        $this->assertFalse($_::isArray(new StdClass()));
        $this->assertFalse($_::isArray(0));
        $this->assertFalse($_::isArray(null));
    }

    /**
     * @dataProvider provider
     */
    public function testIsTraversable($_)
    {
        $this->assertTrue($_::isTraversable(array()));
        $this->assertTrue($_::isTraversable(new ArrayObject()));
        $this->assertTrue($_::isTraversable(new EmptyIterator()));
        $this->assertFalse($_::isTraversable(new StdClass()));
        $this->assertFalse($_::isTraversable(0));
        $this->assertFalse($_::isTraversable(null));
    }
}

// __END__
// vim: expandtab softtabstop=4 shiftwidth=4
