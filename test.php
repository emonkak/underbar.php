<?php

require(__DIR__ . DIRECTORY_SEPARATOR . 'underscore.php');

use Underscore\_;
use Underscore\Underscore as U;

echo _::chain(array(array(0, 1), array(array(2, 3, 4, 5))))->flatten()->join()->value(), PHP_EOL;

$xs = _::chain(_::range(1))
  ->filter(function($x) { return $x % 2 === 0; })
  ->map(function($x) { return $x * 100; })
  ->take(10)
  ->union(_::range(1, 10))
  ->uniq()
  ->initial()
  ->join()
  ->value();
echo $xs, PHP_EOL;

$xs = _::chain(_::zip(_::range(1), _::range(101), _::range(1001)))
  ->take(10)
  ->map(function($xs) { return '['._::join($xs, ' ').']'; })
  ->join()
  ->value();
echo $xs, PHP_EOL;

var_dump(_::chain(array('A', 'B'))->object(array('foo', 'bar'))->toArray()->value());

// __END__
// vim: expandtab softtabstop=2 shiftwidth=2
