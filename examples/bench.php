<?php

require(__DIR__ . '/../vendor/autoload.php');

use Underbar\Lazy as _;

function bench($f)
{
    $begin = microtime(true);
    $result = call_user_func_array($f, array_slice(func_get_args(), 1));
    $end = microtime(true);
    return array($result, $end - $begin);
}

function times($f, $n)
{
    return function() use ($f, $n) {
        while ($n-- > 0) call_user_func_array($f, func_get_args());
    };
}

$xs = range(0, 100000);
$f = function($_) use ($xs) {
    return $_::chain($xs)
        ->map(function($x) { return $x * 2; })
        ->filter(function($x) { return $x % 10 !== 0; })
        ->zip($_::range(100))
        ->take(10000)
        ->each("$_::identity");
};

$classes = array(
    'Underbar\\IteratorImpl',
    'Underbar\\GeneratorImpl',
    'Underbar\\ArrayImpl',
);
foreach ($classes as $class) {
    list (, $time) = bench(times($f, 100), $class);
    echo $class, "\t", $time, PHP_EOL;
}
