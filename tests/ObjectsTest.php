<?php

class ObjectsTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provider
     */
    public function testKeys($_)
    {
        $result = $_::keys(array('one' => 1, 'two' => 2));
        $this->assertEquals(array('one', 'two'), $_::toArray($result), 'can extract the keys from an object');

        // the test above is not safe because it relies on for-in enumeration order
        $result = $_::keys(array('1' => 0));
        $this->assertEquals(array(1), $_::toArray($result), 'is not fooled by sparse arrays; see issue #95');

    }

    /**
     * @dataProvider provider
     */
    public function testValues($_)
    {
        $result = $_::values(array('one' => 1, 'two' => 2));
        $this->assertEquals(array(1, 2), $_::toArray($result, true), 'can extract the values from an object');
    }

    /**
     * @dataProvider provider
     */
    public function testInvert($_)
    {
        $obj = array('first' => 'Moe', 'second' => 'Larry', 'third' => 'Curly');

        $result = $_::chain($obj)->invert()->keys()->toArray(true);
        $this->assertEquals(array('Moe', 'Larry', 'Curly'), $result, 'can invert an object');

        $result = $_::chain($obj)->invert()->invert()->toArray(true);
        $this->assertEquals($obj, $result, 'two inverts gets you back where you started');
    }

    /**
     * @dataProvider provider
     */
    public function testExtend($_)
    {
        $result = $_::extend(array(), array('a' => 'b'));
        $this->assertEquals('b', $result['a'], 'can extend an object with the attributes of another');

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

        $result = $_::extend(array(), null, array('a' => 1));
        $this->assertEquals(1, $result['a'], 'should not error on `null` or `undefined` sources');
    }

    /**
     * @dataProvider provider
     */
    public function testPick($_)
    {
        $obj = array('a' => 1, 'b' => 2, 'c' => 3);

        $result = $_::pick($obj, 'a', 'c');
        $this->assertEquals(array('a' => 1, 'c' => 3), $result, 'can restrict properties to those named');

        $result = $_::pick($obj, array('b', 'c'));
        $this->assertEquals(array('b' => 2, 'c' => 3), $result, 'can restrict properties to those named in an array');

        $result = $_::pick($obj, array('a'), 'b');
        $this->assertEquals(array('a' => 1, 'b' => 2), $result, 'can restrict properties to those named in mixed args');
    }

    /**
     * @dataProvider provider
     */
    public function testOmit($_)
    {
        $obj = array('a' => 1, 'b' => 2, 'c' => 3);

        $result = $_::omit($obj, 'b');
        $this->assertEquals(array('a' => 1, 'c' => 3), $result, 'can omit a single named property');

        $result = $_::omit($obj, 'a', 'c');
        $this->assertEquals(array('b' => 2), $result, 'can omit several named properties');

        $result = $_::omit($obj, array('b', 'c'));
        $this->assertEquals(array('a' => 1), $result, 'can omit properties named in an array');
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

        $result = $_::defaults(array(), null, array('a' => 1));
        $this->assertEquals(1, $result['a'], 'should not error on `null` or `undefined` sources');
    }

    /**
     * @dataProvider provider
     */
    public function testDuplicate($_)
    {
        $moe = (object) array('name' => 'moe', 'lucky' => array(13, 27, 34));
        $clone = $_::duplicate($moe);
        $this->assertEquals('moe', $clone->name, 'the clone as the attributes of the original');

        $clone->name = 'curly';
        $this->assertNotEquals($clone->name, $moe->name, 'clones can change shallow attributes without affecting the original');

        $this->assertEquals($_::duplicate(1), 1, 'non objects should not be changed by clone');
        $this->assertEquals($_::duplicate(null), null, 'non objects should not be changed by clone');
    }

    public function provider()
    {
        return provideClasses();
    }
}

// __END__
// vim: expandtab softtabstop=4 shiftwidth=4
