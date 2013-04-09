# underbar.php [![Build Status](https://travis-ci.org/emonkak/underbar.php.png)](https://travis-ci.org/emonkak/underbar.php)

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
- Implement strict and lazy version functions on the same interface
- Use `Generator` when running on PHP 5.5
- Provide `Enumerable` trait

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

| Function    | Return          | Arguments                                                     |
| ----------- | --------------- | ------------------------------------------------------------- |
| each        | void            | array $xs, callable $f                                        |
| map         | array           | array $xs, callable $f                                        |
| collect     | array           | array $xs, callable $f                                        |
| parMap      | Parallel        | array $xs, callable $f [, int $n = 1 [, int $timeout = null]] |
| reduce      | mixed           | array $xs, callable $f, $acc                                  |
| inject      | mixed           | array $xs, callable $f, $acc                                  |
| foldl       | mixed           | array $xs, callable $f, $acc                                  |
| reduceRight | mixed           | array $xs, callable $f, $acc                                  |
| foldr       | mixed           | array $xs, callable $f, $acc                                  |
| find        | mixed           | array $xs, callable $f                                        |
| detect      | mixed           | array $xs, callable $f                                        |
| filter      | array           | array $xs, callable $f                                        |
| select      | array           | array $xs, callable $f                                        |
| where       | array           | array $xs, array $properties                                  |
| findWhere   | mixed           | array $xs, array $properties                                  |
| reject      | array           | array $xs, callable $f                                        |
| every       | bool            | array $xs, callable $f = null                                 |
| all         | bool            | array $xs, callable $f = null                                 |
| some        | bool            | array $xs, callable $f = null                                 |
| any         | bool            | array $xs, callable $f = null                                 |
| sum         | int             | array $xs                                                     |
| product     | int             | array $xs                                                     |
| contains    | bool            | array $xs, mixed $target                                      |
| invoke      | array           | array $xs, string $method                                     |
| pluck       | array           | array $xs, string $property                                   |
| max         | mixed           | array $xs, callable $f = null                                 |
| min         | mixed           | array $xs, callable $f = null                                 |
| sortBy      | array           | array $xs, mixed $value                                       |
| groupBy     | array           | array $xs, mixed $value = null                                |
| countBy     | array           | array $xs, mixed $value = null                                |
| shuffle     | array           | array $xs                                                     |
| toArray     | array           | array $xs, bool $preserveKeys = null                          |
| size        | int             | array $xs                                                     |
| get         | mixed           | array $xs, int $index , mixed $default = null                 |
| span        | array           | array $xs, callable $f                                        |
| memorize    | CachingIterator | array $xs, callable $f                                        |

### Arrays

| Function     | Return            | Arguments                                                  |
| ------------ | -------------     | ---------------------------------------------------------- |
| first        | array&#x7c;mixed  | array $xs [, int $n = null]                                |
| head         | array&#x7c;mixed  | array $xs [, int $n = null]                                |
| take         | array&#x7c;mixed  | array $xs [, int $n = null]                                |
| takeWhile    | array             | array $xs, callable $f                                     |
| initial      | array             | array $xs [, int $n = 1]                                   |
| last         | array&#x7c;mixed  | array $xs [, int $n = null]                                |
| rest         | array             | array $xs [, int $n = 0]                                   |
| tail         | array             | array $xs [, int $n = 0]                                   |
| drop         | array             | array $xs [, int $n = 0]                                   |
| dropWhile    | array             | array $xs, callable $f                                     |
| compact      | array             | array $xs                                                  |
| flatten      | array             | array $xs [, bool $shallow = false]                        |
| without      | array             | array $xs [, mixed $values, ...]                           |
| union        | array             | array $xs, [array $yss, ...]                                          |
| intersection | array             | array $xs [, array $rest, ...]                             |
| difference   | array             | array $xs [, array $others, ...]                           |
| uniq         | array             | array $xs [, callable $f = null]                           |
| unique       | array             | $xs [, callable $f = null]                                 |
| zip          | array             | array $xs, [array $yss, ...]                                          |
| zipWith      | array             | array $xss, ..., callable $f                               |
| object       | array             | array $xs [, array $values = null]                         |
| indexOf      | int               | array $xs [, mixed $value [, bool&#x7c;int $isSorted = 0]] |
| lastIndexOf  | int               | array $xs, mixed $value [, int $fromIndex = null]          |
| sortedIndex  | int               | array $xs, mixed $value [, callable $f = null]             |
| range        | array             | int $start [, int $stop = null [, int $step = 1]]          |
| cycle        | array             | array $xs [, int $n = null]                                |
| repeat       | array             | mixed $value [, int $n = -1]                               |
| iterate      | mixed             | mixed $acc [, callable $f]                                 |
| pop          | array             | array $xs                                                  |
| push         | array             | array $xs [, mixed $values, ...]                           |
| reverse      | array             | array $xs                                                  |
| shift        | array             | array $xs                                                  |
| sort         | array             | array $xs                                                  |
| splice       | array             | array $xs, int $index, int $n                              |
| unshift      | array             | array $xs [, mixed $values, ...]                           |
| concat       | array             | array $xss, ...                                            |
| join         | string            | array $xs [, string $separator = ',']                      |
| slice        | array             | array $xs, int $begin [, int $end = -1]                    |

### Objects

| Function      | Return | Arguments                             |
| ------------- | ------ | ------------------------------------- |
| keys          | array  | array $xs                             |
| values        | array  | array $xs                             |
| pairs         | array  | array $xs                             |
| invert        | array  | array $xs                             |
| extend        | array  | array $destination [, array $sources] |
| pick          | array  | array $xs [, array&#x7c;mixed $keys]  |
| omit          | array  | array $xs [, array&#x7c;mixed $keys]  |
| defaults      | array  | array $xs [, array $defaults]         |
| tap           | mixed  | mixed $value, callable $interceptor   |
| has           | bool   | array $xs, mixed $key                 |
| isArray       | bool   | mixed $value                          |
| isTraversable | bool   | mixed $value                          |

### Utilities

| Function  | Return           | Arguments     |
| --------- | ---------------- | ------------- |
| identity  | mixed            | mixed $value  |
| chain     | Internal\Wrapper | mixed $value  |
| bench     | float            | callable $f   |
