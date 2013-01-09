<?php

namespace Underscore\Lazy;

abstract class IteratorFunction extends \Underscore\Strict
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
    return new MapIterator(static::_wrapIterator($list), $iterator);
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
    return class_exists('CallbackFilterIterator')
         ? new \CallbackFilterIterator(static::_wrapIterator($list), $iterator)
         : new FilterIterator(static::_wrapIterator($list), $iterator);
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
    if (is_int($n) && $guard === null)
      return new \LimitIterator(static::_wrapIterator($array), 0, $n);
    else
      foreach ($array as $value) return $value;
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
    return new InitialIterator(static::_wrapIterator($array), $n);
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
   * Flattens a nested array (the nesting can be to any depth).
   *
   * @param   array|Traversable  $array
   * @param   boolean            $shallow
   * @return  Iterator
   */
  public static function flatten($array, $shallow = false)
  {
    return new FlattenIterator(static::_wrapIterator($array), $shallow);;
  }

  /**
   * Computes the union of the passed-in arrays: the list of unique items,
   * in order, that are present in one or more of the arrays.
   *
   * @param   array|Traversable  *$arrays
   * @return  Iterator
   */
  public static function union()
  {
    $arrays = array_map(get_called_class().'::_wrapIterator', func_get_args());
    return new UnionIterator($arrays);
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
    $arrays = array_map(get_called_class().'::_wrapIterator', func_get_args());
    return new ZipIterator($arrays);
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
    return new RangeIterator($start, $stop, $step);
  }

  protected static function _mapWithKey($list, $iterator)
  {
    return new MapWithKeyIterator(static::_wrapIterator($list), $iterator);
  }
}

// __END__
// vim: expandtab softtabstop=2 shiftwidth=2
