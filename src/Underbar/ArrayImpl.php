<?php
/**
 * This file is part of the Underbar.php package.
 *
 * Copyright (C) 2013 Shota Nozaki <emonkak@gmail.com>
 *
 * Licensed under the MIT License
 */

namespace Underbar;

class ArrayImpl extends AbstractImpl
{
    /**
     * @chainable
     * @see       ImplementorInterface
     * @category  Collections
     * @param     array     $xs
     * @param     callable  $f
     * @return    array
     */
    public static function map($xs, $f)
    {
        $ys = array();
        foreach ($xs as $k => $x) {
            $ys[$k] = call_user_func($f, $x, $k, $xs);
        }
        return $ys;
    }

    /**
     * @chainable
     * @see       ImplementorInterface
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

    /**
     * @chainable
     * @see       ImplementorInterface
     * @category  Collections
     * @param     array            $xs
     * @param     callable|string  $f
     * @return    array
     */
    public static function sortBy($xs, $f)
    {
        $f = static::createCallback($f);
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
     * @chainable
     * @see       ImplementorInterface
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
     * @chainable
     * @see       ImplementorInterface
     * @category  Collections
     * @param     array            $xs
     * @param     callable|string  $x
     * @return    array
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
     * @chainable
     * @see       ImplementorInterface
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
     * @chainable
     * @category  Collections
     * @param     array  $xs
     * @return    array
     */
    public static function memoize($xs)
    {
        return static::toArray($xs);
    }

    /**
     * @chainable
     * @see       ImplementorInterface
     * @category  Arrays
     * @param     array  $xs
     * @param     int    $n
     * @return    array
     */
    public static function firstN($xs, $n = null)
    {
        return $n > 0
             ? array_slice(static::extractIterator($xs), 0, $n)
             : array();
    }

    /**
     * @chainable
     * @see       ImplementorInterface
     * @category  Arrays
     * @param     array  $xs
     * @param     int    $n
     * @return    array
     */
    public static function initial($xs, $n = 1, $guard = null)
    {
        if ($guard !== null) {
            $n = 1;
        }
        return $n > 0
             ? array_slice(static::extractIterator($xs), 0, -$n)
             : array();
    }

    /**
     * @chainable
     * @see       ImplementorInterface
     * @category  Arrays
     * @param     array  $xs
     * @param     int    $n
     * @return    array
     */
    public static function rest($xs, $n = 1, $guard = null)
    {
        if ($guard !== null) {
            $n = 1;
        }
        return array_slice(static::extractIterator($xs), $n);
    }

    /**
     * @chainable
     * @see       ImplementorInterface
     * @category  Arrays
     * @param     array     $xs
     * @param     callable  $f
     * @return    array
     */
    public static function takeWhile($xs, $f)
    {
        $result = array();
        foreach ($xs as $i => $x) {
            if (!call_user_func($f, $x, $i, $xs)) {
                break;
            }
            $result[] = $x;
        }
        return $result;
    }

    /**
     * @chainable
     * @see       ImplementorInterface
     * @category  Arrays
     * @param     array     $xs
     * @param     callable  $f
     * @return    array
     */
    public static function dropWhile($xs, $f)
    {
        $result = array();
        $accepted = false;
        foreach ($xs as $i => $x) {
            if ($accepted || ($accepted = !call_user_func($f, $x, $i, $xs))) {
                $result[] = $x;
            }
        }
        return $result;
    }

    /**
     * @chainable
     * @see       ImplementorInterface
     * @category  Arrays
     * @param     array    $xs
     * @param     boolean  $shallow
     * @return    array
     */
    public static function flatten($xs, $shallow = false)
    {
        return static::_flatten($xs, $shallow);
    }

    private static function _flatten($xss, $shallow, &$output = array())
    {
        foreach ($xss as $xs) {
            if (static::isTraversable($xs)) {
                if ($shallow) {
                    foreach ($xs as $x) {
                        $output[] = $x;
                    }
                } else {
                    static::_flatten($xs, $shallow, $output);
                }
            } else {
                $output[] = $xs;
            }
        }
        return $output;
    }

    /**
     * @chainable
     * @varargs
     * @see       ImplementorInterface
     * @category  Arrays
     * @param     array  $xss
     * @return    array
     */
    public static function unzip($xss)
    {
        $yss = $zss = $result = array();
        $loop = true;

        foreach ($xss as $xs) {
            $yss[] = $wrapped = static::wrapIterator($xs);
            $wrapped->rewind();
            $loop = $loop && $wrapped->valid();
            $zss[] = $wrapped->current();
        }

        if (!empty($zss)) while ($loop) {
            $result[] = $zss;
            $zss = array();
            $loop = true;
            foreach ($yss as $ys) {
                $ys->next();
                $zss[] = $ys->current();
                $loop = $loop && $ys->valid();
            }
        }

        return $result;
    }

    /**
     * @chainable
     * @see       ImplementorInterface
     * @category  Arrays
     * @param     int  $start
     * @param     int  $stop
     * @param     int  $step
     * @return    array
     */
    public static function range($start, $stop = null, $step = 1)
    {
        if ($stop === null) {
            $stop = $start;
            $start = 0;
        }

        $l = max(ceil(($stop - $start) / $step), 0);
        $range = array();

        for ($i = 0; $i < $l; $i++) {
            $range[] = $start;
            $start += $step;
        }

        return $range;
    }

    /**
     * @chainable
     * @see       ImplementorInterface
     * @category  Arrays
     * @param     array  $xs
     * @param     int    $n
     * @return    array
     */
    public static function cycle($xs, $n = -1)
    {
        $result = array();
        if ($n < 0) {
            throw new \OverflowException();
        }
        while ($n-- > 0) {
            foreach ($xs as $x) {
                $result[] = $x;
            }
        }
        return $result;
    }

    /**
     * @chainable
     * @see       ImplementorInterface
     * @category  Arrays
     * @param     mixed  $value
     * @param     int    $n
     * @return    array
     */
    public static function repeat($value, $n = -1)
    {
        if ($n < 0) {
            throw new \OverflowException();
        }
        return $n === 0 ? array() : array_fill(0, $n, $value);
    }

    /**
     * @chainable
     * @see       ImplementorInterface
     * @category  Arrays
     * @param     mixed     $acc
     * @param     callable  $f
     * @return    array
     * @throws    OverflowException
     */
    public static function iterate($acc, $f)
    {
        throw new \OverflowException();
    }

    /**
     * @chainable
     * @see       ImplementorInterface
     * @category  Arrays
     * @category  Arrays
     * @param     array  $xs
     * @return    array
     */
    public static function reverse($xs)
    {
        return array_reverse(static::extractIterator($xs));
    }

    /**
     * @chainable
     * @see       ImplementorInterface
     * @category  Arrays
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
     * @chainable
     * @varargs
     * @see       ImplementorInterface
     * @category  Arrays
     * @param     array  *$xss
     * @return    array
     */
    public static function concat()
    {
        $result = array();
        foreach (func_get_args() as $xs) {
            $result = array_merge($result, static::extractIterator($xs));
        }
        return $result;
    }
}
