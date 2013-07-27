<?php
/**
 * This file is part of the Underbar.php package.
 *
 * Copyright (C) 2013 Shota Nozaki <emonkak@gmail.com>
 *
 * Licensed under the MIT License
 */

namespace Underbar;

abstract class AbstractImpl implements Internal\ImplementorInterface
{
    /**
     * @category  Collections
     * @param     array     $xs
     * @param     callable  $f
     * @return    void
     */
    public static function each($xs, $f)
    {
        foreach ($xs as $k => $x) {
            call_user_func($f, $x, $k, $xs);
        }
    }

    /**
     * Alias: collect
     *
     * @category  Collections
     * @param     array     $xs
     * @param     callable  $f
     * @return    array
     */
    // abstract public static function map($xs, $f);

    public static function collect($xs, $f)
    {
        return static::map($xs, $f);
    }

    /**
     * Alias: inject, foldl
     *
     * @category  Collections
     * @param     array     $xs
     * @param     callable  $f
     * @param     mixed     $acc
     * @return    mixed
     */
    public static function reduce($xs, $f, $acc)
    {
        foreach ($xs as $k => $x) {
            $acc = call_user_func($f, $acc, $x, $k, $xs);
        }
        return $acc;
    }

    public static function inject($xs, $f, $acc)
    {
        return static::reduce($xs, $f, $acc);
    }

    public static function foldl($xs, $f, $acc)
    {
        return static::reduce($xs, $f, $acc);
    }

    /**
     * Alias: foldr
     *
     * @category  Collections
     * @param     array     $xs
     * @param     callable  $f
     * @param     mixed     $acc
     * @return    mixed
     */
    public static function reduceRight($xs, $f, $acc)
    {
        return static::reduce(static::reverse($xs), $f, $acc);
    }

    public static function foldr($xs, $f, $acc)
    {
        return static::reduceRight($xs, $f, $acc);
    }

    /**
     * Alias: detect
     *
     * @category  Collections
     * @param     array     $xs
     * @param     callable  $f
     * @return    mixed
     */
    public static function find($xs, $f)
    {
        foreach ($xs as $k => $x) {
            if (call_user_func($f, $x, $k, $xs)) {
                return $x;
            }
        }
    }

    public static function detect($xs, $f)
    {
        return static::find($xs, $f);
    }

    /**
     * Alias: select
     *
     * @category  Collections
     * @param     array     $xs
     * @param     callable  $f
     * @return    array
     */
    public static function filter($xs, $f)
    {
        $ys = array();
        foreach ($xs as $k => $x) {
            if (call_user_func($f, $x, $k, $xs)) {
                $ys[$k] = $x;
            }
        }
        return $ys;
    }

    public static function select($xs, $f)
    {
        return static::filter($xs, $f);
    }

    /**
     * @category  Collections
     * @param     array  $xs
     * @param     array  $properties
     * @return    array
     */
    public static function where($xs, $properties)
    {
        return static::filter($xs, function($x) use ($properties) {
            foreach ($properties as $key => $value) {
                if (!((isset($x->$key) && $x->$key === $value)
                      || (isset($x[$key]) && $x[$key] === $value))) {
                    return false;
                }
            }
            return true;
        });
    }

    /**
     * @category  Collections
     * @param     array  $xs
     * @param     array  $properties
     * @return    mixed
     */
    public static function findWhere($xs, $properties)
    {
        return static::find($xs, function($x) use ($properties) {
            foreach ($properties as $key => $value) {
                if (!((isset($x->$key) && $x->$key === $value)
                      || (isset($x[$key]) && $x[$key] === $value))) {
                    return false;
                }
            }
            return true;
        });
    }

    /**
     * @category  Collections
     * @param     array     $xs
     * @param     callable  $f
     * @return    array
     */
    public static function reject($xs, $f)
    {
        return static::filter($xs, function($x, $k, $xs) use ($f) {
            return !call_user_func($f, $x, $k, $xs);
        });
    }

    /**
     * Alias: all
     *
     * @category  Collections
     * @param     array            $xs
     * @param     callable|string  $f
     * @return    boolean
     */
    public static function every($xs, $f = null)
    {
        $f = static::createCallback($f);

        foreach ($xs as $k => $x) {
            if (!call_user_func($f, $x, $k, $xs)) {
                return false;
            }
        }

        return true;
    }

    public static function all($xs, $f = null)
    {
        return static::every($xs, $f);
    }

    /**
     * Alias: any
     *
     * @category  Collections
     * @param     array            $xs
     * @param     callable|string  $f
     * @return    boolean
     */
    public static function some($xs, $f = null)
    {
        $f = static::createCallback($f);

        foreach ($xs as $k => $x) {
            if (call_user_func($f, $x, $k, $xs)) {
                return true;
            }
        }

        return false;
    }

    public static function any($xs, $f = null)
    {
        return static::some($xs, $f);
    }

    /**
     * @category  Collections
     * @param     array  $xs
     * @param     mixed  $target
     * @return    boolean
     */
    public static function contains($xs, $target)
    {
        foreach ($xs as $x) {
            if ($x === $target) {
                return true;
            }
        }
        return false;
    }

    /**
     * @varargs
     * @category  Collections
     * @param     array   $xs
     * @param     string  $method
     * @param     miexed  *$arguments
     * @return    array
     */
    public static function invoke($xs, $method)
    {
        $args = array_slice(func_get_args(), 2);
        return static::map($xs, function($x) use ($method, $args) {
            return call_user_func_array(array($x, $method), $args);
        });
    }

    /**
     * @category  Collections
     * @param     array   $xs
     * @param     string  $property
     * @return    array
     */
    public static function pluck($xs, $property)
    {
        return static::map($xs, function($x) use ($property) {
            if (isset($x->$property)) {
                return $x->$property;
            } elseif (isset($x[$property])) {
                return $x[$property];
            } else {
                return null;
            }
        });
    }

    /**
     * @category  Collections
     * @param     array            $xs
     * @param     callable|string  $f
     * @return    mixed|int
     */
    public static function max($xs, $f = null)
    {
        if ($f === null) {
            $xs = static::extractIterator($xs);
            return empty($xs) ? -INF : max($xs);
        }

        $f = static::createCallback($f);
        $computed = -INF;
        $result = -INF;
        foreach ($xs as $k => $x) {
            $current = call_user_func($f, $x, $k, $xs);
            if ($current > $computed) {
                $computed = $current;
                $result = $x;
            }
        }

        return $result;
    }

    /**
     * @category  Collections
     * @param     array            $xs
     * @param     callable|string  $f
     * @return    mixed|int
     */
    public static function min($xs, $f = null)
    {
        if ($f === null) {
            $xs = static::extractIterator($xs);
            return empty($xs) ? INF : min($xs);
        }

        $f = static::createCallback($f);
        $computed = INF;
        $result = INF;
        foreach ($xs as $k => $x) {
            $current = call_user_func($f, $x, $k, $xs);
            if ($current < $computed) {
                $computed = $current;
                $result = $x;
            }
        }

        return $result;
    }

    /**
     * Porting from the Prelude of Haskell.
     *
     * @category  Collections
     * @param     array  $xs
     * @return    int
     */
    public static function sum($xs)
    {
        $acc = 0;
        foreach ($xs as $x) {
            $acc += $x;
        }
        return $acc;
    }

    /**
     * Porting from the Prelude of Haskell.
     *
     * @category  Collections
     * @param     array  $xs
     * @return    int
     */
    public static function product($xs)
    {
        $acc = 1;
        foreach ($xs as $x) {
            $acc *= $x;
        }
        return $acc;
    }

    /**
     * @category  Collections
     * @param     array            $xs
     * @param     callable|string  $x
     * @return    array
     */
    public static function sortBy($xs, $x)
    {
        $f = static::createCallback($x);
        $result = array();

        foreach ($xs as $k => $x) {
            $result[] = array(
                'value' => $x,
                'key' => $k,
                'criteria' => call_user_func($f, $x, $k, $xs),
            );
        }

        usort($result, function($left, $right) {
            $a = $left['criteria'];
            $b = $right['criteria'];
            if ($a !== $b) {
                return $a < $b ? -1 : 1;
            } else {
                return $left['key'] < $right['key'] ? -1 : 1;
            }
        });

        return static::pluck($result, 'value');
    }

    /**
     * @category  Collections
     * @param     array            $xs
     * @param     callable|string  $f
     * @return    array
     */
    public static function groupBy($xs, $f = null)
    {
        $f = static::createCallback($f);
        $result = array();

        foreach ($xs as $k => $x) {
            $key = call_user_func($f, $x, $k, $xs);
            $result[$key][] = $x;
        }

        return $result;
    }

    /**
     * @category  Collections
     * @param     array            $xs
     * @param     callable|string  $x
     * @return    int
     */
    public static function countBy($xs, $f = null)
    {
        $f = static::createCallback($f);
        $result = array();

        foreach ($xs as $k => $x) {
            $key = call_user_func($f, $x, $k, $xs);
            if (isset($result[$key])) {
                $result[$key]++;
            } else {
                $result[$key] = 1;
            }
        }

        return $result;
    }

    /**
     * @category  Collections
     * @param     array  $xs
     * @return    array
     */
    public static function shuffle($xs)
    {
        $xs = static::extractIterator($xs);
        shuffle($xs);
        return $xs;
    }

    /**
     * @category  Collections
     * @param     mixed  $xs
     * @return    array
     */
    public static function toArray($xs)
    {
        if ($xs instanceof \Traversable) {
            return iterator_to_array($xs, true);
        }
        if (is_array($xs)) {
            return $xs;
        }
        if (is_string($xs)) {
            return str_split($xs);
        }
        return (array) $xs;
    }

    /**
     * @category  Collections
     * @param     mixed  $xs
     * @return    array
     */
    public static function toList($xs)
    {
        if ($xs instanceof \Traversable) {
            return iterator_to_array($xs, false);
        }
        if (is_array($xs)) {
            return array_values($xs);
        }
        if (is_string($xs)) {
            return str_split($xs);
        }
        return (array) $xs;
    }

    /**
     * @category  Collections
     * @param     Iterator  $xs
     * @return    Iterator
     */
    public static function memoize($xs)
    {
        return new Iterator\MemoizeIterator(static::wrapIterator($xs));
    }

    /**
     * @category  Collections
     * @param     mixed  $xs
     * @return    int
     */
    public static function size($xs)
    {
        if ($xs instanceof \Countable) {
            return count($xs);
        }
        if ($xs instanceof \Traversable) {
            return iterator_count($xs);
        }
        if (is_string($xs)) {
            return mb_strlen($xs);
        }
        return count($xs);
    }

    /**
     * Alias: head, take
     *
     * @category  Arrays
     * @param     arra  $xs
     * @param     int   $n
     * @return    array|mixed
     */
    public static function first($xs, $n = null, $guard = null)
    {
        if ($n !== null && $guard === null) {
            return static::firstN($xs, $n);
        }

        foreach ($xs as $x) {
            return $x;
        }
    }

    public static function head($xs, $n = null, $guard = null)
    {
        return static::first($xs, $n, $guard);
    }

    public static function take($xs, $n = null, $guard = null)
    {
        return static::first($xs, $n, $guard);
    }

    // abstract public static function firstN($xs, $n = null);

    /**
     * @category  Arrays
     * @param     array  $xs
     * @param     int    $n
     * @return    array
     */
    // abstract public static function initial($xs, $n = 1, $guard = null);

    /**
     * @category  Arrays
     * @param     array  $xs
     * @param     int    $n
     * @return    array|mixed
     */
    public static function last($xs, $n = null, $guard = null)
    {
        if ($n !== null && $guard === null) {
            return static::lastN($xs, $n);
        }

        $x = null;
        foreach ($xs as $x) {
        }
        return $x;
    }

    public static function lastN($xs, $n = null)
    {
        $queue = new \SplQueue();
        if ($n <= 0) {
            return $queue;
        }

        $i = 0;
        foreach ($xs as $x) {
            if ($i === $n) {
                $queue->dequeue();
                $i--;
            }
            $queue->enqueue($x);
            $i++;
        }

        return iterator_to_array($queue, false);
    }

    /**
     * Alias: tail, drop
     *
     * @category  Arrays
     * @param     array  $xs
     * @param     int    $n
     * @return    array
     */
    // abstract public static function rest($xs, $n = 1, $guard = null);

    public static function tail($xs, $n = 1, $guard = null)
    {
        return static::rest($xs, $n, $guard);
    }

    public static function drop($xs, $n = 1, $guard = null)
    {
        return static::rest($xs, $n, $guard);
    }

    /**
     * Porting from the Prelude of Haskell.
     *
     * @category  Arrays
     * @param     array     $xs
     * @param     callable  $f
     * @return    array
     */
    // abstract public static function takeWhile($xs, $f);

    /**
     * Porting from the Prelude of Haskell.
     *
     * @category  Arrays
     * @param     array     $xs
     * @param     callable  $f
     * @return    array
     */
    // abstract public static function dropWhile($xs, $f);

    /**
     * @category  Arrays
     * @param     array  $xs
     * @return    array
     */
    public static function compact($xs)
    {
        return static::filter($xs, __CLASS__.'::identity');
    }

    /**
     * @category  Arrays
     * @param     array  $xs
     * @param     boolean   $shallow
     * @return    array
     */
    // abstract public static function flatten($xs, $shallow = false);

    /**
     * @varargs
     * @category  Arrays
     * @param     array  $xs
     * @param     mixed  *$values
     * @return    array
     */
    public static function without($xs)
    {
        return static::difference($xs, array_slice(func_get_args(), 1));
    }

    /**
     * @varargs
     * @category  Arrays
     * @param     array  *$xss
     * @return    array
     */
    public static function union()
    {
        return static::uniq(call_user_func_array(
            get_called_class().'::concat',
            func_get_args())
        );
    }

    /**
     * @varargs
     * @category  Arrays
     * @param     array  *$xs
     * @return    array
     */
    public static function intersection()
    {
        $xss = array();
        foreach (func_get_args() as $xs) {
            $xss[] = static::extractIterator($xs);
        }
        return call_user_func_array('array_intersect', $xss);
    }

    /**
     * @varargs
     * @category  Arrays
     * @param     array  $xs
     * @param     array  *$others
     * @return    array
     */
    public static function difference($xs)
    {
        $yss = array_slice(func_get_args(), 1);
        return static::filter($xs, function($x) use ($yss) {
            foreach ($yss as $ys) {
                foreach ($ys as $y) {
                    if ($x === $y) {
                        return false;
                    }
                }
            }
            return true;
        });
    }

    /**
     * Alias: unique
     *
     * @category  Arrays
     * @param     array            $xs
     * @param     boolean             $isSorted
     * @param     callable|string  $f
     * @return    array
     */
    public static function uniq($xs, $isSorted = false, $f = null)
    {
        if (!is_bool($isSorted)) {
            $f = static::createCallback($isSorted);
            $isSorted = false;
        } else {
            $f = static::createCallback($f);
        }

        if ($isSorted) {
            $lastValue = null;
            return static::filter($xs, function($x, $i, $xs) use (
                $f,
                &$lastValue
            ) {
                $x = call_user_func($f, $x, $i, $xs);
                if ($lastValue !== $x) {
                    $lastValue = $x;
                    return false;
                }
                return true;
            });
        }

        $seenScalar = $seenObjects = $seenOthers = array();
        return static::filter($xs, function($x, $i, $xs) use (
            $f,
            &$seenScalar,
            &$seenObjects,
            &$seenOthers
        ) {
            $x = call_user_func($f, $x, $i, $xs);

            if (is_scalar($x)) {
                if (isset($seenScalar[$x])) {
                    return false;
                }
                $seenScalar[$x] = 0;
            } elseif (is_object($x)) {
                $hash = spl_object_hash($x);
                if (isset($seenObjects[$hash])) {
                    return false;
                }
                $seenObjects[$hash] = 0;
            } else {
                if (in_array($x, $seenOthers, true)) {
                    return false;
                }
                $seenOthers[] = $x;
            }

            return true;
        });
    }

    public static function unique($xs, $isSorted = false, $f = null)
    {
        return static::uniq($xs, $f);
    }

    /**
     * @varargs
     * @category  Arrays
     * @param     array  *$xss
     * @return    array
     */
    public static function zip()
    {
        return static::unzip(func_get_args());
    }

    /**
     * Porting from the Prelude of Haskell.
     *
     * @varargs
     * @category  Arrays
     * @param     array     *$xss
     * @param     callable  $f
     * @return    array
     */
    public static function zipWith()
    {
        $xss = func_get_args();
        $f = array_pop($xss);
        $zipped = static::unzip($xss);
        return static::map($zipped, function($xs, $i, $xss) use ($f) {
            return call_user_func_array($f, $xs);
        });
    }

    /**
     * @varargs
     * @category  Arrays
     * @param     array  $xss
     * @return    array
     */
    // abstract public static function unzip($xss);

    /**
     * @category  Arrays
     * @param     array  $xs
     * @param     array  $values
     * @return    array
     */
    public static function object($xs, $values = null)
    {
        $result = array();
        $values = static::wrapIterator($values);
        if ($values !== null) {
            $values->rewind();
            foreach ($xs as $key) {
                if (!$values->valid()) {
                    break;
                }

                $result[$key] = $values->current();
                $values->next();
            }
        } else {
            foreach ($xs as $x) {
                $result[$x[0]] = $x[1];
            }
        }
        return $result;
    }

    /**
     * @category  Arrays
     * @param     array     $xs
     * @param     mixed     $value
     * @param     boolean|int  $isSorted
     * @return    int
     */
    public static function indexOf($xs, $value, $isSorted = 0)
    {
        $xs = static::extractIterator($xs);

        if ($isSorted === true) {
            $i = static::sortedIndex($xs, $value);
            return (isset($xs[$i]) && $xs[$i] === $value) ? $i : -1;
        } else {
            $l = count($xs);
            $i = $isSorted < 0 ? max(0, $l + $isSorted) : $isSorted;
            for (; $i < $l; $i++) {
                if (isset($xs[$i]) && $xs[$i] === $value) {
                    return $i;
                }
            }
        }

        return -1;
    }

    /**
     * @category  Arrays
     * @param     array  $xs
     * @param     mixed  $x
     * @param     int    $fromIndex
     * @return    int
     */
    public static function lastIndexOf($xs, $x, $fromIndex = null)
    {
        $xs = static::extractIterator($xs);
        $l = count($xs);
        $i = $fromIndex !== null ? min($l, $fromIndex) : $l;

        while ($i-- > 0) {
            if (isset($xs[$i]) && $xs[$i] === $x) {
                return $i;
            }
        }

        return -1;
    }

    /**
     * @category  Arrays
     * @param     array            $xs
     * @param     mixed            $value
     * @param     callable|string  $f
     * @return    int
     */
    public static function sortedIndex($xs, $value, $f = null)
    {
        $xs = static::extractIterator($xs);
        $f = static::createCallback($f);
        $value = call_user_func($f, $value);

        $low = 0;
        $high = count($xs);

        while ($low < $high) {
            $mid = ($low + $high) >> 1;
            if (call_user_func($f, $xs[$mid]) < $value) {
                $low = $mid + 1;
            } else {
                $high = $mid;
            }
        }

        return $low;
    }

    /**
     * @category  Arrays
     * @param     int  $start
     * @param     int  $stop
     * @param     int  $step
     * @return    array
     */
    // abstract public static function range($start, $stop = null, $step = 1);

    /**
     * Porting from the Prelude of Haskell.
     *
     * @category  Arrays
     * @param     array  $xs
     * @param     int    $n
     * @return    array
     * @throws    OverflowException
     */
    // abstract public static function cycle($xs, $n = null);

    /**
     * Porting from the Prelude of Haskell.
     *
     * @category  Arrays
     * @param     mixed  $value
     * @param     int    $n
     * @return    array
     * @throws    OverflowException
     */
    // abstract public static function repeat($value, $n = -1);

    /**
     * Porting from the Prelude of Haskell.
     *
     * @category  Arrays
     * @param     mixed     $acc
     * @param     callable  $f
     * @return    array
     * @throws    OverflowException
     */
    // abstract public static function iterate($acc, $f);

    /**
     * @category  Arrays
     * @param     array  $xs
     * @return    array
     */
    public static function reverse($xs)
    {
        return array_reverse(static::extractIterator($xs));
    }

    /**
     * @category  Arrays
     * @param     array     $xs
     * @param     callable  $compare
     * @return    array
     */
    public static function sort($xs, $compare = null)
    {
        $xs = static::extractIterator($xs);
        is_callable($compare) ? usort($xs, $compare) : sort($xs);
        return $xs;
    }

    /**
     * @varargs
     * @category  Arrays
     * @param     array  *$xss
     * @return    array
     */
    // abstract public static function concat();

    /**
     * @category  Arrays
     * @param     array   $xs
     * @param     string  $separator
     * @return    string
     */
    public static function join($xs, $separator = ',')
    {
        $str = '';
        foreach ($xs as $x) {
            $str .= $x . $separator;
        }
        return substr($str, 0, -strlen($separator));
    }

    /**
     * @category  Objects
     * @param     array  $xs
     * @return    array
     */
    public static function keys($xs)
    {
        if ($xs instanceof \Traversable) {
            return static::map($xs, function($x, $k) {
                return $k;
            });
        }
        return array_keys($xs);
    }

    /**
     * @category  Objects
     * @param     array  $xs
     * @return    array|Iterator
     */
    public static function values($xs)
    {
        if ($xs instanceof \Traversable) {
            return $xs;
        }
        return array_values($xs);
    }

    /**
     * @category  Objects
     * @param     array    $xs
     * @return    array
     */
    public static function pairs($xs)
    {
        return static::map($xs, function($x, $k) {
            return array($k, $x);
        });
    }

    /**
     * @category  Objects
     * @param     array  $xs
     * @return    array
     */
    public static function invert($xs)
    {
        $result = array();
        foreach ($xs as $k => $x) {
            $result[$x] = $k;
        }
        return $result;
    }

    /**
     * @varargs
     * @category  Objects
     * @param     array  $destination
     * @param     array  *$sources
     * @return    array
     */
    public static function extend($destination)
    {
        $destination = static::toArray($destination);
        $sources = array_slice(func_get_args(), 1);
        foreach ($sources as $xs) {
            foreach ($xs as $k => $x) {
                $destination[$k] = $x;
            }
        }
        return $destination;
    }

    /**
     * @varargs
     * @category  Objects
     * @param     array         $xs
     * @param     array|string  *$keys
     * @return    array
     */
    public static function pick($xs)
    {
        $whitelist = array();
        $keys = array_slice(func_get_args(), 1);

        foreach ($keys as $key) {
            if (static::isTraversable($key)) {
                foreach ($key as $k) {
                    $whitelist[$k] = 0;
                }
            } else {
                $whitelist[$key] = 0;
            }
        }

        return static::filter($xs, function($x, $k) use ($whitelist) {
            return isset($whitelist[$k]);
        });
    }

    /**
     * @varargs
     * @category  Objects
     * @param     array         $xs
     * @param     array|string  *$keys
     * @return    array
     */
    public static function omit($xs)
    {
        $blacklist = array();
        $keys = array_slice(func_get_args(), 1);

        foreach ($keys as $key) {
            if (static::isTraversable($key)) {
                foreach ($key as $k) {
                    $blacklist[$k] = 0;
                }
            } else {
                $blacklist[$key] = 0;
            }
        }

        return static::filter($xs, function($x, $k) use ($blacklist) {
            return !isset($blacklist[$k]);
        });
    }

    /**
     * @varargs
     * @category  Objects
     * @param     array  $xs
     * @param     array  *$defaults
     * @return    array
     */
    public static function defaults($xs)
    {
        $xs = static::toArray($xs);
        $defaults = array_slice(func_get_args(), 1);
        foreach ($defaults as $default) {
            foreach ($default as $k => $x) {
                if (!isset($xs[$k])) {
                    $xs[$k] = $x;
                }
            }
        }

        return $xs;
    }

    /**
     * @category  Objects
     * @param     mixed     $value
     * @param     callable  $interceptor
     * @return    mixed
     */
    public static function tap($value, $interceptor)
    {
        call_user_func($interceptor, $value);
        return $value;
    }

    /**
     * @category  Objects
     * @param     mixed  $xs
     * @return    boolean
     */
    public static function isArray($value)
    {
        return is_array($value) || $value instanceof \ArrayAccess;
    }

    /**
     * @category  Objects
     * @param     mixed  $xs
     * @return    boolean
     */
    public static function isTraversable($value)
    {
        return is_array($value) || $value instanceof \Traversable;
    }

    /**
     * @category  Utility
     * @param     mixed  $value
     * @return    mixed
     */
    public static function identity($value)
    {
        return $value;
    }

    /**
     * @category  Chaining
     * @param     mixed  $value
     * @return    Wrapper
     */
    public static function chain($value)
    {
        return new Internal\Wrapper($value, get_called_class());
    }

    /**
     * Parallel version of map()
     *
     * @category  Parallel
     * @param     array     $xs
     * @param     callable  $f
     * @param     int       $n
     * @param     int       $timeout
     * @return    Parallel
     */
    public static function parMap($xs, $f, $n = null, $timeout = null)
    {
        $parallel = new Internal\Parallel($f, $n, $timeout);
        $parallel->pushAll($xs);
        return $parallel;
    }

    /**
     * @param   mixed  $value
     * @return  callable
     */
    protected static function createCallback($value)
    {
        if (is_scalar($value)) {
            return function($xs) use ($value) {
                return is_array($xs) ? $xs[$value] : $xs->$value;
            };
        }
        if (is_callable($value)) {
            return $value;
        }
        return __CLASS__.'::identity';
    }

    /**
     * @param   array|Iterator  $xs
     * @return  Iterator
     */
    protected static function wrapIterator($xs)
    {
        if ($xs instanceof \Iterator) {
            return $xs;
        }
        if ($xs instanceof \Traversable) {
            return new \IteratorIterator($xs);
        }
        if (is_array($xs)) {
            return new \ArrayIterator($xs);
        }
        return $xs;
    }

    /**
     * @param   array|Iterator  $xs
     * @return  array
     */
    protected static function extractIterator($xs)
    {
        return $xs instanceof \Traversable
             ? iterator_to_array($xs, false)
             : $xs;
    }
}
