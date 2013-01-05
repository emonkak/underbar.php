<?php

require(__DIR__ . DIRECTORY_SEPARATOR . 'underscore.php');

use Underscore\_;
use Underscore\Underscore as U;

echo _::chain(array(array(10, 11, array(12, 13)), array(array(2, 3, 4, 5))))
  ->flatten()
  ->join()
  ->value(), PHP_EOL;

echo _::chain(_::range(1))
  ->filter(function($x) { return $x % 2 === 0; })
  ->map(function($x) { return $x * 100; })
  ->take(10)
  ->union(_::range(1, 10))
  ->uniq()
  ->initial(5)
  ->join()
  ->value(), PHP_EOL;

echo _::chain(_::zip(_::range(1), _::range(101), _::range(1001)))
  ->take(10)
  ->map(function($xs) { return '['._::join($xs, ' ').']'; })
  ->join()
  ->value(), PHP_EOL;

echo _::chain(_::range(1, 10000))
  ->filter(function($n) { return $n % 2 === 0; })
  ->map(function($n) { return $n * 5; })
  ->take(100)
  ->join()
  ->value(), PHP_EOL;

var_dump(_::chain(array('A', 'B'))->object(array('foo', 'bar'))->toArray(true)->value());

echo _::sortedIndex([10, 20, 30, 40, 50], 35), PHP_EOL;

echo _::indexOf([1, 2, 3], 2), PHP_EOL;

echo _::lastIndexOf([1, 2, 3, 1, 2, 3], 2), PHP_EOL;

var_dump(_::chain(['A' => 1, 2, 3], 2)->values()->toArray()->value());

// __END__
// vim: expandtab softtabstop=2 shiftwidth=2
