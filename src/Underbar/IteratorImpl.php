<?php
/**
 * This file is part of the Underbar.php package.
 *
 * Copyright (C) 2013 Shota Nozaki <emonkak@gmail.com>
 *
 * Licensed under the MIT License
 */

namespace Underbar;

class IteratorImpl extends AbstractImpl
{
    /**
     * @chainable
     * @see       ImplementorInterface
     * @category  Collections
     * @param     array     $xs
     * @param     callable  $f
     * @return    Iterator
     */
    public static function map($xs, $f)
    {
        return new Iterator\MapIterator(static::wrapIterator($xs), $f);
    }

    /**
     * @chainable
     * @see       ImplementorInterface
     * @category  Collections
     * @param     array     $xs
     * @param     callable  $f
     * @return    Iterator
     */
    public static function filter($xs, $f)
    {
        return new Iterator\FilterIterator(static::wrapIterator($xs), $f);
    }

    /**
     * @chainable
     * @see       ImplementorInterface
     * @category  Collections
     * @param     array            $xs
     * @param     callable|string  $f
     * @return    Iterator
     */
    public static function sortBy($xs, $f)
    {
        return new Iterator\DelayIterator(function() use ($xs, $f) {
            return new \ArrayIterator(ArrayImpl::sortBy($xs, $f));
        });
    }

    /**
     * @chainable
     * @see       ImplementorInterface
     * @category  Collections
     * @param     array            $xs
     * @param     callable|string  $f
     * @return    Iterator
     */
    public static function groupBy($xs, $f = null)
    {
        return new Iterator\DelayIterator(function() use ($xs, $f) {
            return new \ArrayIterator(ArrayImpl::groupBy($xs, $f));
        });
    }

    /**
     * @chainable
     * @see       ImplementorInterface
     * @category  Collections
     * @param     array            $xs
     * @param     callable|string  $x
     * @return    Iterator
     */
    public static function countBy($xs, $f = null)
    {
        return new Iterator\DelayIterator(function() use ($xs, $f) {
            return new \ArrayIterator(ArrayImpl::countBy($xs, $f));
        });
    }

    /**
     * @chainable
     * @see       ImplementorInterface
     * @category  Collections
     * @param     array  $xs
     * @return    Iterator
     */
    public static function shuffle($xs)
    {
        return new Iterator\DelayIterator(function() use ($xs) {
            return new \ArrayIterator(ArrayImpl::shuffle($xs));
        });
    }

    /**
     * @chainable
     * @see       ImplementorInterface
     * @category  Collections
     * @param     array  $xs
     * @return    Iterator
     */
    public static function memoize($xs)
    {
        return new Iterator\MemoizeIterator(static::wrapIterator($xs));
    }

    /**
     * @chainable
     * @see       ImplementorInterface
     * @category  Arrays
     * @param     array  $xs
     * @param     int    $n
     * @return    Iterator
     */
    public static function firstN($xs, $n = null)
    {
        return new Iterator\TakeIterator(static::wrapIterator($xs), $n);
    }

    /**
     * @chainable
     * @see       ImplementorInterface
     * @category  Arrays
     * @param     array  $xs
     * @param     int    $n
     * @return    Iterator
     */
    public static function initial($xs, $n = 1, $guard = null)
    {
        if ($guard !== null) {
            $n = 1;
        }
        return new Iterator\InitialIterator(static::wrapIterator($xs), $n);
    }

    /**
     * @chainable
     * @see       ImplementorInterface
     * @category  Arrays
     * @param     array  $xs
     * @param     int    $n
     * @return    Iterator
     */
    public static function rest($xs, $n = 1, $guard = null)
    {
        if ($guard !== null) {
            $n = 1;
        }
        return new Iterator\DropIterator(static::wrapIterator($xs), $n);
    }

    /**
     * @chainable
     * @see       ImplementorInterface
     * @category  Arrays
     * @param     array     $xs
     * @param     callable  $f
     * @return    Iterator
     */
    public static function takeWhile($xs, $f)
    {
        return new Iterator\TakeWhileIterator(static::wrapIterator($xs), $f);
    }

    /**
     * @chainable
     * @see       ImplementorInterface
     * @category  Arrays
     * @param     array     $xs
     * @param     callable  $f
     * @return    Iterator
     */
    public static function dropWhile($xs, $f)
    {
        return new Iterator\DropWhileIterator(static::wrapIterator($xs), $f);
    }

    /**
     * @chainable
     * @see       ImplementorInterface
     * @category  Arrays
     * @param     array  $xs
     * @param     int    $depth
     * @return    Iterator
     */
    public static function flatten($xs, $depth = -1)
    {
        return new Iterator\FlattenIterator(static::wrapIterator($xs), $depth);
    }

    /**
     * @chainable
     * @varargs
     * @see       ImplementorInterface
     * @category  Arrays
     * @param     array  $xss
     * @return    Iterator
     */
    public static function unzip($xss)
    {
        $it = new Iterator\ZipIterator();
        foreach ($xss as $xs) {
            $it->attachIterator(static::wrapIterator($xs));
        }
        return $it;
    }

    /**
     * @chainable
     * @see       ImplementorInterface
     * @category  Arrays
     * @param     int  $start
     * @param     int  $stop
     * @param     int  $step
     * @return    Iterator
     */
    public static function range($start, $stop = null, $step = 1)
    {
        if ($stop === null) {
            $stop = $start;
            $start = 0;
        }
        return new Iterator\RangeIterator($start, $stop, $step);
    }

    /**
     * @chainable
     * @see       ImplementorInterface
     * @category  Arrays
     * @param     array  $xs
     * @param     int    $n
     * @return    Iterator
     */
    public static function cycle($xs, $n = -1)
    {
        return new Iterator\CycleIterator(static::memoize($xs), $n);
    }

    /**
     * @chainable
     * @see       ImplementorInterface
     * @category  Arrays
     * @param     mixed  $value
     * @param     int    $n
     * @return    Iterator
     */
    public static function repeat($value, $n = -1)
    {
        return new Iterator\RepeatIterator($value, $n);
    }

    /**
     * @chainable
     * @see       ImplementorInterface
     * @category  Arrays
     * @param     mixed     $acc
     * @param     callable  $f
     * @return    Iterator
     */
    public static function iterate($acc, $f)
    {
        return new Iterator\IterateIterator($acc, $f);
    }

    /**
     * @chainable
     * @see       ImplementorInterface
     * @category  Arrays
     * @category  Arrays
     * @param     array  $xs
     * @return    Iterator
     */
    public static function reverse($xs)
    {
        return new Iterator\DelayIterator(function() use ($xs) {
            return new \ArrayIterator(ArrayImpl::reverse($xs));
        });
    }

    /**
     * @chainable
     * @see       ImplementorInterface
     * @category  Arrays
     * @category  Arrays
     * @param     array     $xs
     * @param     callable  $compare
     * @return    Iterator
     */
    public static function sort($xs, $compare = null)
    {
        return new Iterator\DelayIterator(function() use ($xs, $compare) {
            return new \ArrayIterator(ArrayImpl::sort($xs, $compare));
        });
    }

    /**
     * @chainable
     * @varargs
     * @see       ImplementorInterface
     * @category  Arrays
     * @param     array  *$xss
     * @return    Iterator
     */
    public static function concat()
    {
        $it = new Iterator\ConcatIterator();
        foreach (func_get_args() as $array) {
            $it->append(static::wrapIterator($array));
        }
        return $it;
    }
}
