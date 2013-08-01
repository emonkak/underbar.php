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
     * @chainable
     * @category  Collections
     * @param     array     $xs
     * @param     callable  $f
     * @return    array|Iterator
     */
    public static function map($xs, $f);

    /**
     * @chainable
     * @category  Collections
     * @param     array      $xs
     * @param     callable   $f
     * @return    array|Iterator
     */
    public static function filter($xs, $f);

    /**
     * @chainable
     * @category  Collections
     * @param     array            $xs
     * @param     callable|string  $f
     * @return    array
     */
    public static function sortBy($xs, $f);

    /**
     * @chainable
     * @category  Collections
     * @param     array            $xs
     * @param     callable|string  $f
     * @return    array
     */
    public static function groupBy($xs, $f = null);

    /**
     * @chainable
     * @category  Collections
     * @param     array            $xs
     * @param     callable|string  $f
     * @return    array
     */
    public static function countBy($xs, $f = null);

    /**
     * @chainable
     * @category  Collections
     * @param     array  $xs
     * @return    array
     */
    public static function shuffle($xs);

    /**
     * @chainable
     * @category  Collections
     * @param     Iterator  $xs
     * @return    Iterator
     */
    public static function memoize($xs);

    /**
     * @chainable
     * @category  Arrays
     * @param     array  $xs
     * @param     int    $n
     * @return    array|Iterator
     */
    public static function firstN($xs, $n);

    /**
     * @chainable
     * @category  Arrays
     * @param     array  $xs
     * @param     int    $n
     * @return    array|Iterator
     */
    public static function lastN($xs, $n);

    /**
     * @chainable
     * @category  Arrays
     * @param     array  $xs
     * @param     int    $n
     * @return    array|Iterator
     */
    public static function initial($xs, $n = 1, $guard = null);

    /**
     * @chainable
     * @category  Arrays
     * @param     array  $xs
     * @param     int    $n
     * @return    array|Iterator
     */
    public static function rest($xs, $n = 1, $guard = null);

    /**
     * @chainable
     * @category  Arrays
     * @param     array     $xs
     * @param     callable  $f
     * @return    array|Iterator
     */
    public static function takeWhile($xs, $f);

    /**
     * @chainable
     * @category  Arrays
     * @param     array     $xs
     * @param     callable  $f
     * @return    array|Iterator
     */
    public static function dropWhile($xs, $f);

    /**
     * @chainable
     * @varargs
     * @category  Arrays
     * @param     array  $xss
     * @return    array|Iterator
     */
    public static function unzip($xss);

    /**
     * @chainable
     * @category  Arrays
     * @param     array    $xss
     * @param     boolean  $shallow
     * @return    array|Iterator
     */
    public static function flatten($xss, $shallow = false);

    /**
     * @chainable
     * @varargs
     * @category  Arrays
     * @param     array  $xs
     * @param     array  *$others
     * @return    array|Iterator
     */
    public static function intersection($xs);

    /**
     * Alias: unique
     *
     * @chainable
     * @category  Arrays
     * @param     array            $xs
     * @param     callable|string  $f
     * @return    array|Iterator
     */
    public static function uniq($xs, $f = null);

    /**
     * @chainable
     * @category  Arrays
     * @param     int  $start
     * @param     int  $stop
     * @param     int  $step
     * @return    array|Iterator
     */
    public static function range($start, $stop = null, $step = 1);

    /**
     * @chainable
     * @category  Arrays
     * @param     array  $array
     * @return    array|Iterator
     */
    public static function cycle($array, $n = null);

    /**
     * @chainable
     * @category  Arrays
     * @param     mixed  $value
     * @param     int    $n
     * @return    array|Iterator
     */
    public static function repeat($value, $n = -1);

    /**
     * @chainable
     * @category  Arrays
     * @param     mixed     $acc
     * @param     callable  $f
     * @return    array|Iterator
     */
    public static function iterate($acc, $f);

    /**
     * @chainable
     * @category  Arrays
     * @param     array  $xs
     * @return    array|Iterator
     */
    public static function reverse($xs);

    /**
     * @chainable
     * @category  Arrays
     * @param     array     $xs
     * @param     callable  $compare
     * @return    array|Iterator
     */
    public static function sort($xs, $compare = null);

    /**
     * @chainable
     * @varargs
     * @category  Arrays
     * @param     array  *$xss
     * @return    array|Iterator
     */
    public static function concat();
}
