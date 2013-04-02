<?php

use Underbar\Option;

class OptionTest extends PHPUnit_Framework_TestCase
{
    public function testFromValue()
    {
        $this->assertTrue(Option::fromValue(1)->isDefined());
        $this->assertFalse(Option::fromValue(0, 0)->isDefined());
        $this->assertFalse(Option::fromValue(null)->isDefined());
    }

    public function testGet()
    {
        $this->assertEquals(1, Option::fromValue(1)->get());

        $this->setExpectedException('OutOfRangeException');
        Option::fromValue(null)->get();
    }

    public function testGetOrElse()
    {
        $this->assertEquals(1, Option::fromValue(1)->getOrElse(0));
        $this->assertEquals(0, Option::fromValue(null)->getOrElse(0));
    }

    public function testGetIterator()
    {
        $this->assertEquals(array(1), iterator_to_array(Option::fromValue(1)));
        $this->assertEmpty(iterator_to_array(Option::fromValue(null)));
    }

    public function testIsEmpty()
    {
        $this->assertFalse(Option::fromValue(1)->isEmpty());
        $this->assertTrue(Option::fromValue(null)->isEmpty());
    }

    public function testIsDefined()
    {
        $this->assertTrue(Option::fromValue(1)->isDefined());
        $this->assertFalse(Option::fromValue(null)->isDefined());
    }

    public function testMap()
    {
        $square = function($x) { return $x * $x; };
        $this->assertEquals(4, Option::fromValue(2)->map($square)->orNull());
        $this->assertNull(Option::fromValue(null)->map($square)->orNull());
    }

    public function testExists()
    {
        $odd = function($x) { return $x % 2 === 0; };
        $this->assertFalse(Option::fromValue(1)->exists($odd));
        $this->assertTrue(Option::fromValue(2)->exists($odd));
        $this->assertFalse(Option::fromValue(null)->exists($odd));
    }

    public function testFilter()
    {
        $odd = function($x) { return $x % 2 === 0; };
        $this->assertFalse(Option::fromValue(1)->filter($odd)->isDefined());
        $this->assertTrue(Option::fromValue(2)->filter($odd)->isDefined());
        $this->assertFalse(Option::fromValue(null)->filter($odd)->isDefined());
    }

    public function testFilterNot()
    {
        $odd = function($x) { return $x % 2 === 0; };
        $this->assertTrue(Option::fromValue(1)->filterNot($odd)->isDefined());
        $this->assertFalse(Option::fromValue(2)->filterNot($odd)->isDefined());
        $this->assertFalse(Option::fromValue(null)->filterNot($odd)->isDefined());
    }

    public function testFlatMap()
    {
        $odd = function($x) {
            return $x % 2 === 0
                ? Option::fromValue($x + 1)
                : Option\None::instance();
        };
        $this->assertNull(Option::fromValue(1)->flatMap($odd)->orNull());
        $this->assertEquals(3, Option::fromValue(2)->flatMap($odd)->orNull());
        $this->assertNull(Option::fromValue(null)->flatMap($odd)->orNull());
    }

    public function testOrElse()
    {
        $this->assertEquals(1, Option::fromValue(1)->orElse(Option::fromValue(2))->orNull());
        $this->assertEquals(2, Option::fromValue(null)->orElse(Option::fromValue(2))->orNull());
        $this->assertTrue(Option::fromValue(null)->orElse(Option::fromValue(null))->isEmpty());
    }

    public function testCount()
    {
        $this->assertEquals(1, count(Option::fromValue(1)));
        $this->assertEquals(0, count(Option::fromValue(null)));
    }
}
