<?php

namespace Understrike;

abstract class Lazy_Generator extends Strict
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
        foreach ($list as $index => $value)
            yield $index => call_user_func($iterator, $value, $index, $list);
    }

    /**
     * @param   array|Traversable  $list
     * @param   callable           $iterator
     * @return  Iterator
     */
    public static function mapWithKey($list, $iterator)
    {
        foreach ($list as $index => $value) {
            list ($key, $val) = call_user_func($iterator, $value, $index, $list);
            yield $key => $val;
        }
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
        foreach ($list as $index => $value) {
            if (call_user_func($iterator, $value, $index, $list))
                yield $index => $value;
        }
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
            return static::_first($array, $n);
        else
            foreach ($array as $value) return $value;
    }

    private static function _first($array, $n = null)
    {
        foreach ($array as $index => $value) {
            if ($n-- > 0)
                yield $index => $value;
            else
                break;
        }
    }

    /**
     * @param   array|Traversable  $array
     * @param   callable           $iterator
     * @return  Iterator
     */
    public static function takeWhile($array, $iterator)
    {
        foreach ($array as $index => $value) {
            if (!call_user_func($iterator, $value, $index, $array)) break;
            yield $index => $value;
        }
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
        $queue = new \SplQueue();

        if ($guard !== null) $n = 1;
        foreach ($array as $value) {
            $queue->enqueue($value);
            if (count($queue) > $n) yield $queue->dequeue();
        }
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
    public static function rest($array, $n = 1, $guard = null)
    {
        if ($guard !== null) $n = 1;
        foreach ($array as $index => $value) {
            if (--$n < 0)
                yield $index => $value;
        }
    }

    /**
     * Merges together the values of each of the arrays with the values at the
     * corresponding position.
     *
     * @param   array|Traversable  *$array
     * @return  Iterator
     */
    public static function zip()
    {
        $arrays = $zipped = array();;
        $loop = false;

        foreach (func_get_args() as $array) {
            $arrays[] = $wrapped = static::_wrapIterator($array);
            $wrapped->rewind();
            $loop = $loop || $wrapped->valid();
            $zipped[] = $wrapped->current();
        }

        while ($loop) {
            yield $zipped;
            $zipped = array();
            $loop = false;
            foreach ($arrays as $array) {
                $array->next();
                $zipped[] = $array->current();
                $loop = $loop || $array->valid();
            }
        }
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
        foreach ($array as $key => $value) {
            if (is_array($value) || $value instanceof \Traversable) {
                if ($shallow) {
                    foreach ($value as $childKey => $childValue)
                        yield $childKey => $childValue;
                } else {
                    foreach (static::flatten($value, $shallow) as $childKey => $childValue)
                        yield $childKey => $childValue;
                }
            } else {
                yield $key => $value;
            }
        }
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

        $len = max(ceil(($stop - $start) / $step), 0);
        for ($i = 0; $i < $len; $i++) {
            yield $start;
            $start += $step;
        }
    }

    /**
     * @param   array|Traversable  $array
     * @return  Iterator
     */
    public static function cycle($array, $n = null)
    {
        if ($n !== null) {
            while (true) foreach ($array as $value) yield $value;
        } else {
            while ($n-- > 0) foreach ($array as $value) yield $value;
        }
    }

    /**
     * Returns a new array comprised of this array joined with other array(s)
     * and/or value(s).
     *
     * @param   array|Traversable  *$arrays
     * @return  array
     */
    public static function concat()
    {
        foreach (func_get_args() as $array) {
            foreach ($array as $key => $value)
                yield $key => $value;
        }
    }
}

// __END__
// vim: expandtab softtabstop=4 shiftwidth=4
