# underbar.php

underbar.php is a underscore.js like library.

However not aim full compatibility of undersocre.js.

# Requirements

- PHP 5.3 or higher (Suggest PHP 5.4 or higher)
- [Composer](http://getcomposer.org/)

# Licence

MIT Licence

# Features

- undersocre.js like API
- Available some functions like a functional language (e.g `takeWhile()`, `dropWhile()`, `cycle()`, `repeat()`, `iterate()`)
- Available `Option` (also known as `Maybe`) class and null safe functions (e.g. `headSafe()`, `findSafe()`)
- Implement strict and lazy version functions on the same interface
- Use `Generator` when running on PHP 5.5
- Provide `Enumerable` trait
- PSR-2 compliance

# Getting Started

1. Install [Composer](http://getcomposer.org/).
2. Create the `composer.json`
3. Execute `composer.phar install`

**composer.json**

```json
{
    "require": {
        "emonkak/underbar.php": "dev-master"
    }
}
```

# Example

```php
require __DIR__ . '/vendor/autoload.php';

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
class Collection implements \IteratorAggregate
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

# API

## Underbar\Strict and Underbar\Lazy

### Collections

#### each(*array* $xs, *callable* $f)
#### map(*array* $xs, *callable* $f)
#### collect(*array* $xs, *callable* $f)
#### mapKey(*array* $xs, *callable* $f)
#### collectKey(*array* $xs, *callable* $f)
#### parallelMap(*array* $xs, *callable* $f [, int $n = 1 [, int $timeout = null]])
#### parallelCollect(*array* $xs, *callable* $f [, int $n = 1 [, int $timeout = null]])
#### collectKey(*array* $xs, *callable* $f)
#### reduce(*array* $xs, *callable* $f, $acc)
#### inject(*array* $xs, *callable* $f, $acc)
#### foldl(*array* $xs, *callable* $f, $acc)
#### reduceRight(*array* $xs, *callable* $f, $acc)
#### foldr(*array* $xs, *callable* $f, $acc)
#### find(*array* $xs, *callable* $f)
#### detect(*array* $xs, *callable* $f)
#### findSafe(*array* $xs, *callable* $f)
#### detectSafe(*array* $xs, *callable* $f)
#### filter(*array* $xs, *callable* $f)
#### select(*array* $xs, *callable* $f)
#### where(*array* $xs, *array* $properties)
#### findWhere(*array* $xs, *array* $properties)
#### findWhereSafe(*array* $xs, *array* $properties)
#### reject(*array* $xs, *callable* $f)
#### every(*array* $xs, *callable* $f = null)
#### all(*array* $xs, *callable* $f = null)
#### some(*array* $xs, *callable* $f = null)
#### any(*array* $xs, *callable* $f = null)
#### sum(*array* $xs)
#### product(*array* $xs)
#### contains(*array* $xs, *mixed* $target)
#### invoke(*array* $xs, *string* $method)
#### pluck(*array* $xs, *string* $property)
#### max(*array* $xs, *callable* $f = null)
#### min(*array* $xs, *callable* $f = null)
#### sortBy(*array* $xs, *mixed* $value)
#### groupBy(*array* $xs, *mixed* $value = null)
#### countBy(*array* $xs, *mixed* $value = null)
#### shuffle(*array* $xs)
#### toArray(*array* $xs, *bool* $preserveKeys = null)
#### size(*array* $xs)
#### count(*array* $xs)

### Arrays

#### first(*array* $xs [, *int* $n = null])
#### head(*array* $xs [, *int* $n = null])
#### take(*array* $xs [, int $n = null])
#### firstSafe(*array* $xs [, *int* $n = null])
#### headSafe(*array* $xs [, *int* $n = null])
#### takeSafe(*array* $xs [, *int* $n = null])
#### takeWhile(*array* $xs, callable $f)
#### initial(*array* $xs [, *int* $n = 1])
#### last(*array* $xs [, *int* $n = null])
#### lastSafe(*array* $xs [, *int* $n = null])
#### rest(*array* $xs [, *int* $n = 0])
#### tail(*array* $xs [, *int* $n = 0])
#### drop(*array* $xs [, *int* $n = 0])
#### dropWhile(*array* $xs, callable $f)
#### compact(*array* $xs)
#### flatten(*array* $xs [, *bool* $shallow = false])
#### without(*array* $xs [, *mixed* $values, ...])
#### union([*array* $xss, ...])
#### *int*ersection(*array* $xs [, *array* $rest, ...])
#### difference(*array* $xs [, *array* $others, ...])
#### uniq(*array* $xs [, callable $f = null])
#### unique($xs [, callable $f = null])
#### zip([*array* $xss, ...])
#### zipWith(*array* $xss, ..., callable $f)
#### object(*array* $xs [, *array* $values = null])
#### indexOf(*array* $xs [, *mixed* $value [, *bool|int* $isSorted = 0]])
#### lastIndexOf(*array* $xs, *mixed* $value [, *int* $fromIndex = null])
#### sortedIndex(*array* $xs, *mixed* $value [, callable $f = null])
#### range(*int* $start [, *int* $stop = null [, *int* $step = 1]])
#### cycle(*array* $xs [, *int* $n = null])
#### repeat(*mixed* $value [, *int* $n = -1])
#### iterate(*mixed* $acc [, callable $f])
#### pop(*array* $xs)
#### push(*array* $xs [, *mixed* $values, ...])
#### reverse(*array* $xs)
#### shift(*array* $xs)
#### sort(*array* $xs)
#### splice(*array* $xs, *int* $index, *int* $n)
#### unshift(*array* $xs [, *mixed* $values, ...])
#### concat(*array* $xss, ...)
#### join(*array* $xs [, *string* $separator = ','])
#### slice(*array* $xs, *int* $begin [, *int* $end = -1])

### Objects

#### keys(*array* $xs)
#### values(*array* $xs)
#### pairs(*array* $xs)
#### invert(*array* $xs)
#### extend(*array* $destination [, *array* $sources])
#### pick(*array* $xs [, *array|mixed* $keys])
#### omit(*array* $xs [, *array|mixed* $keys])
#### defaults(*array* $xs [, *array* $defaults])
#### tap(*array* $xs, *callable* $interceptor)
#### duplicate(*mixed* $value)
#### has(*array* $xs, *mixed* $key)

### Utilities

#### identity(*mixed* $value)
#### times(*int* $n, *callable* $f)
#### chain(*mixed* $value)
