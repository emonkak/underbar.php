<?php

class PararellTest extends Underbar_TestCase
{
    /**
     * @dataProvider provider
     */
    public function testParMap($_)
    {
        if (!function_exists('pcntl_fork')) {
            return;
        }

        $time = microtime(true);
        $sum = $_::chain($_::range(10))
            ->parMap(function($x) {
                usleep(100000);
                return $x * 100;
            }, 10)
            ->sum();
        $this->assertEquals(4500, $sum, 'sum numbers');

        $time = microtime(true) - $time;
        $this->assertLessThan(1.0, $time, 'work to parallel');
    }
}
