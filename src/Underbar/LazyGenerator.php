<?php

namespace Underbar;

abstract class LazyGenerator extends LazyUnsafeGenerator
{
    /**
     * @category  Collections
     * @param     array|Traversable  $xs
     * @param     callable           $f
     * @return    Iterator
     */
    public static function map($xs, $f)
    {
        return new Internal\RewindableGenerator(parent::map($xs, $f));
    }

    /**
     * @category  Collections
     * @param     array|Traversable  $xs
     * @param     callable           $f
     * @return    Iterator
     */
    public static function mapKey($xs, $f)
    {
        return new Internal\RewindableGenerator(parent::mapKey($xs, $f));
    }

    /**
     * @category  Collections
     * @param     array|Traversable  $xs
     * @param     callable           $f
     * @return    Iterator
     */
    public static function filter($xs, $f)
    {
        return new Internal\RewindableGenerator(parent::filter($xs, $f));
    }

    /**
     * @category  Arrays
     * @param     array|Traversable  $array
     * @param     int                $n
     * @return    mixed|Iterator
     */
    public static function first($xs, $n = null, $guard = null)
    {
        if ($n !== null && $guard === null) {
            return new Internal\RewindableGenerator(parent::_first($xs, $n));
        }
        foreach ($xs as $x) {
            return $x;
        }
    }

    /**
     * @category  Arrays
     * @param     array|Traversable  $xs
     * @param     callable           $f
     * @return    Iterator
     */
    public static function takeWhile($xs, $f)
    {
        return new Internal\RewindableGenerator(parent::takeWhile($xs, $f));
    }

    /**
     * @category  Arrays
     * @param     array|Traversable  $xs
     * @param     int                $n
     * @return    Iterator
     */
    public static function initial($xs, $n = 1, $guard = null)
    {
        return new Internal\RewindableGenerator(parent::initial($xs, $n, $guard));
    }

    /**
     * @category  Arrays
     * @param     array|Traversable  $xs
     * @param     int                $n
     * @return    Iterator
     */
    public static function rest($xs, $n = 1, $guard = null)
    {
        return new Internal\RewindableGenerator(parent::rest($xs, $n, $guard));
    }

    /**
     * @category  Arrays
     * @param     array|Traversable  $xs
     * @param     callable           $f
     * @return    mixed|Iterator
     */
    public static function dropWhile($xs, $f)
    {
        return new Internal\RewindableGenerator(parent::dropWhile($xs, $f));
    }

    /**
     * @category  Arrays
     * @param     array|Traversable  *$xss
     * @return    Iterator
     */
    public static function zip()
    {
        return new Internal\RewindableGenerator(
            call_user_func_array(get_parent_class().'::zip', func_get_args())
        );
    }

    /**
     * @category  Arrays
     * @param     array|Traversable  $xs
     * @param     boolean            $shallow
     * @return    Iterator
     */
    public static function flatten($xs, $shallow = false)
    {
        return new Internal\RewindableGenerator(parent::flatten($xs, $shallow));
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
        return new Internal\RewindableGenerator(parent::range($start, $stop, $step));
    }

    /**
     * @category  Arrays
     * @param     array|Traversable  $xs
     * @return    Iterator
     */
    public static function cycle($xs, $n = null)
    {
        return new Internal\RewindableGenerator(parent::cycle($xs, $n));
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
     * @param     mixed     $acc
     * @param     callable  $f
     * @return    Iterator
     */
    public static function iterate($acc, $f)
    {
        return new Internal\RewindableGenerator(parent::iterate($acc, $f));
    }

    /**
     * @category  Arrays
     * @param     array|Traversable  *$xss
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
