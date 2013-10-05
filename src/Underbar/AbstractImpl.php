<?php
/**
 * This file is part of the Underbar.php package.
 *
 * Copyright (C) 2013 Shota Nozaki <emonkak@gmail.com>
 *
 * Licensed under the MIT License
 */

namespace Underbar;

class AbstractImpl
{
    /**
     * @chainable
     * @category  Collections
     * @param     array     $xs
     * @param     callable  $f
     * @return    array|Iterator
     */
    final public static function each($xs, $f)
    {
        foreach ($xs as $k => $x) {
            call_user_func($f, $x, $k, $xs);
        }
        return $xs;
    }

    /**
     * @chainable
     * @category  Collections
     */
    final public static function collect($xs, $f, $g = null)
    {
        return static::map($xs, $f, $g);
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
    final public static function reduce($xs, $f, $acc)
    {
        foreach ($xs as $k => $x) {
            $acc = call_user_func($f, $acc, $x, $k, $xs);
        }
        return $acc;
    }

    final public static function inject($xs, $f, $acc)
    {
        return static::reduce($xs, $f, $acc);
    }

    final public static function foldl($xs, $f, $acc)
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
    final public static function reduceRight($xs, $f, $acc)
    {
        return static::reduce(static::reverse($xs), $f, $acc);
    }

    final public static function foldr($xs, $f, $acc)
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
    final public static function find($xs, $f)
    {
        foreach ($xs as $k => $x) {
            if (call_user_func($f, $x, $k, $xs)) {
                return $x;
            }
        }
    }

    final public static function detect($xs, $f)
    {
        return static::find($xs, $f);
    }

    /**
     * @chainable
     * @category  Collections
     */
    final public static function select($xs, $f)
    {
        return static::filter($xs, $f);
    }

    /**
     * @chainable
     * @category  Collections
     * @param     array  $xs
     * @param     array  $properties
     * @return    array|Iterator
     */
    final public static function where($xs, $properties)
    {
        return static::filter($xs, function($x) use ($properties) {
            foreach ($properties as $key => $value) {
                if (!((isset($x->$key) && $x->$key == $value)
                      || (isset($x[$key]) && $x[$key] == $value))) {
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
    final public static function findWhere($xs, $properties)
    {
        return static::find($xs, function($x) use ($properties) {
            foreach ($properties as $key => $value) {
                if (!((isset($x->$key) && $x->$key == $value)
                      || (isset($x[$key]) && $x[$key] == $value))) {
                    return false;
                }
            }
            return true;
        });
    }

    /**
     * @chainable
     * @category  Collections
     * @param     array     $xs
     * @param     callable  $f
     * @return    array|Iterator
     */
    final public static function reject($xs, $f)
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
    final public static function every($xs, $f = null)
    {
        $f = static::createCallback($f);

        foreach ($xs as $k => $x) {
            if (!call_user_func($f, $x, $k, $xs)) {
                return false;
            }
        }

        return true;
    }

    final public static function all($xs, $f = null)
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
    final public static function some($xs, $f = null)
    {
        $f = static::createCallback($f);

        foreach ($xs as $k => $x) {
            if (call_user_func($f, $x, $k, $xs)) {
                return true;
            }
        }

        return false;
    }

    final public static function any($xs, $f = null)
    {
        return static::some($xs, $f);
    }

    /**
     * @category  Collections
     * @param     array  $xs
     * @param     mixed  $target
     * @return    boolean
     */
    final public static function contains($xs, $target)
    {
        foreach ($xs as $x) {
            if ($x === $target) {
                return true;
            }
        }
        return false;
    }

    /**
     * @chainable
     * @varargs
     * @category  Collections
     * @param     array   $xs
     * @param     string  $method
     * @param     miexed  *$arguments
     * @return    array|Iterator
     */
    final public static function invoke($xs, $method)
    {
        $args = array_slice(func_get_args(), 2);
        return static::map($xs, function($x) use ($method, $args) {
            return call_user_func_array(array($x, $method), $args);
        });
    }

    /**
     * @chainable
     * @category  Collections
     * @param     array   $xs
     * @param     string  $property
     * @return    array|Iterator
     */
    final public static function pluck($xs, $property)
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
    final public static function max($xs, $f = null)
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
    final public static function min($xs, $f = null)
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
    final public static function sum($xs)
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
    final public static function product($xs)
    {
        $acc = 1;
        foreach ($xs as $x) {
            $acc *= $x;
        }
        return $acc;
    }

    /**
     * @category  Collections
     * @param     array  $xs
     * @return    float
     */
    final public static function average($xs)
    {
        $total = 0.0;
        $n = 0;
        foreach ($xs as $x) {
            $total += $x;
            $n++;
        }
        return $n > 0 ? $total / $n : INF;
    }

    /**
     * @category  Collections
     * @param     mixed  $xs
     * @return    array
     */
    final public static function toArray($xs)
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
    final public static function toList($xs)
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
     * @param     mixed  $xs
     * @return    int
     */
    final public static function size($xs)
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
     * @chainable
     * @category  Arrays
     * @param     array  $xs
     * @param     int    $n
     * @return    array|mixed|null|Iterator
     */
    final public static function first($xs, $n = null, $guard = null)
    {
        if ($n !== null && $guard === null) {
            return static::firstN($xs, $n);
        } else {
            return static::firstOrElse($xs, null);
        }
    }

    /**
     * @chainable
     * @category  Collections
     */
    final public static function head($xs, $n = null, $guard = null)
    {
        return static::first($xs, $n, $guard);
    }

    /**
     * @chainable
     * @category  Collections
     */
    final public static function take($xs, $n = null, $guard = null)
    {
        return static::first($xs, $n, $guard);
    }

    /**
     * @category  Arrays
     * @param     array  $xs
     * @param     mixed  $default
     * @return    mixed
     */
    final public static function firstOrElse($xs, $default)
    {
        foreach ($xs as $x) {
            return $x;
        }
        return $default;
    }

    /**
     * @chainable
     * @category  Arrays
     * @param     array  $xs
     * @return    array|mixed|null|Iterator
     */
    final public static function last($xs, $n = null, $guard = null)
    {
        if ($n !== null && $guard === null) {
            return static::lastN($xs, $n);
        } else {
            return static::lastOrElse($xs, null);
        }
    }

    /**
     * @category  Arrays
     * @param     array  $xs
     * @param     mixed  $default
     * @return    mixed
     */
    final public static function lastOrElse($xs, $default)
    {
        $x = $default;
        foreach ($xs as $x) {
        }
        return $x;
    }

    /**
     * @chainable
     * @category  Collections
     */
    final public static function tail($xs, $n = 1, $guard = null)
    {
        return static::rest($xs, $n, $guard);
    }

    /**
     * @chainable
     * @category  Collections
     */
    final public static function drop($xs, $n = 1, $guard = null)
    {
        return static::rest($xs, $n, $guard);
    }

    /**
     * @chainable
     * @category  Arrays
     * @param     array  $xs
     * @return    array|Iterator
     */
    final public static function compact($xs)
    {
        return static::filter($xs, __CLASS__.'::identity');
    }

    /**
     * @chainable
     * @varargs
     * @category  Arrays
     * @param     array  $xs
     * @param     mixed  *$values
     * @return    array|Iterator
     */
    final public static function without($xs)
    {
        return static::difference($xs, array_slice(func_get_args(), 1));
    }

    /**
     * @chainable
     * @varargs
     * @category  Arrays
     * @param     array  *$xss
     * @return    array|Iterator
     */
    final public static function union()
    {
        return static::uniq(call_user_func_array(
            get_called_class().'::concat',
            func_get_args())
        );
    }

    /**
     * @chainable
     * @varargs
     * @category  Arrays
     * @param     array  $xs
     * @param     array  *$others
     * @return    array|Iterator
     */
    final public static function difference($xs)
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
     * @chainable
     * @category  Arrays
     */
    final public static function unique($xs, $f = null)
    {
        return static::uniq($xs, $f);
    }

    /**
     * @chainable
     * @varargs
     * @category  Arrays
     * @param     array  *$xss
     * @return    array|Iterator
     */
    final public static function zip()
    {
        return static::unzip(func_get_args());
    }

    /**
     * Porting from the Prelude of Haskell.
     *
     * @chainable
     * @varargs
     * @category  Arrays
     * @param     array     *$xss
     * @param     callable  $f
     * @return    array|Iterator
     */
    final public static function zipWith()
    {
        $xss = func_get_args();
        $f = array_pop($xss);
        $zipped = static::unzip($xss);
        return static::map($zipped, function($xs, $i, $xss) use ($f) {
            return call_user_func_array($f, $xs);
        });
    }

    /**
     * @chainable
     * @category  Arrays
     * @param     array  $xs
     * @param     array  $values
     * @return    array
     */
    final public static function object($xs, $values = null)
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
    final public static function indexOf($xs, $value, $isSorted = 0)
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
    final public static function lastIndexOf($xs, $x, $fromIndex = null)
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
    final public static function sortedIndex($xs, $value, $f = null)
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
     * @param     array   $xs
     * @param     string  $separator
     * @return    string
     */
    final public static function join($xs, $separator = ',')
    {
        $str = '';
        foreach ($xs as $x) {
            $str .= $x . $separator;
        }
        return substr($str, 0, -strlen($separator));
    }

    /**
     * @chainable
     * @category  Objects
     * @param     array  $xs
     * @return    array|Iterator
     */
    final public static function keys($xs)
    {
        if ($xs instanceof \Traversable) {
            return static::map($xs, function($x, $k) {
                return $k;
            });
        }
        return array_keys($xs);
    }

    /**
     * @chainable
     * @category  Objects
     * @param     array  $xs
     * @return    array|Iterator|Iterator
     */
    final public static function values($xs)
    {
        if ($xs instanceof \Traversable) {
            return $xs;
        }
        return array_values($xs);
    }

    /**
     * @chainable
     * @category  Objects
     * @param     array    $xs
     * @return    array|Iterator
     */
    final public static function pairs($xs)
    {
        return static::map($xs, function($x, $k) {
            return array($k, $x);
        });
    }

    /**
     * @chainable
     * @category  Objects
     * @param     array  $xs
     * @return    array
     */
    final public static function invert($xs)
    {
        return static::map($xs, function($x, $k) {
            return $k;
        }, function($k, $x) {
            return $x;
        });
    }

    /**
     * @chainable
     * @varargs
     * @category  Objects
     * @param     array  $destination
     * @param     array  *$sources
     * @return    array
     */
    final public static function extend($destination)
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
     * @category  Objects
     * @param     array   $xs
     * @param     string  $key
     * @return    mixed|null
     */
    final public static function get($xs, $key)
    {
        return static::getOrElse($xs, $key, null);
    }

    /**
     * @category  Objects
     * @param     array   $xs
     * @param     string  $key
     * @param     mixed   $default
     * @return    mixed
     */
    final public static function getOrElse($xs, $key, $default)
    {
        if (static::isArray($xs)) {
            return isset($xs[$key]) ? $xs[$key] : $default;
        }

        foreach ($xs as $k => $x) {
            if ($k === $key) {
                return $x;
            }
        }

        return $default;
    }

    /**
     * @chainable
     * @varargs
     * @category  Objects
     * @param     array         $xs
     * @param     array|string  *$keys
     * @return    array|Iterator
     */
    final public static function pick($xs)
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
     * @chainable
     * @varargs
     * @category  Objects
     * @param     array         $xs
     * @param     array|string  *$keys
     * @return    array|Iterator
     */
    final public static function omit($xs)
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
     * @chainable
     * @varargs
     * @category  Objects
     * @param     array  $xs
     * @param     array  *$defaults
     * @return    array
     */
    final public static function defaults($xs)
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
     * @chainable
     * @category  Objects
     * @param     mixed     $value
     * @param     callable  $interceptor
     * @return    mixed
     */
    final public static function tap($value, $interceptor)
    {
        call_user_func($interceptor, $value);
        return $value;
    }

    /**
     * @category  Objects
     * @param     mixed  $xs
     * @return    boolean
     */
    final public static function isEmpty($xs)
    {
        if ($xs instanceof \Countable) {
            return count($xs) === 0;
        } elseif ($xs instanceof \Iterator) {
            $xs->rewind();
            return !$xs->valid();
        } elseif ($xs instanceof \IteratorAggregate) {
            return static::isEmpty($xs->getIterator());
        } elseif ($xs instanceof \Traversable) {
            foreach ($xs as $x) {
                return true;
            }
            return false;
        } else {
            return empty($xs);
        }
    }

    /**
     * @category  Utility
     * @param     mixed  $value
     * @return    mixed
     */
    final public static function identity($value)
    {
        return $value;
    }

    /**
     * @category  Chaining
     * @param     mixed  $value
     * @return    Wrapper
     */
    final public static function chain($value)
    {
        return new Internal\Wrapper($value, get_called_class());
    }

    /**
     * Parallel version of map()
     *
     * @chainable
     * @category  Parallel
     * @param     array     $xs
     * @param     callable  $f
     * @param     int       $n
     * @param     int       $timeout
     * @return    Parallel
     */
    final public static function parMap($xs, $f, $n = 4, $timeout = null)
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
     * @return  array
     */
    protected static function extractIterator($xs)
    {
        return $xs instanceof \Traversable
             ? iterator_to_array($xs, false)
             : $xs;
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
     * @param   mixed  $value
     * @return  boolean
     */
    protected static function isTraversable($value)
    {
        return is_array($value) || $value instanceof \Traversable;
    }

    /**
     * @param   mixed  $value
     * @return  boolean
     */
    protected static function isArray($value)
    {
        return is_array($value) || $value instanceof \ArrayAccess;
    }
}
