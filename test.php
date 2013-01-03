<?php

require(__DIR__ . DIRECTORY_SEPARATOR . 'underscore.php');

use Underscore\_;

echo _::chain([[1, 2], [3, 4, 5, 6], [[5, 6]]])->flatten(true)->join()->value(), PHP_EOL;

_::initialize(array('useGenerator' => true));

echo _::chain([[1, 2], [3, 4, 5, 6], [[5, 6]]])->flatten(true)->join()->value(), PHP_EOL;

$xs = _::chain(_::range(0))
  ->filter(function($x) { return $x % 2 === 0; })
  ->map(function($x) { return $x * 100; })
  ->take(10)
  ->union(_::range(0, 10))
  ->join()
  ->value();
echo $xs, PHP_EOL;

// __END__
// vim: expandtab softtabstop=2 shiftwidth=2
