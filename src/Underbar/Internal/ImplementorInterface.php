<?php
/**
 * This file is part of the Underbar.php package.
 *
 * Copyright (C) 2013 Shota Nozaki <emonkak@gmail.com>
 *
 * Licensed under the MIT License
 */

namespace Underbar\Internal;

interface ImplementorInterface
{
    /**
     * @category  Collections
     * @param     array     $xs
     * @param     callable  $f
     * @return    array|Iterator
     */
    public static function map($xs, $f);

    /**
     * @category  Collections
     * @param     array      $xs
     * @param     callable   $f
     * @return    array|Iterator
     */
    public static function filter($xs, $f);

    /**
     * @category  Arrays
     * @param     array  $xs
     * @param     int    $n
     * @return    array|Iterator
     */
    public static function firstN($xs, $n);

    /**
     * @category  Arrays
     * @param     array  $xs
     * @param     int    $n
     * @return    array|Iterator
     */
    public static function initial($xs, $n = 1, $guard = null);

    /**
     * @category  Arrays
     * @param     array  $xs
     * @param     int    $n
     * @return    array|Iterator
     */
    public static function rest($xs, $n = 1, $guard = null);

    /**
     * @category  Arrays
     * @param     array     $xs
     * @param     callable  $f
     * @return    array|Iterator
     */
    public static function takeWhile($xs, $f);

    /**
     * @category  Arrays
     * @param     array     $xs
     * @param     callable  $f
     * @return    array|Iterator
     */
    public static function dropWhile($xs, $f);

    /**
     * @varargs
     * @category  Arrays
     * @param     array  $xss
     * @return    array|Iterator
     */
    public static function unzip($xss);

    /**
     * @category  Arrays
     * @param     array    $xss
     * @param     boolean  $shallow
     * @return    array|Iterator
     */
    public static function flatten($xss, $shallow = false);

    /**
     * @category  Arrays
     * @param     int  $start
     * @param     int  $stop
     * @param     int  $step
     * @return    array|Iterator
     */
    public static function range($start, $stop = null, $step = 1);

    /**
     * @category  Arrays
     * @param     array  $array
     * @return    array|Iterator
     */
    public static function cycle($array, $n = null);

    /**
     * @category  Arrays
     * @param     mixed  $value
     * @param     int    $n
     * @return    array|Iterator
     */
    public static function repeat($value, $n = -1);

    /**
     * @category  Arrays
     * @param     mixed     $acc
     * @param     callable  $f
     * @return    array|Iterator
     */
    public static function iterate($acc, $f);

    /**
     * @varargs
     * @category  Arrays
     * @param     array  *$xss
     * @return    array|Iterator
     */
    public static function concat();
}
