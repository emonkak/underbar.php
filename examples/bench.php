<?php

require(__DIR__ . '/../vendor/autoload.php');

use Underbar\Lazy as _;

$xs = range(0, 100000);
$f = function($_) use ($xs) {
    return $_::chain($xs)
        ->map(function($x) { return $x * 2; })
        ->filter(function($x) { return $x % 10 !== 0; })
        ->zip($_::range(100))
        ->take(10000)
        ->each("$_::identity");
};

foreach (array(
    'Underbar\\LazyIterator',
    'Underbar\\LazyGenerator',
    'Underbar\\LazyUnsafeGenerator',
    'Underbar\\Strict',
) as $_) {
    echo $_, ': ', _::bench($f, $_), PHP_EOL;
}

// __END__
// vim: expandtab softtabstop=4 shiftwidth=4
