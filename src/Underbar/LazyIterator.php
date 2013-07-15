<?php
/**
 * This file is part of the Underbar.php package.
 *
 * Copyright (C) 2013 Shota Nozaki <emonkak@gmail.com>
 *
 * Licensed under the MIT License
 */

namespace Underbar;

class LazyIterator extends Strict
{
    /**
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
     * @category  Collections
     * @param     array     $xs
     * @param     callable  $f
     * @param     mixed     $acc
     * @return    Iterator
     */
    public static function scanl($xs, $f, $acc)
    {
        return new Iterator\ScanLeftIterator(static::wrapIterator($xs), $f, $acc);
    }

    /**
     * @category  Collections
     * @param     array            $xs
     * @param     callable|string  $f
     * @param     bool             $isSorted
     * @return    Generator
     */
    public static function groupBy($xs, $f = null, $isSorted = false)
    {
        if ($isSorted) {
            $f = static::lookupIterator($f);
            return new Iterator\GroupByIterator(static::wrapIterator($xs), $f);
        }
        return parent::groupBy($xs, $f, $isSorted);
    }

    /**
     * @category  Collections
     * @param     array            $xs
     * @param     callable|string  $f
     * @param     bool             $isSorted
     * @return    int
     */
    public static function countBy($xs, $f = null, $isSorted = false)
    {
        if ($isSorted) {
            $f = static::lookupIterator($f);
            return new Iterator\CountByIterator(static::wrapIterator($xs), $f);
        }
        return parent::countBy($xs, $f, $isSorted);
    }

    /**
     * @category  Arrays
     * @param     array  $xs
     * @param     int    $n
     * @return    Iterator
     */
    public static function _first($xs, $n = null)
    {
        return $n > 0
             ? new Iterator\LimitIterator(static::wrapIterator($xs), 0, $n)
             : new \EmptyIterator();
    }

    /**
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
     * @category  Arrays
     * @param     array  $xs
     * @param     bool   $shallow
     * @return    Iterator
     */
    public static function flatten($xs, $shallow = false)
    {
        return new Iterator\FlattenIterator(static::wrapIterator($xs), $shallow);
    }

    /**
     * @varargs
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
     * @category  Arrays
     * @param     mixed     $acc
     * @param     callable  $f
     * @return    Iterator
     * @throws    OverflowException
     */
    public static function iterate($acc, $f)
    {
        return new Iterator\IterateIterator($acc, $f);
    }

    /**
     * @category  Collections
     * @param     callable  $f
     * @param     mixed     $acc
     * @return    Iterator
     */
    public static function unfoldr($f, $acc)
    {
        return new Iterator\UnfoldRightIterator($f, $acc);
    }

    /**
     * @varargs
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
