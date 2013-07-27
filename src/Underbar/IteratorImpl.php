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
     * @see       ImplementorInterface
     * @category  Collections
     * @param     array     $xs
     * @param     callable  $f
     * @return    Iterator
     */
    public static function filter($xs, $f)
    {
        return class_exists('CallbackFilterIterator', false)
             ? new \CallbackFilterIterator(static::wrapIterator($xs), $f)
             : new Iterator\FilterIterator(static::wrapIterator($xs), $f);
    }

    /**
     * @see       ImplementorInterface
     * @category  Arrays
     * @param     array  $xs
     * @param     int    $n
     * @return    Iterator
     */
    public static function firstN($xs, $n = null)
    {
        return $n > 0
             ? new Iterator\LimitIterator(static::wrapIterator($xs), 0, $n)
             : new \EmptyIterator();
    }

    /**
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
        return new Iterator\LimitIterator(static::wrapIterator($xs), $n);
    }

    /**
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
     * @see       ImplementorInterface
     * @category  Arrays
     * @param     array    $xs
     * @param     boolean  $shallow
     * @return    Iterator
     */
    public static function flatten($xs, $shallow = false)
    {
        return new Iterator\FlattenIterator(static::wrapIterator($xs), $shallow);
    }

    /**
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
     * @see       ImplementorInterface
     * @category  Arrays
     * @param     array  $xs
     * @return    Iterator
     */
    public static function cycle($xs, $n = null)
    {
        $wrapped = static::wrapIterator($xs);
        if ($n === null) {
            return new \InfiniteIterator($wrapped);
        }
        $it = new \AppendIterator();
        while ($n-- > 0) {
            $it->append($wrapped);
        }
        return $it;
    }

    /**
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
            $it->append(static::wrapIterator($array));
        }
        return $it;
    }
}
