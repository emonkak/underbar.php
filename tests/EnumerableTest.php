<?php

/**
 * @requires PHP 5.4
 */
class EnumerableTest extends PHPUnit_Framework_TestCase
{
    public function testMap()
    {
        $xs = new MyArray(array(1, 2, 3));
        $twice = function($x) { return $x * 2; };
        $shouldBe = array(2, 4, 6);

        $result = $xs->map($twice);
        $this->assertEquals($shouldBe, $result);

        $result = $xs->chain()->map($twice)->toList()->value();
        $this->assertEquals($shouldBe, $result);

        $result = $xs->lazy()->map($twice)->toList()->value();
        $this->assertEquals($shouldBe, $result);
    }

    public function testPush()
    {
        $xs = new MyArray(array(1, 2));

        $result = $xs->push(3);
        $this->assertEquals(array(1, 2, 3), $result);

        $result = $xs->push(3, 4);
        $this->assertEquals(array(1, 2, 3, 4), $result);
    }
}

if (function_exists('trait_exists')) {
    eval(<<<EOF
class MyArray extends ArrayObject
{
    use Underbar\Enumerable;
}
EOF
);
}
