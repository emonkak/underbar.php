<?php

namespace Understrike;

abstract class Lazy_Iterator extends Strict
{
    /**
     * Produces a new array of values by mapping each value in list through a
     * transformation function (iterator).
     *
     * Alias: collect
     *
     * @param   array|Traversable  $list
     * @param   callable           $iterator
     * @return  Iterator
     */
    public static function map($list, $iterator)
    {
        return new Internal\MapIterator(static::_wrapIterator($list), $iterator);
    }

    /**
     * Looks through each value in the list, returning an array of all the values
     * that pass a truth test (iterator).
     *
     * Alias: select
     *
     * @param   array|Traversable  $list
     * @param   callable           $iterator
     * @return  Iterator
     */
    public static function filter($list, $iterator)
    {
        return class_exists('CallbackFilterIterator', false)
            ? new \CallbackFilterIterator(static::_wrapIterator($list), $iterator)
            : new Internal\FilterIterator(static::_wrapIterator($list), $iterator);
    }

    /**
     * Returns the first element of an array.
     * Passing n will return the first n elements of the array.
     *
     * Alias: head, take
     *
     * @param   array|Traversable  $array
     * @param   int                $n
     * @return  mixed|Iterator
     */
    public static function first($array, $n = null, $guard = null)
    {
        if ($n !== null && $guard === null)
            return $n > 0
                ? new \LimitIterator(static::_wrapIterator($array), 0, $n)
                : new \EmptyIterator();
        else
            foreach ($array as $value) return $value;
    }

    /**
     * @param   array|Traversable  $array
     * @param   callable           $iterator
     * @return  Iterator
     */
    public static function takeWhile($array, $iterator)
    {
        return new Internal\TakeWhileIterator(
            static::_wrapIterator($array),
            $iterator
        );
    }

    /**
     * Returns everything but the last entry of the array.
     *
     * @param   array|Traversable  $array
     * @param   int                $n
     * @return  Iterator
     */
    public static function initial($array, $n = 1, $guard = null)
    {
        if ($guard !== null) $n = 1;
        return new Internal\InitialIterator(static::_wrapIterator($array), $n);
    }

    /**
     * Returns the rest of the elements in an array.
     *
     * Alias: tail, drop
     *
     * @param   array|Traversable  $array
     * @param   int                $index
     * @return  Iterator
     */
    public static function rest($array, $index = 1, $guard = null)
    {
        if ($guard !== null) $index = 1;
        return new \LimitIterator(static::_wrapIterator($array), $index);
    }

    /**
     * @param   array|Traversable  $array
     * @param   callable           $iterator
     * @return  Iterator
     */
    public static function dropWhile($array, $iterator)
    {
        return new Internal\DropWhileIterator(
            static::_wrapIterator($array),
            $iterator
        );
    }

    /**
     * Flattens a nested array (the nesting can be to any depth).
     *
     * @param   array|Traversable  $array
     * @param   boolean            $shallow
     * @return  Iterator
     */
    public static function flatten($array, $shallow = false)
    {
        return new Internal\FlattenIterator(static::_wrapIterator($array), $shallow);
    }

    /**
     * Merges together the values of each of the arrays with the values at the
     * corresponding position.
     *
     * @param   array|Traversable  *$arrays
     * @return  Iterator
     */
    public static function zip()
    {
        $it = new \MultipleIterator(\MultipleIterator::MIT_NEED_ANY);
        foreach (func_get_args() as $array)
            $it->attachIterator(static::_wrapIterator($array));
        return $it;
    }

    /**
     * A function to create flexibly-numbered lists of integers,
     * handy for each and map loops.
     *
     * @param   int       $start
     * @param   int       $stop
     * @param   int       $step
     * @return  Iterator
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
     * @param   array|Traversable  $array
     * @return  Iterator
     */
    public static function cycle($array, $n = null)
    {
        $wrapped = static::_wrapIterator($array);
        if ($n !== null) {
            $it = new \AppendIterator();
            while ($n-- > 0) $it->append($wrapped);
            return $it;
        } else {
            return new \InfiniteIterator($wrapped);
        }
    }

    /**
     * Returns a new array comprised of this array joined with other array(s)
     * and/or value(s).
     *
     * @param   array|Traversable  *$arrays
     * @return  Iterator
     */
    public static function concat()
    {
        $it = new \AppendIterator();
        foreach (func_get_args() as $array)
            $it->append(static::_wrapIterator($array));
        return $it;
    }

    /**
     * @param   mixed     $value
     * @return  Iterator
     */
    public static function repeat($value)
    {
        return new Internal\RepeatIterator($value);
    }
}

// __END__
// vim: expandtab softtabstop=4 shiftwidth=4