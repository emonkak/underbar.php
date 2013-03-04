<?php

namespace Underbar;

abstract class LazyGenerator extends LazyGeneratorUnsafe
{
    /**
     * Produces a new array of values by mapping each value in list through a
     * transformation function (iterator).
     *
     * Alias: collect
     *
     * @category  Collections
     * @param     array|Traversable  $list
     * @param     callable           $iterator
     * @return    Iterator
     */
    public static function map($list, $iterator)
    {
        return new Internal\RewindableGenerator(parent::map($list, $iterator));
    }

    /**
     * Looks through each value in the list, returning an array of all the values
     * that pass a truth test (iterator).
     *
     * Alias: select
     *
     * @category  Collections
     * @param     array|Traversable  $list
     * @param     callable           $iterator
     * @return    Iterator
     */
    public static function filter($list, $iterator)
    {
        return new Internal\RewindableGenerator(parent::filter($list, $iterator));
    }

    /**
     * Returns the first element of an array.
     * Passing n will return the first n elements of the array.
     *
     * Alias: head, take
     *
     * @category  Arrays
     * @param     array|Traversable  $array
     * @param     int                $n
     * @return    mixed|Iterator
     */
    public static function first($array, $n = null, $guard = null)
    {
        if ($n !== null && $guard === null)
            return new Internal\RewindableGenerator(parent::_first($array, $n));
        else
            foreach ($array as $value) return $value;
    }

    /**
     * @category  Arrays
     * @param     array|Traversable  $array
     * @param     callable           $iterator
     * @return    Iterator
     */
    public static function takeWhile($array, $iterator)
    {
        return new Internal\RewindableGenerator(parent::takeWhile($array, $iterator));
    }

    /**
     * Returns everything but the last entry of the array.
     *
     * @category  Arrays
     * @param     array|Traversable  $array
     * @param     int                $n
     * @return    Iterator
     */
    public static function initial($array, $n = 1, $guard = null)
    {
        return new Internal\RewindableGenerator(parent::initial($array, $n, $guard));
    }

    /**
     * Returns the rest of the elements in an array.
     *
     * Alias: tail, drop
     *
     * @category  Arrays
     * @param     array|Traversable  $array
     * @param     int                $index
     * @return    Iterator
     */
    public static function rest($array, $n = 1, $guard = null)
    {
        return new Internal\RewindableGenerator(parent::rest($array, $n, $guard));
    }

    /**
     * @category  Arrays
     * @param     array|Traversable  $array
     * @param     callable           $iterator
     * @return    mixed|Iterator
     */
    public static function dropWhile($array, $iterator)
    {
        return new Internal\RewindableGenerator(parent::dropWhile($array, $iterator));
    }

    /**
     * Merges together the values of each of the arrays with the values at the
     * corresponding position.
     *
     * @category  Arrays
     * @param     array|Traversable  *$array
     * @return    Iterator
     */
    public static function zip()
    {
        return new Internal\RewindableGenerator(
            call_user_func_array(get_parent_class().'::zip', func_get_args())
        );
    }

    /**
     * Flattens a nested array (the nesting can be to any depth).
     *
     * @category  Arrays
     * @param     array|Traversable  $array
     * @param     boolean            $shallow
     * @return    Iterator
     */
    public static function flatten($array, $shallow = false)
    {
        return new Internal\RewindableGenerator(parent::flatten($array, $shallow));
    }

    /**
     * A function to create flexibly-numbered lists of integers,
     * handy for each and map loops.
     *
     * @category  Arrays
     * @param     int       $start
     * @param     int       $stop
     * @param     int       $step
     * @return    Iterator
     */
    public static function range($start, $stop = null, $step = 1)
    {
        return new Internal\RewindableGenerator(parent::range($start, $stop, $step));
    }

    /**
     * @category  Arrays
     * @param     array|Traversable  $array
     * @return    Iterator
     */
    public static function cycle($array, $n = null)
    {
        return new Internal\RewindableGenerator(parent::cycle($array, $n));
    }

    /**
     * @param   mixed     $value
     * @param   int       $n
     * @return  Iterator
     */
    public static function repeat($value, $n = -1)
    {
        return new Internal\RewindableGenerator(parent::repeat($value, $n));
    }

    /**
     * @category  Arrays
     * @param     mixed           $memo
     * @param     callable        $iterator
     * @return    array|Iterator
     * @throws    OverflowException
     */
    public static function iterate($memo, $iterator)
    {
        return new Internal\RewindableGenerator(parent::iterate($memo, $iterator));
    }

    /**
     * Returns a new array comprised of this array joined with other array(s)
     * and/or value(s).
     *
     * @category  Arrays
     * @param     array|Traversable  *$arrays
     * @return    Iterator
     */
    public static function concat()
    {
        return new Internal\RewindableGenerator(
            call_user_func_array(get_parent_class().'::concat', func_get_args())
        );
    }
}

// __END__
// vim: expandtab softtabstop=4 shiftwidth=4
