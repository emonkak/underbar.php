# underbar.php

underbar.php is a underscore.js like collection library.

However not aim full compatibility of it.

# Requirements

- PHP 5.3 or higher
- [Composer](http://getcomposer.org/)

# Features

- undersocre.js compatible (not fully)
- Add some functions like a functional language (e.g `takeWhile()`, `dropWhile()`, `cycle()`, `repeat()`, `iterate()`)
- Available `Option` (also known as `Maybe`) class and null safe functions (e.g. `headSafe()`, `findSafe()`)
- Implement strict and lazy version functions on the same interface
- Support `Generator` when running on PHP 5.5
- Provide trait which add useful collection operated methods
- PSR-2 compliance (not complete yet)

# Getting Started

# Example

```php
require __DIR__ . 'vendor/autoload.php';

use Underbar\Lazy as _;

// Take five elements from a even infinite list
_::chain(0)
    ->iterate(function($n) { return $n + 1; })
    ->filter(function($n) { return $n % 2 === 0; })
    ->take(5)
    ->each(functions($n) { echo $n, PHP_EOL; });
// => 0
//    2
//    4
//    6
//    8

// Get a first element
echo _::first(array(100)), PHP_EOL;
// => 100

// Get a first element when empty array
echo _::firstSafe(array())->getOrElse('empty'), PHP_EOL;
// => 'empty'

// Declare enumerable collection class
class Collection implements \Iterator
{
    use \Underbar\Enumerable;

    protected $array;

    public function __construct()
    {
        $this->array = func_get_args();
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->array);
    }
}

$collection = new Collection(1, 2, 3);

$twice = $collection->map(function($n) { return $n * 2; });
echo _::join($twice, ', '), PHP_EOL;
// => '2, 4, 6'

$twiceCycle = $collection
    ->lazy()
    ->cycle()
    ->map(function($n) { return $n * 2; })
    ->take(6);
echo _::join($twiceCycle, ', '), PHP_EOL;
// => '2, 4, 6, 2, 4, 6'
```

# Available functions

## Collections

- `each($list, $iterator)`
- `map($list, $iterator)`
- `collect($list, $iterator)`
- `reduce($list, $iterator, $memo)`
- `inject($list, $iterator, $memo)`
- `foldl($list, $iterator, $memo)`
- `reduceRight($list, $iterator, $memo)`
- `foldr($list, $iterator, $memo)`
- `find($list, $iterator)`
- `detect($list, $iterator)`
- `findSafe($list, $iterator)`
- `detectSafe($list, $iterator)`
- `filter($list, $iterator)`
- `select($list, $iterator)`
- `where($list, $properties)`
- `findWhere($list, $properties)`
- `findWhereSafe($list, $properties)`
- `reject($list, $iterator)`
- `every($list, $iterator = null)`
- `all($list, $iterator = null)`
- `some($list, $iterator = null)`
- `any($list, $iterator = null)`
- `sum($list)`
- `product($list)`
- `contains($list, $target)`
- `invoke($list, $method)`
- `pluck($list, $property)`
- `max($list, $iterator = null)`
- `min($list, $iterator = null)`
- `sortBy($list, $value)`
- `groupBy($list, $value = null)`
- `countBy($list, $value = null)`
- `shuffle($list)`
- `toArray($list, $preserveKeys = null)`
- `size($list)`
- `count($list)`

## Arrays

- `first($array, $n = null, $guard = null)`
- `head($array, $n = null, $guard = null)`
- `take($array, $n = null, $guard = null)`
- `firstSafe($array, $n = null, $guard = null)`
- `headSafe($array, $n = null, $guard)`
- `takeSafe($array, $n = null, $guard)`
- `takeWhile($array, $iterator)`
- `initial($array, $n = 1, $guard = null)`
- `last($array, $n = null, $guard = null)`
- `lastSafe($array, $n = null, $guard)`
- `rest($array, $index = 1, $guard = null)`
- `tail($array, $index = 1, $guard = null)`
- `drop($array, $index = 1, $guard = null)`
- `dropWhile($array, $iterator)`
- `compact($array)`
- `flatten($array, $shallow = false)`
- `without($array)`
- `union()`
- `intersection()`
- `difference($array)`
- `uniq($array, $iterator = null)`
- `unique($array, $iterator = null)`
- `zip()`
- `zipWith()`

## Objects

- `object($list, $values = null)`
- `indexOf($array, $value, $isSorted = 0)`
- `lastIndexOf($array, $value, $fromIndex = null)`
- `sortedIndex($list, $value, $iterator = null)`
- `range($start, $stop = null, $step = 1)`
- `cycle($array, $n = null)`
- `repeat($value, $n = -1)`
- `iterate($memo, $iterator)`
- `pop($array)`
- `push($array)`
- `reverse($array)`
- `shift($array)`
- `sort($array)`
- `splice($array, $index, $n)`
- `unshift($array)`
- `concat()`
- `join($array, $separator = ',')`
- `slice($array, $begin, $end = -1)`
- `keys($object)`
- `values($object)`
- `pairs($object)`
- `invert($object)`
- `extend($destination)`
- `pick($object)`
- `omit($object)`
- `defaults($object)`
- `tap($object, $interceptor)`
- `duplicate($object)`
- `has($object, $key)`

## Functions

- `partial($func)`
- `memoize($func, $hasher = null)`
- `once($func)`
- `after($times, $func)`
- `wrap($func, $wrapper)`
- `compose()`
- `identity($value)`

## Utilities

- `times($n, $iterator)`
- `chain($value)`
