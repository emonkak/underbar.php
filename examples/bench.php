<?php

require(__DIR__ . '/../vendor/autoload.php');

use Underbar\Underbar as _;

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
    'Lazy\\Iterator'        => array($f, 'Underbar\\Lazy\\Iterator', $xs),
    'Lazy\\Generator'       => array($f, 'Underbar\\Lazy\\Generator', $xs),
    'Lazy\\GeneratorUnsafe' => array($f, 'Underbar\\Lazy\\GeneratorUnsafe', $xs),
    'Strict'                => array($f, 'Underbar\\Strict', $xs),
))->bench()->toArray(true)->tap('var_export');

// __END__
// vim: expandtab softtabstop=4 shiftwidth=4
