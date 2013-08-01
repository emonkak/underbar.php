<?php
/**
 * This file is part of the Underbar.php package.
 *
 * Copyright (C) 2013 Shota Nozaki <emonkak@gmail.com>
 *
 * Licensed under the MIT License
 */

namespace Underbar;

trait Enumerable
{
    abstract function getUnderbarImpl();

    abstract function value();

    public function map($f)
    {
        $impl = $this->getUnderbarImpl();
        $result = $impl::map($this->value(), $f);
        return $impl::chain($result);
    }

    public function filter($f)
    {
        $impl = $this->getUnderbarImpl();
        $result = $impl::filter($this->value(), $f);
        return $impl::chain($result);
    }

    public function sortBy($f)
    {
        $impl = $this->getUnderbarImpl();
        $result = $impl::sortBy($this->value(), $f);
        return $impl::chain($result);
    }

    public function groupBy($f = null)
    {
        $impl = $this->getUnderbarImpl();
        $result = $impl::groupBy($this->value(), $f);
        return $impl::chain($result);
    }

    public function countBy($f = null)
    {
        $impl = $this->getUnderbarImpl();
        $result = $impl::countBy($this->value(), $f);
        return $impl::chain($result);
    }

    public function shuffle()
    {
        $impl = $this->getUnderbarImpl();
        $result = $impl::shuffle($this->value());
        return $impl::chain($result);
    }

    public function memoize()
    {
        $impl = $this->getUnderbarImpl();
        $result = $impl::memoize($this->value());
        return $impl::chain($result);
    }

    public function firstN($n)
    {
        $impl = $this->getUnderbarImpl();
        $result = $impl::firstN($this->value(), $n);
        return $impl::chain($result);
    }

    public function lastN($n)
    {
        $impl = $this->getUnderbarImpl();
        $result = $impl::lastN($this->value(), $n);
        return $impl::chain($result);
    }

    public function initial($n = 1, $guard = null)
    {
        $impl = $this->getUnderbarImpl();
        $result = $impl::initial($this->value(), $n, $guard);
        return $impl::chain($result);
    }

    public function rest($n = 1, $guard = null)
    {
        $impl = $this->getUnderbarImpl();
        $result = $impl::rest($this->value(), $n, $guard);
        return $impl::chain($result);
    }

    public function takeWhile($f)
    {
        $impl = $this->getUnderbarImpl();
        $result = $impl::takeWhile($this->value(), $f);
        return $impl::chain($result);
    }

    public function dropWhile($f)
    {
        $impl = $this->getUnderbarImpl();
        $result = $impl::dropWhile($this->value(), $f);
        return $impl::chain($result);
    }

    public function flatten($shallow = false)
    {
        $impl = $this->getUnderbarImpl();
        $result = $impl::flatten($this->value(), $shallow);
        return $impl::chain($result);
    }

    public function intersection()
    {
        $impl = $this->getUnderbarImpl();
        $result = call_user_func_array(
            array($impl, 'intersection'),
            array_merge(array($this->value()), func_get_args())
        );
        return $impl::chain($result);
    }

    public function uniq($f = null)
    {
        $impl = $this->getUnderbarImpl();
        $result = $impl::uniq($this->value(), $f);
        return $impl::chain($result);
    }

    public function unzip()
    {
        $impl = $this->getUnderbarImpl();
        $result = call_user_func_array(
            array($impl, 'unzip'),
            array_merge(array($this->value()), func_get_args())
        );
        return $impl::chain($result);
    }

    public function cycle($n = -1)
    {
        $impl = $this->getUnderbarImpl();
        $result = $impl::cycle($this->value(), $n);
        return $impl::chain($result);
    }

    public function repeat($n = -1)
    {
        $impl = $this->getUnderbarImpl();
        $result = $impl::repeat($this->value(), $n);
        return $impl::chain($result);
    }

    public function iterate($f)
    {
        $impl = $this->getUnderbarImpl();
        $result = $impl::iterate($this->value(), $f);
        return $impl::chain($result);
    }

    public function reverse()
    {
        $impl = $this->getUnderbarImpl();
        $result = $impl::reverse($this->value());
        return $impl::chain($result);
    }

    public function sort($compare = null)
    {
        $impl = $this->getUnderbarImpl();
        $result = $impl::sort($this->value(), $compare);
        return $impl::chain($result);
    }

    public function concat()
    {
        $impl = $this->getUnderbarImpl();
        $result = call_user_func_array(
            array($impl, 'concat'),
            array_merge(array($this->value()), func_get_args())
        );
        return $impl::chain($result);
    }

    public function each($f)
    {
        $impl = $this->getUnderbarImpl();
        $result = $impl::each($this->value(), $f);
        return $impl::chain($result);
    }

    public function collect($f)
    {
        $impl = $this->getUnderbarImpl();
        $result = $impl::collect($this->value(), $f);
        return $impl::chain($result);
    }

    public function reduce($f, $acc)
    {
        $impl = $this->getUnderbarImpl();
        $result = $impl::reduce($this->value(), $f, $acc);
        return $result;
    }

    public function inject($f, $acc)
    {
        $impl = $this->getUnderbarImpl();
        $result = $impl::inject($this->value(), $f, $acc);
        return $result;
    }

    public function foldl($f, $acc)
    {
        $impl = $this->getUnderbarImpl();
        $result = $impl::foldl($this->value(), $f, $acc);
        return $result;
    }

    public function reduceRight($f, $acc)
    {
        $impl = $this->getUnderbarImpl();
        $result = $impl::reduceRight($this->value(), $f, $acc);
        return $result;
    }

    public function foldr($f, $acc)
    {
        $impl = $this->getUnderbarImpl();
        $result = $impl::foldr($this->value(), $f, $acc);
        return $result;
    }

    public function find($f)
    {
        $impl = $this->getUnderbarImpl();
        $result = $impl::find($this->value(), $f);
        return $result;
    }

    public function detect($f)
    {
        $impl = $this->getUnderbarImpl();
        $result = $impl::detect($this->value(), $f);
        return $result;
    }

    public function select($f)
    {
        $impl = $this->getUnderbarImpl();
        $result = $impl::select($this->value(), $f);
        return $impl::chain($result);
    }

    public function where($properties)
    {
        $impl = $this->getUnderbarImpl();
        $result = $impl::where($this->value(), $properties);
        return $impl::chain($result);
    }

    public function findWhere($properties)
    {
        $impl = $this->getUnderbarImpl();
        $result = $impl::findWhere($this->value(), $properties);
        return $result;
    }

    public function reject($f)
    {
        $impl = $this->getUnderbarImpl();
        $result = $impl::reject($this->value(), $f);
        return $impl::chain($result);
    }

    public function every($f = null)
    {
        $impl = $this->getUnderbarImpl();
        $result = $impl::every($this->value(), $f);
        return $result;
    }

    public function all($f = null)
    {
        $impl = $this->getUnderbarImpl();
        $result = $impl::all($this->value(), $f);
        return $result;
    }

    public function some($f = null)
    {
        $impl = $this->getUnderbarImpl();
        $result = $impl::some($this->value(), $f);
        return $result;
    }

    public function any($f = null)
    {
        $impl = $this->getUnderbarImpl();
        $result = $impl::any($this->value(), $f);
        return $result;
    }

    public function contains($target)
    {
        $impl = $this->getUnderbarImpl();
        $result = $impl::contains($this->value(), $target);
        return $result;
    }

    public function invoke($method)
    {
        $impl = $this->getUnderbarImpl();
        $result = call_user_func_array(
            array($impl, 'invoke'),
            array_merge(array($this->value()), func_get_args())
        );
        return $impl::chain($result);
    }

    public function pluck($property)
    {
        $impl = $this->getUnderbarImpl();
        $result = $impl::pluck($this->value(), $property);
        return $impl::chain($result);
    }

    public function max($f = null)
    {
        $impl = $this->getUnderbarImpl();
        $result = $impl::max($this->value(), $f);
        return $result;
    }

    public function min($f = null)
    {
        $impl = $this->getUnderbarImpl();
        $result = $impl::min($this->value(), $f);
        return $result;
    }

    public function sum()
    {
        $impl = $this->getUnderbarImpl();
        $result = $impl::sum($this->value());
        return $result;
    }

    public function product()
    {
        $impl = $this->getUnderbarImpl();
        $result = $impl::product($this->value());
        return $result;
    }

    public function average()
    {
        $impl = $this->getUnderbarImpl();
        $result = $impl::average($this->value());
        return $result;
    }

    public function toArray()
    {
        $impl = $this->getUnderbarImpl();
        $result = $impl::toArray($this->value());
        return $result;
    }

    public function toList()
    {
        $impl = $this->getUnderbarImpl();
        $result = $impl::toList($this->value());
        return $result;
    }

    public function size()
    {
        $impl = $this->getUnderbarImpl();
        $result = $impl::size($this->value());
        return $result;
    }

    public function first($n = null, $guard = null)
    {
        $impl = $this->getUnderbarImpl();
        $result = $impl::first($this->value(), $n, $guard);
        return $n !== null ? $impl::chain($result) : $result;
    }

    public function head($n = null, $guard = null)
    {
        $impl = $this->getUnderbarImpl();
        $result = $impl::head($this->value(), $n, $guard);
        return $impl::chain($result);
    }

    public function take($n = null, $guard = null)
    {
        $impl = $this->getUnderbarImpl();
        $result = $impl::take($this->value(), $n, $guard);
        return $impl::chain($result);
    }

    public function last($n = null, $guard = null)
    {
        $impl = $this->getUnderbarImpl();
        $result = $impl::last($this->value(), $n, $guard);
        return $n !== null ? $impl::chain($result) : $result;
    }

    public function tail($n = 1, $guard = null)
    {
        $impl = $this->getUnderbarImpl();
        $result = $impl::tail($this->value(), $n, $guard);
        return $impl::chain($result);
    }

    public function drop($n = 1, $guard = null)
    {
        $impl = $this->getUnderbarImpl();
        $result = $impl::drop($this->value(), $n, $guard);
        return $impl::chain($result);
    }

    public function compact()
    {
        $impl = $this->getUnderbarImpl();
        $result = $impl::compact($this->value());
        return $impl::chain($result);
    }

    public function without()
    {
        $impl = $this->getUnderbarImpl();
        $result = call_user_func_array(
            array($impl, 'without'),
            array_merge(array($this->value()), func_get_args())
        );
        return $impl::chain($result);
    }

    public function union()
    {
        $impl = $this->getUnderbarImpl();
        $result = call_user_func_array(
            array($impl, 'union'),
            array_merge(array($this->value()), func_get_args())
        );
        return $impl::chain($result);
    }

    public function difference()
    {
        $impl = $this->getUnderbarImpl();
        $result = call_user_func_array(
            array($impl, 'difference'),
            array_merge(array($this->value()), func_get_args())
        );
        return $impl::chain($result);
    }

    public function unique($f = null)
    {
        $impl = $this->getUnderbarImpl();
        $result = $impl::unique($this->value(), $f);
        return $impl::chain($result);
    }

    public function zip()
    {
        $impl = $this->getUnderbarImpl();
        $result = call_user_func_array(
            array($impl, 'zip'),
            array_merge(array($this->value()), func_get_args())
        );
        return $impl::chain($result);
    }

    public function zipWith()
    {
        $impl = $this->getUnderbarImpl();
        $result = call_user_func_array(
            array($impl, 'zipWith'),
            array_merge(array($this->value()), func_get_args())
        );
        return $impl::chain($result);
    }

    public function object($values = null)
    {
        $impl = $this->getUnderbarImpl();
        $result = $impl::object($this->value(), $values);
        return $impl::chain($result);
    }

    public function indexOf($value, $isSorted = 0)
    {
        $impl = $this->getUnderbarImpl();
        $result = $impl::indexOf($this->value(), $value, $isSorted);
        return $result;
    }

    public function lastIndexOf($x, $fromIndex = null)
    {
        $impl = $this->getUnderbarImpl();
        $result = $impl::lastIndexOf($this->value(), $x, $fromIndex);
        return $result;
    }

    public function sortedIndex($value, $f = null)
    {
        $impl = $this->getUnderbarImpl();
        $result = $impl::sortedIndex($this->value(), $value, $f);
        return $result;
    }

    public function join($separator = ',')
    {
        $impl = $this->getUnderbarImpl();
        $result = $impl::join($this->value(), $separator);
        return $result;
    }

    public function keys()
    {
        $impl = $this->getUnderbarImpl();
        $result = $impl::keys($this->value());
        return $impl::chain($result);
    }

    public function values()
    {
        $impl = $this->getUnderbarImpl();
        $result = $impl::values($this->value());
        return $impl::chain($result);
    }

    public function pairs()
    {
        $impl = $this->getUnderbarImpl();
        $result = $impl::pairs($this->value());
        return $impl::chain($result);
    }

    public function invert()
    {
        $impl = $this->getUnderbarImpl();
        $result = $impl::invert($this->value());
        return $impl::chain($result);
    }

    public function extend()
    {
        $impl = $this->getUnderbarImpl();
        $result = call_user_func_array(
            array($impl, 'extend'),
            array_merge(array($this->value()), func_get_args())
        );
        return $impl::chain($result);
    }

    public function pick()
    {
        $impl = $this->getUnderbarImpl();
        $result = call_user_func_array(
            array($impl, 'pick'),
            array_merge(array($this->value()), func_get_args())
        );
        return $impl::chain($result);
    }

    public function tap($interceptor)
    {
        $impl = $this->getUnderbarImpl();
        $result = $impl::tap($this->value(), $interceptor);
        return $impl::chain($result);
    }

    public function omit()
    {
        $impl = $this->getUnderbarImpl();
        $result = call_user_func_array(
            array($impl, 'omit'),
            array_merge(array($this->value()), func_get_args())
        );
        return $impl::chain($result);
    }

    public function defaults()
    {
        $impl = $this->getUnderbarImpl();
        $result = call_user_func_array(
            array($impl, 'defaults'),
            array_merge(array($this->value()), func_get_args())
        );
        return $impl::chain($result);
    }

    public function parMap($f, $n = null, $timeout = null)
    {
        $impl = $this->getUnderbarImpl();
        $result = $impl::parMap($this->value(), $f, $n, $timeout);
        return $impl::chain($result);
    }
}
