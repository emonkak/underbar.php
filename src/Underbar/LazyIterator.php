<?php

namespace Underbar;

abstract class LazyIterator extends Strict
{
    /**
     * @category  Collections
     * @param     array|Traversable  $xs
     * @param     callable           $f
     * @return    Iterator
     */
    public static function map($xs, $f)
    {
        return new Internal\MapIterator(static::_wrapIterator($xs), $f);
    }

    /**
     * @category  Collections
     * @param     array|Traversable  $xs
     * @param     callable           $f
     * @return    Iterator
     */
    public static function filter($xs, $f)
    {
        return class_exists('CallbackFilterIterator', false)
             ? new \CallbackFilterIterator(static::_wrapIterator($xs), $f)
             : new Internal\FilterIterator(static::_wrapIterator($xs), $f);
    }

    /**
     * @category  Collections
     * @param     array|Traversable  $xs
     * @param     callable|string    $f
     * @param     bool               $isSorted
     * @return    Generator
     */
    public static function groupBy($xs, $f = null, $isSorted = false)
    {
        if ($isSorted) {
            $f = static::_lookupIterator($f);
            return new Internal\GroupByIterator(static::_wrapIterator($xs), $f);
        }
        return parent::groupBy($xs, $f, $isSorted);
    }

    /**
     * @category  Collections
     * @param     array|Traversable  $xs
     * @param     callable|string    $f
     * @param     bool               $isSorted
     * @return    int
     */
    public static function countBy($xs, $f = null, $isSorted = false)
    {
        if ($isSorted) {
            $f = static::_lookupIterator($f);
            return new Internal\CountByIterator(static::_wrapIterator($xs), $f);
        }
        return parent::countBy($xs, $f, $isSorted);
    }

    /**
     * @category  Arrays
     * @param     array|Traversable  $xs
     * @param     int                $n
     * @return    Iterator
     */
    protected static function _first($xs, $n = null)
    {
        return $n > 0
             ? new Internal\LimitIterator(static::_wrapIterator($xs), 0, $n)
             : new \EmptyIterator();
    }

    /**
     * @category  Arrays
     * @param     array|Traversable  $xs
     * @param     callable           $f
     * @return    Iterator
     */
    public static function takeWhile($xs, $f)
    {
        return new Internal\TakeWhileIterator(static::_wrapIterator($xs), $f);
    }

    /**
     * @category  Arrays
     * @param     array|Traversable  $xs
     * @param     int                $n
     * @return    Iterator
     */
    public static function initial($xs, $n = 1, $guard = null)
    {
        if ($guard !== null) {
            $n = 1;
        }
        return new Internal\InitialIterator(static::_wrapIterator($xs), $n);
    }

    /**
     * @category  Arrays
     * @param     array|Traversable  $xs
     * @param     int                $n
     * @return    Iterator
     */
    public static function rest($xs, $n = 1, $guard = null)
    {
        if ($guard !== null) {
            $n = 1;
        }
        return new Internal\LimitIterator(static::_wrapIterator($xs), $n);
    }

    /**
     * @category  Arrays
     * @param     array|Traversable  $xs
     * @param     callable           $f
     * @return    Iterator
     */
    public static function dropWhile($xs, $f)
    {
        return new Internal\DropWhileIterator(static::_wrapIterator($xs), $f);
    }

    /**
     * @category  Arrays
     * @param     array|Traversable  $xs
     * @param     bool               $shallow
     * @return    Iterator
     */
    public static function flatten($xs, $shallow = false)
    {
        return new Internal\FlattenIterator(static::_wrapIterator($xs), $shallow);
    }

    /**
     * @category  Arrays
     * @param     array|Traversable  *$xss
     * @return    Iterator
     */
    public static function zip()
    {
        $it = new Internal\ZipIterator();
        foreach (func_get_args() as $xs) {
            $it->attachIterator(static::_wrapIterator($xs));
        }
        return $it;
    }

    /**
     * @category  Arrays
     * @param     int       $start
     * @param     int       $stop
     * @param     int       $step
     * @return    Iterator
     */
    public static function range($start, $stop = null, $step = 1)
    {
        if ($stop === null) {
            $stop = $start;
            $start = 0;
        }
        return new Internal\RangeIterator($start, $stop, $step);
    }

    /**
     * @category  Arrays
     * @param     array|Traversable  $xs
     * @return    Iterator
     */
    public static function cycle($xs, $n = null)
    {
        $wrapped = static::_wrapIterator($xs);
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
     * @param     mixed     $value
     * @param     int       $n
     * @return    Iterator
     */
    public static function repeat($value, $n = -1)
    {
        return new Internal\RepeatIterator($value, $n);
    }

    /**
     * @category  Arrays
     * @param     mixed           $acc
     * @param     callable        $f
     * @return    array|Iterator
     * @throws    OverflowException
     */
    public static function iterate($acc, $f)
    {
        return new Internal\IterateIterator($acc, $f);
    }

    /**
     * @category  Arrays
     * @param     array|Traversable  *$xss
     * @return    Iterator
     */
    public static function concat()
    {
        $it = new \AppendIterator();
        foreach (func_get_args() as $array) {
            $it->append(static::_wrapIterator($array));
        }
        return $it;
    }
}

// __END__
// vim: expandtab softtabstop=4 shiftwidth=4
