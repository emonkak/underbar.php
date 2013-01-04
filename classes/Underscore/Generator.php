<?php

namespace Underscore;

trait Generator
{
  /**
   * Produces a new array of values by mapping each value in list through a
   * transformation function (iterator).
   *
   * Alias: collect
   *
   * @param   array|Iterator  $list
   * @param   callable        $iterator
   * @return  Iterator
   */
  public static function map($list, $iterator)
  {
    foreach ($list as $index => $value)
      yield $index => call_user_func($iterator, $value, $index, $list);
  }

  /**
   * Looks through each value in the list, returning an array of all the values
   * that pass a truth test (iterator).
   *
   * Alias: select
   *
   * @param   array|Iterator  $list
   * @param   callable        $iterator
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
   * @param   array|Iterator  $array
   * @param   int             $n
   * @return  mixed|Iterator
   */
  public static function take($array, $n = null)
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
   * @param   array|Iterator  $array
   * @param   int             $n
   * @return  array
   */
  public static function initial($array, $n = 1)
  {
    $queue = new \SplQueue();
    $result = array();

    foreach ($array as $index => $value) {
      $queue->enqueue(array($index, $value));
      if (count($queue) > $n) {
        list ($idx, $val) = $queue->dequeue();
        yield $idx => $val;
      }
    }
  }

  /**
   * Returns the rest of the elements in an array.
   *
   * Alias: tail, drop
   *
   * @param   array|Iterator  $array
   * @param   int             $index
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
   * @param   array|Iterator  *$arrays
   * @return  Iterator
   */
  public static function union()
  {
    foreach (func_get_args() as $array) {
      foreach ($array as $value)
        yield $value;
    }
  }

  /**
   * Flattens a nested array (the nesting can be to any depth).
   *
   * @param   array|Iterator  $array
   * @param   boolean         $shallow
   * @return  array
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
