<?php

namespace Underbar;

class LazySafeGenerator extends LazyGenerator
{
    /**
     * @category  Collections
     * @param     array|Traversable  $xs
     * @param     callable           $f
     * @return    Iterator\RewindableGenerator
     */
    public static function map($xs, $f)
    {
        return new Iterator\RewindableGenerator(
            get_parent_class().'::'.__FUNCTION__,
            array($xs, $f)
        );
    }

    /**
     * @category  Collections
     * @param     array|Traversable  $xs
     * @param     callable           $f
     * @return    Iterator\RewindableGenerator
     */
    public static function filter($xs, $f)
    {
        return new Iterator\RewindableGenerator(
            get_parent_class().'::'.__FUNCTION__,
            array($xs, $f)
        );
    }

    /**
     * @category  Collections
     * @param     array|Traversable  $xs
     * @param     callable           $f
     * @param     mixed              $acc
     * @return    Iterator
     */
    public static function scanl($xs, $f, $acc)
    {
        return new Iterator\RewindableGenerator(
            get_parent_class().'::'.__FUNCTION__,
            array($xs, $f, $acc)
        );
    }

    /**
     * @category  Collections
     * @param     array|Traversable  $xs
     * @param     callable|string    $f
     * @param     bool               $isSorted
     * @return    Iterator\RewindableGenerator
     */
    public static function _groupBy($xs, $f = null)
    {
        return new Iterator\RewindableGenerator(
            get_parent_class().'::'.__FUNCTION__,
            array($xs, $f)
        );
    }

    /**
     * @category  Collections
     * @param     array|Traversable  $xs
     * @param     callable|string    $f
     * @param     bool               $isSorted
     * @return    Iterator\RewindableGenerator
     */
    public static function _countBy($xs, $f = null)
    {
        return new Iterator\RewindableGenerator(
            get_parent_class().'::'.__FUNCTION__,
            array($xs, $f)
        );
    }

    /**
     * @category  Arrays
     * @param     array|Traversable        $array
     * @param     int                      $n
     * @return    Iterator\RewindableGenerator
     */
    public static function _first($xs, $n = null)
    {
        return new Iterator\RewindableGenerator(
            get_parent_class().'::'.__FUNCTION__,
            array($xs, $n)
        );
    }

    /**
     * @category  Arrays
     * @param     array|Traversable  $xs
     * @param     callable           $f
     * @return    Iterator\RewindableGenerator
     */
    public static function takeWhile($xs, $f)
    {
        return new Iterator\RewindableGenerator(
            get_parent_class().'::'.__FUNCTION__,
            array($xs, $f)
        );
    }

    /**
     * @category  Arrays
     * @param     array|Traversable  $xs
     * @param     int                $n
     * @return    Iterator\RewindableGenerator
     */
    public static function initial($xs, $n = 1, $guard = null)
    {
        return new Iterator\RewindableGenerator(
            get_parent_class().'::'.__FUNCTION__,
            array($xs, $n, $guard)
        );
    }

    /**
     * @category  Arrays
     * @param     array|Traversable  $xs
     * @param     int                $n
     * @return    Iterator\RewindableGenerator
     */
    public static function rest($xs, $n = 1, $guard = null)
    {
        return new Iterator\RewindableGenerator(
            get_parent_class().'::'.__FUNCTION__,
            array($xs, $n, $guard)
        );
    }

    /**
     * @category  Arrays
     * @param     array|Traversable  $xs
     * @param     callable           $f
     * @return    Iterator\RewindableGenerator
     */
    public static function dropWhile($xs, $f)
    {
        return new Iterator\RewindableGenerator(
            get_parent_class().'::'.__FUNCTION__,
            array($xs, $f)
        );
    }

    /**
     * @category  Arrays
     * @param     array|Traversable  *$xss
     * @return    Iterator\RewindableGenerator
     */
    public static function zip()
    {
        return new Iterator\RewindableGenerator(
            get_parent_class().'::'.__FUNCTION__,
            func_get_args()
        );
    }

    /**
     * @category  Arrays
     * @param     array|Traversable  $xs
     * @param     bool               $shallow
     * @return    Iterator\RewindableGenerator
     */
    public static function flatten($xs, $shallow = false)
    {
        return new Iterator\RewindableGenerator(
            get_parent_class().'::'.__FUNCTION__,
            array($xs, $shallow)
        );
    }

    /**
     * @category  Arrays
     * @param     int                $start
     * @param     int                $stop
     * @param     int                $step
     * @return    Iterator\RewindableGenerator
     */
    public static function range($start, $stop = null, $step = 1)
    {
        return new Iterator\RewindableGenerator(
            get_parent_class().'::'.__FUNCTION__,
            array($start, $stop, $step)
        );
    }

    /**
     * @category  Arrays
     * @param     array|Traversable  $xs
     * @return    Iterator\RewindableGenerator
     */
    public static function cycle($xs, $n = null)
    {
        return new Iterator\RewindableGenerator(
            get_parent_class().'::'.__FUNCTION__,
            array($xs, $n)
        );
    }

    /**
     * @param   mixed     $value
     * @param   int       $n
     * @return  Iterator\RewindableGenerator
     */
    public static function repeat($value, $n = -1)
    {
        return new Iterator\RewindableGenerator(
            get_parent_class().'::'.__FUNCTION__,
            array($value, $n)
        );
    }

    /**
     * @category  Arrays
     * @param     mixed              $acc
     * @param     callable           $f
     * @return    Iterator\RewindableGenerator
     */
    public static function iterate($f, $acc)
    {
        return new Iterator\RewindableGenerator(
            get_parent_class().'::'.__FUNCTION__,
            array($f, $acc)
        );
    }

    /**
     * @category  Arrays
     * @param     callable  $f
     * @param     mixed     $acc
     * @return    Iterator\RewindableGenerator
     */
    public static function unfoldr($f, $acc)
    {
        return new Iterator\RewindableGenerator(
            get_parent_class().'::'.__FUNCTION__,
            array($f, $acc)
        );
    }

    /**
     * @category  Arrays
     * @param     array|Traversable  *$xss
     * @return    Iterator\RewindableGenerator
     */
    public static function concat()
    {
        return new Iterator\RewindableGenerator(
            get_parent_class().'::'.__FUNCTION__,
            func_get_args()
        );
    }
}

// __END__
// vim: expandtab softtabstop=4 shiftwidth=4
