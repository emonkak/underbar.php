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
        return new Iterator\MapIterator(self::wrapIterator($xs), $f);
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
        return new \CallbackFilterIterator(self::wrapIterator($xs), $f);
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
        return new Iterator\DelegateIterator(function() use ($xs, $f) {
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
        return new Iterator\DelegateIterator(function() use ($xs, $f) {
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
        return new Iterator\DelegateIterator(function() use ($xs, $f) {
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
        return new Iterator\DelegateIterator(function() use ($xs) {
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
        return new Iterator\MemoizeIterator(self::wrapIterator($xs));
    }

    /**
     * @chainable
     * @see       ImplementorInterface
     * @category  Arrays
     * @param     array  $xs
     * @param     int    $n
     * @return    Iterator
     */
    public static function firstN($xs, $n)
    {
        return $n > 0
             ? new \LimitIterator(self::wrapIterator($xs), 0, $n)
             : new \EmptyIterator;
    }

    /**
     * @chainable
     * @see       ImplementorInterface
     * @category  Arrays
     * @param     array  $xs
     * @param     int    $n
     * @return    Iterator
     */
    public static function lastN($xs, $n)
    {
        return new Iterator\DelegateIterator(function() use ($xs, $n) {
            return ArrayImpl::lastN($xs, $n);
        });
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
        return new Iterator\InitialIterator(self::wrapIterator($xs), $n);
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
        return new \LimitIterator(self::wrapIterator($xs), $n);
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
        return new Iterator\TakeWhileIterator(self::wrapIterator($xs), $f);
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
        return new Iterator\DropWhileIterator(self::wrapIterator($xs), $f);
    }

    /**
     * @chainable
     * @see       ImplementorInterface
     * @category  Arrays
     * @param     array    $xs
     * @param     boolean  $shallow
     * @return    Iterator
     */
    public static function flatten($xs, $shallow = false)
    {
        $inner = new Iterator\FlattenIterator(
            self::wrapIterator($xs),
            $shallow ? 1 : -1
        );
        return new \RecursiveIteratorIterator($inner);
    }

    /**
     * @chainable
     * @varargs
     * @see       ImplementorInterface
     * @category  Arrays
     * @param     array  $xs
     * @param     array  *$others
     * @return    array
     */
    public static function intersection($xs)
    {
        $others = [];
        foreach (array_slice(func_get_args(), 1) as $ys) {
            $others[] = self::extractIterator($ys);
        }

        $size = count($others);
        if ($size === 1) {
            $others = $others[0];
        } elseif ($size > 1) {
            $others = call_user_func_array('array_intersect', $others);
        }

        return new Iterator\IntersectIterator(self::wrapIterator($xs), $others);
    }

    /**
     * @chainable
     * @see       ImplementorInterface
     * @category  Arrays
     * @param     array            $xs
     * @param     callable|string  $f
     * @return    Iterator
     */
    public static function uniq($xs, $f = null)
    {
        $f = self::createCallback($f);
        return new Iterator\UniqueIterator(self::wrapIterator($xs), $f);
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
            $it->attachIterator(self::wrapIterator($xs));
        }
        return $it;
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
        $inner = static::wrapIterator($xs);
        if ($n >= 0) {
            $it = new \AppendIterator();
            while ($n--) {
                $it->append($inner);
            }
            return $it;
        } else {
            return new \InfiniteIterator($inner);
        }
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
        return new Iterator\DelegateIterator(function() use ($xs) {
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
        return new Iterator\DelegateIterator(function() use ($xs, $compare) {
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
        $it = new \AppendIterator();
        foreach (func_get_args() as $array) {
            $it->append(self::wrapIterator($array));
        }
        return $it;
    }

    /**
     * @chainable
     * @see       ImplementorInterface
     * @category  Utility
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
}
