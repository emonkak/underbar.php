<?php
/**
 * This file is part of the Underbar.php package.
 *
 * Copyright (C) 2013 Shota Nozaki <emonkak@gmail.com>
 *
 * Licensed under the MIT License
 */

namespace Underbar;

class LazySafeGenerator extends LazyGenerator
{
    /**
     * @category  Collections
     * @param     array     $xs
     * @param     callable  $f
     * @return    RewindableGenerator
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
     * @param     array     $xs
     * @param     callable  $f
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
     * @param     array     $xs
     * @param     callable  $f
     * @param     mixed     $acc
     * @return    RewindableGenerator
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
     * @param     array            $xs
     * @param     callable|string  $f
     * @param     bool             $isSorted
     * @return    RewindableGenerator
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
     * @param     array            $xs
     * @param     callable|string  $f
     * @param     bool             $isSorted
     * @return    RewindableGenerator
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
     * @param     array  $array
     * @param     int    $n
     * @return    RewindableGenerator
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
     * @param     array     $xs
     * @param     callable  $f
     * @return    RewindableGenerator
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
     * @param     array  $xs
     * @param     int    $n
     * @return    RewindableGenerator
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
     * @param     array  $xs
     * @param     int    $n
     * @return    RewindableGenerator
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
     * @param     array     $xs
     * @param     callable  $f
     * @return    RewindableGenerator
     */
    public static function dropWhile($xs, $f)
    {
        return new Iterator\RewindableGenerator(
            get_parent_class().'::'.__FUNCTION__,
            array($xs, $f)
        );
    }

    /**
     * @varargs
     * @category  Arrays
     * @param     array  $xss
     * @return    RewindableGenerator
     */
    public static function unzip($xss)
    {
        return new Iterator\RewindableGenerator(
            get_parent_class().'::'.__FUNCTION__,
            func_get_args()
        );
    }

    /**
     * @category  Arrays
     * @param     array  $xs
     * @param     bool   $shallow
     * @return    RewindableGenerator
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
     * @param     int  $start
     * @param     int  $stop
     * @param     int  $step
     * @return    RewindableGenerator
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
     * @param     array  $xs
     * @return    RewindableGenerator
     */
    public static function cycle($xs, $n = null)
    {
        return new Iterator\RewindableGenerator(
            get_parent_class().'::'.__FUNCTION__,
            array($xs, $n)
        );
    }

    /**
     * @category  Arrays
     * @param     mixed  $value
     * @param     int    $n
     * @return    RewindableGenerator
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
     * @param     mixed     $acc
     * @param     callable  $f
     * @return    RewindableGenerator
     */
    public static function iterate($acc, $f)
    {
        return new Iterator\RewindableGenerator(
            get_parent_class().'::'.__FUNCTION__,
            array($acc, $f)
        );
    }

    /**
     * @category  Arrays
     * @param     callable  $f
     * @param     mixed     $acc
     * @return    RewindableGenerator
     */
    public static function unfoldr($f, $acc)
    {
        return new Iterator\RewindableGenerator(
            get_parent_class().'::'.__FUNCTION__,
            array($f, $acc)
        );
    }

    /**
     * @varargs
     * @category  Arrays
     * @param     array   *$xss
     * @return    RewindableGenerator
     */
    public static function concat()
    {
        return new Iterator\RewindableGenerator(
            get_parent_class().'::'.__FUNCTION__,
            func_get_args()
        );
    }
}
