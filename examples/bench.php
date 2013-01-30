<?php

require(__DIR__ . '/../vendor/autoload.php');

use Underbar\Lazy as _;

// warm
class_exists('Underbar\\Lazy');
class_exists('Underbar\\Lazy_Iterator');
class_exists('Underbar\\Lazy_Generator');
class_exists('Underbar\\Strict');

function bench($subject, $callback)
{
    $arguments = array_slice(func_get_args(), 2);

    $beginTime = microtime(true);
    $beginMemory = memory_get_usage(true);

    call_user_func_array($callback, $arguments);

    $endTime = microtime(true);
    $endMemory = memory_get_usage(true);

    printf("%s: %.2f sec [mem: %s KB] [peak: %s KB]\n",
           $subject,
           $endTime - $beginTime,
           number_format(($endMemory - $beginMemory) / 1024),
           number_format(memory_get_peak_usage(true) / 1024));
}

$xs = range(0, 100000);
$f = function($_, $xs) {
    return $_::chain($xs)
        ->map(function($x) { return $x * 2; })
        ->filter(function($x) { return $x % 10 !== 0; })
        ->zip($_::range(100))
        ->take(10000)
        ->each("$_::identity");
};
bench('lazy iterator', $f, 'Underbar\\Lazy_Iterator', $xs);
bench('lazy generator', $f, 'Underbar\\Lazy_Generator', $xs);
bench('strict fast', function($xs) {
    $f = function($x) { return $x * 2; };
    $g = function($x) { return $x % 10 !== 0; };
    $ys = range(0, 99);
    reset($ys);
    foreach ($xs as $i => $x) {
        if ($i > 10000) break;
        $x = $f($x);
        if ($g($x)) _::identity(array($x, current($ys)));
        next($ys);
    }
}, $xs);
bench('strict', $f, 'Underbar\\Strict', $xs);

// __END__
// vim: expandtab softtabstop=4 shiftwidth=4
