<?php

require(__DIR__ . '/vendor/autoload.php');

use Understrike\Lazy as _;

class_exists('Understrike\\Lazy\\IteratorFunctions');
class_exists('Understrike\\Lazy\\GeneratorFunctions');
class_exists('Understrike\\Strict');

class __
{
  const STATE_YIELD    = 0;
  const STATE_CONTINUE = 1;
  const STATE_BREAK    = 2;

  protected $xs;

  protected $fs = array();

  public function __construct($xs)
  {
    $this->xs = $xs;
  }

  public function value()
  {
    foreach ($this->xs as $k => $x) {
      foreach ($this->fs as $f) {
        $result = call_user_func($f, $x, $k, $this->xs);
        switch ($result[0]) {
        case self::STATE_YIELD:
          $x = $result[1];
          $k = $result[2];
          break;
        case self::STATE_CONTINUE:
          continue 3;
        case self::STATE_BREAK:
          break 3;
        }
      }
      yield $k => $x;
    }
  }

  public function each($f)
  {
    $xs = $this->value();
    foreach ($xs as $k => $x) call_user_func($f, $x, $k, $xs);
    return $this;
  }

  public function map($f)
  {
    $this->fs[] = function($x, $k, $xs) use ($f) {
      return array(self::STATE_YIELD, call_user_func($f, $k, $xs), $k);
    };
    return $this;
  }

  public function filter($f)
  {
    $this->fs[] = function($x, $k, $xs) use ($f) {
      if (call_user_func($f, $k, $xs))
        return array(self::STATE_YIELD, $x, $k);
      else
        return array(self::STATE_CONTINUE);
    };
    return $this;
  }

  public function first($n)
  {
    $this->fs[] = function($x, $k, $xs) use (&$n) {
      if ($n-- > 0)
        return array(self::STATE_YIELD, $x, $k);
      else
        return array(self::STATE_BREAK);
    };
    return $this;
  }
}

function bench($subject, $callback)
{
  $arguments = array_slice(func_get_args(), 2);
  $beginTime = microtime(true);
  $beginMemory = memory_get_usage(true);

  $result = call_user_func_array($callback, $arguments);

  $endTime = microtime(true);
  $endMemory = memory_get_usage(true);

  printf("%s: %.2f sec (%s KB) [%s KB]\n",
         $subject,
         $endTime - $beginTime,
         number_format(($endMemory - $beginMemory) / 1024),
         number_format(memory_get_peak_usage(true) / 1024));
}

$xs = _::range(0, 100000);
$f = function($_, $xs) {
  return $_::chain($xs)
    ->map(function($x) { return $x * 2; })
    ->filter(function($x) { return $x % 10 !== 0; })
    ->map(function($x) { return $x * 2; })
    ->each("$_::identity")
    ->value();
};

bench('fast?', function($xs) {
  return (new __($xs))
    ->map(function($x) { return $x * 2; })
    ->filter(function($x) { return $x % 10 !== 0; })
    ->map(function($x) { return $x * 2; })
    ->each(function($x) { return $x; });
}, clone $xs);
bench('fast?', function($xs) {
  $f = function($x) { return $x * 2; };
  $g = function($x) { return $x % 10 !== 0; };
  foreach ($xs as $x) {
    $x = $f($x);
    if ($g($x)) {
      _::identity($f($x));
    }
  }
}, clone $xs);
bench('lazy iterator', $f, 'Understrike\\Lazy\\IteratorFunctions', clone $xs);
bench('lazy generator', $f, 'Understrike\\Lazy\\GeneratorFunctions', clone $xs);
bench('strict', $f, 'Understrike\\Strict', clone $xs);

$list = array(1, array(2), array(3, array(array(array(4)))));
var_dump(_::chain($list)->flatten(true)->toArray()->value());

var_dump(_::chain(array('A', 'B'))->object(array('foo', 'bar'))->toArray(true)->value());

echo _::chain(array(array(10, 11, array(12, 13)), array(array(2, 3, 4, 5))))
->flatten()
->join()
  ->value(), PHP_EOL;

echo _::chain(_::range(1, 100))
  ->filter(function($x) { return $x % 2 === 0; })
  ->map(function($x) { return $x * 100; })
->take(10)
->union(_::range(1, 10))
->initial()
->join()
  ->value(), PHP_EOL;

echo _::chain(_::zip(_::range(1, 10), _::range(101, 110), _::range(1001, 1010)))
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

echo _::sortedIndex(array(10, 20, 30, 40, 50), 35), PHP_EOL;

echo _::indexOf(array(1, 2, 3), 2), PHP_EOL;

echo _::lastIndexOf(array(1, 2, 3, 1, 2, 3), 2), PHP_EOL;

var_dump(_::chain(array('A' => 1, 2, 3), 2)->values()->toArray(true)->value());

// __END__
// vim: expandtab softtabstop=2 shiftwidth=2
