<?php

/**
 * @requires PHP 5.4
 */
class EnumerableTest extends PHPUnit_Framework_TestCase
{
    public function testMap()
    {
        require(__DIR__ . '/MyArray.php');

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
}
