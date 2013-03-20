<?php

require(__DIR__ . '/../vendor/autoload.php');

use Underbar\Strict as _;

// warm
class_exists('Underbar\\Lazy');
class_exists('Underbar\\LazyIterator');
class_exists('Underbar\\LazyGenerator');
class_exists('Underbar\\LazyUnsafeGenerator');
class_exists('Underbar\\Strict');

$xs = range(0, 100000);
$f = function($_, $xs) {
    return $_::chain($xs)
        ->map(function($x) { return $x * 2; })
        ->filter(function($x) { return $x % 10 !== 0; })
        ->zip($_::range(100))
        ->take(10000)
        ->each("$_::identity");
};

_::chain(array(
    'LazyIterator'        => array($f, 'Underbar\\LazyIterator', $xs),
    'LazyGenerator'       => array($f, 'Underbar\\LazyGenerator', $xs),
    'LazyUnsafeGenerator' => array($f, 'Underbar\\LazyUnsafeGenerator', $xs),
    'Strict'              => array($f, 'Underbar\\Strict', $xs),
))->bench()->tap('var_export');

// __END__
// vim: expandtab softtabstop=4 shiftwidth=4
