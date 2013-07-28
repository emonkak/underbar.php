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
     * @see       ProviderInterface
     * @category  Collections
     * @param     array     $xs
     * @param     callable  $f
     * @return    Iterator
     */
    public static function map($xs, $f)
    {
        return new Iterator\MapIterator(self::wrapIterator($xs), $f);
    }

    /**
     * @chainable
     * @see       ProviderInterface
     * @category  Collections
     * @param     array     $xs
     * @param     callable  $f
     * @return    Iterator
     */
    public static function filter($xs, $f)
    {
        return new Iterator\FilterIterator(self::wrapIterator($xs), $f);
    }

    /**
     * @chainable
     * @see       ProviderInterface
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
     * @see       ProviderInterface
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
     * @see       ProviderInterface
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
     * @see       ProviderInterface
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
     * @see       ProviderInterface
     * @category  Collections
     * @param     array  $xs
     * @return    Iterator
     */
    public static function memoize($xs)
    {
        return new Iterator\MemoizeIterator(self::wrapIterator($xs));
    }

    /**
     * @chainable
     * @see       ProviderInterface
     * @category  Arrays
     * @param     array  $xs
     * @param     int    $n
     * @return    Iterator
     */
    public static function firstN($xs, $n)
    {
        return new Iterator\TakeIterator(self::wrapIterator($xs), $n);
    }

    /**
     * @chainable
     * @see       ProviderInterface
     * @category  Arrays
     * @param     array  $xs
     * @param     int    $n
     * @return    Iterator
     */
    public static function lastN($xs, $n)
    {
        return new Iterator\DelayIterator(function() use ($xs, $n) {
            return ArrayImpl::lastN($xs, $n);
        });
    }

    /**
     * @chainable
     * @see       ProviderInterface
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
        return new Iterator\InitialIterator(self::wrapIterator($xs), $n);
    }

    /**
     * @chainable
     * @see       ProviderInterface
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
        return new Iterator\DropIterator(self::wrapIterator($xs), $n);
    }

    /**
     * @chainable
     * @see       ProviderInterface
     * @category  Arrays
     * @param     array     $xs
     * @param     callable  $f
     * @return    Iterator
     */
    public static function takeWhile($xs, $f)
    {
        return new Iterator\TakeWhileIterator(self::wrapIterator($xs), $f);
    }

    /**
     * @chainable
     * @see       ProviderInterface
     * @category  Arrays
     * @param     array     $xs
     * @param     callable  $f
     * @return    Iterator
     */
    public static function dropWhile($xs, $f)
    {
        return new Iterator\DropWhileIterator(self::wrapIterator($xs), $f);
    }

    /**
     * @chainable
     * @see       ProviderInterface
     * @category  Arrays
     * @param     array    $xs
     * @param     boolean  $shallow
     * @return    Iterator
     */
    public static function flatten($xs, $shallow = false)
    {
        $depth = $shallow ? 1 : -1;
        return new Iterator\FlattenIterator(self::wrapIterator($xs), $depth);
    }

    /**
     * @chainable
     * @varargs
     * @see       ProviderInterface
     * @category  Arrays
     * @param     array  $xss
     * @return    Iterator
     */
    public static function unzip($xss)
    {
        $it = new Iterator\ZipIterator();
        foreach ($xss as $xs) {
            $it->attachIterator(self::wrapIterator($xs));
        }
        return $it;
    }

    /**
     * @chainable
     * @see       ProviderInterface
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
     * @see       ProviderInterface
     * @category  Arrays
     * @param     array  $xs
     * @param     int    $n
     * @return    Iterator
     */
    public static function cycle($xs, $n = -1)
    {
        return new Iterator\CycleIterator(self::memoize($xs), $n);
    }

    /**
     * @chainable
     * @see       ProviderInterface
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
     * @see       ProviderInterface
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
     * @see       ProviderInterface
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
     * @see       ProviderInterface
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
     * @see       ProviderInterface
     * @category  Arrays
     * @param     array  *$xss
     * @return    Iterator
     */
    public static function concat()
    {
        $it = new Iterator\ConcatIterator();
        foreach (func_get_args() as $array) {
            $it->append(self::wrapIterator($array));
        }
        return $it;
    }
}
