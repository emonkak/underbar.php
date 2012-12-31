<?php

require(__DIR__ . DIRECTORY_SEPARATOR . 'underscore.php');

use Underscore\_;

$xs = _::chain(_::range(0))
  ->filter(function($x) { return $x % 2 === 0; })
  ->map(function($x) { return $x * 2; })
  ->take(10)
  ->value();

foreach ($xs as $x) {
  var_dump($x);
}

// __END__
// vim: expandtab softtabstop=2 shiftwidth=2
