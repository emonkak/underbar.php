<?php

namespace Underscore\Lazy;

abstract class GeneratorFunction extends \Underscore\_
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
   * Produces a new array of values by mapping each value in list through a
   * transformation function (iterator).
   *
   * Alias: collectWithKey
   *
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
  public static function first($array, $n = null)
  {
    if (is_int($n))
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
   * Returns everything but the last entry of the array.
   *
   * @param   array|Traversable  $array
   * @param   int                $n
   * @return  Iterator
   */
  public static function initial($array, $n = 1)
  {
    $queue = new \SplQueue();

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
  public static function rest($array, $n = 1)
  {
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
    $arrays = array_map(get_called_class().'::_wrapIterator', func_get_args());
    foreach ($arrays as $array) $array->rewind();

    do {
      $available = false;
      $zipped = array();

      foreach ($arrays as $array) {
        if ($array->valid()) {
          $available = true;
          $zipped[] = $array->current();
          $array->next();
        } else {
          $zipped[] = null;
        }
      }

      yield $zipped;
    } while ($available);
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
  public static function range($start, $stop = PHP_INT_MAX, $step = 1)
  {
    if ($stop === null) {
      $stop = $start;
      $start = 0;
    }
    if ($start < $stop) {
      do {
        yield $start;
        $start += $step;
      } while ($start <= $stop);
    } else {
      do {
        yield $start;
        $start += $step;
      } while ($start >= $stop);
    }
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
    foreach (func_get_args() as $array) {
      foreach ($array as $key => $value)
        yield $key => $value;
    }
  }

  /**
   * Flattens a nested array (the nesting can be to any depth).
   *
   * @param   array|Traversable  $array
   * @param   boolean         $shallow
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
}

// __END__
// vim: expandtab softtabstop=2 shiftwidth=2
