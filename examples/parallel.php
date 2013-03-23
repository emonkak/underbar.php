<?php

require(__DIR__ . '/../vendor/autoload.php');

use Underbar\Lazy as _;

_::chain(_::range(10))
    ->parallelMap(function($x) { sleep(1); return $x * 100; }, 10)
    ->reduce(function($x, $y) { var_dump($x, $y); return $x + $y; }, 0)
    ->tap('var_export');
