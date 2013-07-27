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

        $result = $xs->chain()->map($twice)->toList()->value();
        $this->assertEquals($shouldBe, $result);
    }

    public function testConcat()
    {
        $xs = new MyArray(array(1, 2));

        $result = $xs->chain($xs)->concat(array(3))->toList()->value();
        $this->assertEquals(array(1, 2, 3), $result);

        $result = $xs->chain(array(3, 4))->concat(array(3, 4))->toList()->value();
        $this->assertEquals(array(1, 2, 3, 4), $result);
    }
}

if (function_exists('trait_exists')) {
    eval(<<<EOF
class MyArray extends ArrayObject
{
    use Underbar\Enumerable;

    public function getUnderbarImpl()
    {
        return 'Underbar\\IteratorImpl';
    }
}
EOF
);
}
