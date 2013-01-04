<?php

namespace Underscore;

abstract class Underscore
{
  /**
   * Iterates over a list of elements, yielding each in turn to an iterator
   * function.
   *
   * @param   array|Iterator  $list
   * @param   callable        $iterator
   * @return  void
   */
  public static function each($list, $iterator)
  {
    foreach ($list as $index => $value)
      call_user_func($iterator, $value, $index, $list);
  }

  private static function _wrapArray($list)
  {
    return is_array($list) ? new \ArrayObject($list) : $list;
  }

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
    return new MapIterator(static::_wrapArray($list), $iterator);
  }

  public static function collect($list, $iterator)
  {
    return static::map($list, $iterator);
  }

  /**
   * Also known as inject and foldl, reduce boils down a list of values into a
   * single value.
   *
   * Alias: inject, foldl
   *
   * @param   array|Iterator  $list
   * @param   callable        $iterator
   * @param   mixed           $memo
   * @return  mixed
   */
  public static function reduce($list, $iterator, $memo)
  {
    foreach ($list as $index => $value)
      $memo = call_user_func($iterator, $memo, $value, $index, $list);

    return $memo;
  }

  public static function inject($list, $iterator, $memo)
  {
    return static::reduce($list, $iterator, $memo);
  }

  public static function foldl($list, $iterator, $memo)
  {
    return static::reduce($list, $iterator, $memo);
  }

  /**
   * The right-associative version of reduce.
   *
   * Alias: foldr
   *
   * @param   array|Iterator  $list
   * @param   callable        $iterator
   * @param   mixed           $memo
   * @return  mixed
   */
  public static function reduceRight($list, $iterator, $memo)
  {
    foreach (static::reverse($list) as $index => $value)
      $memo = call_user_func($iterator, $memo, $value, $index, $list);

    return $memo;
  }

  public static function foldr($list, $iterator, $memo)
  {
    return static::reduceRight($list, $iterator, $memo);
  }

  /**
   * Looks through each value in the list, returning the first one that passes a
   * truth test (iterator).
   *
   * Alias: detect
   *
   * @param   array|Iterator  $list
   * @param   callable        $iterator
   * @return  mixed
   */
  public static function find($list, $iterator)
  {
    foreach ($list as $index => $value) {
      if (call_user_func($iterator, $value, $index, $list))
        return $value;
    }
  }

  public static function detect($list, $iterator)
  {
    return static::find($list, $iterator);
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
    return class_exists('CallbackFilterIterator')
         ? new \CallbackFilterIterator(static::_wrapArray($list), $iterator)
         : new FilterIterator(static::_wrapArray($list), $iterator);
  }

  public static function select($list, $iterator)
  {
    return static::filter($list, $iterator);
  }

  /**
   * Looks through each value in the list, returning an array of all the values
   * that contain all of the key-value pairs listed in properties.
   *
   * @param   array|Iterator  $list
   * @param   array           $properties
   * @return  boolean
   */
  public static function where($list, array $properties)
  {
    return static::filter($list, function($value) use ($properties) {
      foreach ($properties as $propKey => $propValue) {
        if (!((isset($value->$propKey) && $value->$propKey === $propValue)
              || (isset($value[$propKey]) && $value[$propKey] === $propValue)))
          return false;
      }
      return true;
    });
  }

  /**
   * Returns the values in list without the elements that the truth test
   * (iterator) passes. The opposite of filter.
   *
   * @param   array|Iterator  $list
   * @param   callable        $iterator
   * @return  Iterator
   */
  public static function reject($list, $iterator)
  {
    return static::filter($list, function($value, $index, $list) use ($iterator) {
      return !call_user_func($iterator, $value, $index, $list);
    });
  }

  /**
   * Returns true if all of the values in the list pass the iterator truth test.
   *
   * Alias: all
   *
   * @param   array|Iterator  $list
   * @param   callable        $iterator
   * @return  boolean
   */
  public static function every($list, $iterator = null)
  {
    $result = true;

    foreach ($list as $index => $value) {
      if (!($result = $iterator
                    ? call_user_func($iterator, $value, $index, $list)
                    : $value))
        break;
    }

    return !!$result;
  }

  public static function all($list, $iterator = null)
  {
    return static::every($list, $iterator);
  }

  /**
   * Returns true if any of the values in the list pass the iterator truth test.
   *
   * Alias: any
   *
   * @param   array|Iterator  $list
   * @param   callable        $iterator
   * @return  boolean
   */
  public static function some($list, $iterator = null)
  {
    $result = true;

    foreach ($list as $index => $value) {
      if ($result = $iterator
                  ? call_user_func($iterator, $value, $index, $list)
                  : $value)
        break;
    }

    return !!$result;
  }

  public static function any($list, $iterator = null)
  {
    return static::any($list, $iterator);
  }

  /**
   * Returns true if the value is present in the list.
   *
   * Alias: include
   *
   * @param   array|Iterator  $list
   * @param   mixed           $target
   * @return  boolean
   */
  public static function contains($list, $target)
  {
    foreach ($list as $value) {
      if ($value === $target)
        return true;
    }

    return false;
  }

  /**
   * Calls the method named by methodName on each value in the list.
   *
   * @param   array|Iterator  $list
   * @param   string          $methodName
   * @return  Iterator
   */
  public static function invoke($list, $methodName)
  {
    $args = array_slice(func_get_args(), 2);

    return static::map($list, function($value) use ($methodName, $args) {
      return call_user_func_array(array($value, $methodName), $args);
    });
  }

  /**
   * A convenient version of what is perhaps the most common use-case for map:
   * extracting a list of property values.
   *
   * @param   array|Iterator  $list
   * @param   string          $propertyName
   * @return  Iterator
   */
  public static function pluck($list, $propertyName)
  {
    return static::map($list, function($value) use ($propertyName) {
      if (isset($value[$propertyName]))
        return $value[$propertyName];
      elseif (isset($value->$propertyName))
        return $value->$propertyName;
      else
        return null;
    });
  }

  private static function _lookupIterator($value)
  {
    if (is_callable($value))
      return $value;
    elseif (is_scalar($value))
      return function($obj) use ($value) {
        return is_array($obj) ? $obj[$value] : $obj->$value;
      };
    else
      return __CLASS__.'::identity';
  }

  /**
   * Returns the maximum value in list. If iterator is passed, it will be used
   * on each value to generate the criterion by which the value is ranked.
   *
   * @param   array|Iterator  $list
   * @param   callable        $iterator
   * @return  mixed
   */
  public static function max($list, $iterator = null)
  {
    $iterator = static::_lookupIterator($iterator);
    $result = array('computed' => -PHP_INT_MAX, 'value' => -PHP_INT_MAX);

    foreach ($list as $index => $value) {
      $computed = call_user_func($iterator, $value, $index, $list);
      if ($computed > $result['computed']) {
        $result['computed'] = $computed;
        $result['value'] = $value;
      }
    }

    return $result['value'];
  }

  /**
   * Returns the minimum value in list. If iterator is passed, it will be used
   * on each value to generate the criterion by which the value is ranked.
   *
   * @param   array|Iterator  $list
   * @param   callable        $iterator
   * @return  mixed
   */
  public static function min($list, $iterator = null)
  {
    $iterator = static::_lookupIterator($iterator);
    $result = array('computed' => PHP_INT_MAX, 'value' => PHP_INT_MAX);

    foreach ($list as $index => $value) {
      $computed = call_user_func($iterator, $value, $index, $list);
      if ($computed < $result['computed']) {
        $result['computed'] = $computed;
        $result['value'] = $value;
      }
    }

    return $result['value'];
  }

  /**
   * Returns a sorted copy of list, ranked in ascending order by the results of
   * running each value through iterator.
   *
   * @param   array|Iterator   $list
   * @param   callable|string  $value
   * @return  Iterator
   */
  public static function sortBy($list, $value)
  {
    $iterator = static::_lookupIterator($value);
    $result = array();

    foreach ($list as $index => $value) {
      $result[] = array(
        'value' => $value,
        'index' => $index,
        'criteria' => call_user_func($iterator, $value, $index, $list),
      );
    }

    usort($result, function($left, $right) {
      $a = $left['criteria'];
      $b = $right['criteria'];
      if ($a !== $b)
        return ($a < $b) ? -1 : 1;
      else
        return $left['index'] < $right['index'] ? -1 : 1;
    });

    return static::pluck($result, 'value');
  }

  /**
   * Returns a sorted copy of list, ranked in ascending order by the results of
   * running each value through iterator.
   *
   * @param   array|Iterator   $list
   * @param   callable|string  $value
   * @return  array
   */
  public static function groupBy($list, $value)
  {
    $iterator = static::_lookupIterator($value);
    $result = array();

    foreach ($list as $index => $value) {
      $key = call_user_func($iterator, $value, $index, $list);
      $result[$key][] = $value;
    }

    return $result;
  }

  /**
   * Sorts a list into groups and returns a count for the number of objects in
   * each group. Similar to groupBy, but instead of returning a list of values,
   * returns a count for the number of values in that group.
   *
   * @param   array|Iterator   $list
   * @param   callable|string  $value
   * @return  Iterator
   */
  public static function countBy($list, $value)
  {
    $iterator = static::_lookupIterator($value);
    $result = array();

    foreach ($list as $index => $value) {
      $key = call_user_func($iterator, $value, $index, $list);
      if (!isset($result[$key])) $result[$key] = 0;
      $result[$key]++;
    }

    return $result;
  }

  /**
   * Returns a shuffled copy of the list.
   *
   * @param   array|Iterator  $list
   * @return  array
   */
  public static function shuffle($list)
  {
    $result = static::toArray($list);
    shuffle($result);
    return $result;
  }

  /**
   * Converts the list (anything that can be iterated over), into a real Array.
   *
   * @param   array|Iterator  $list
   * @return  array
   */
  final public static function toArray($list)
  {
    return is_array($list) ? $list : iterator_to_array($list);
  }

  /**
   * Return the number of values in the list.
   *
   * @param   array|Countable|Iterator  $list
   * @return  int
   */
  public static function size($list)
  {
    return (is_array($list) || $list instanceof \Countable)
         ? count($list)
         : iterator_count($list);
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
  public static function first($array, $n = null)
  {
    if (is_int($n))
      return new \LimitIterator(static::_wrapArray($array), 0, $n);
    else
      foreach ($array as $value) return $value;
  }

  public static function head($array, $n = null)
  {
    return static::first($array, $n);
  }

  public static function take($array, $n = null)
  {
    return static::first($array, $n);
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
    $array = static::toArray($array);
    return array_slice($array, 0, count($array) - $n);
  }

  /**
   * Returns the last element of an array.
   *
   * @param   array|Iterator  $array
   * @param   int             $n
   * @return  array|mixed
   */
  public static function last($array, $n = null)
  {
    $array = static::toArray($array);
    return is_int($n) ? array_slice($array, $n) : end($array);
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
  public static function rest($array, $index = 1)
  {
    return new \LimitIterator(static::_wrapArray($array), $index);
  }

  public static function tail($array, $index = 1)
  {
    return self::rest($array, $index);
  }

  public static function drop($array, $index = 1)
  {
    return self::rest($array, $index);
  }

  /**
   * Returns a copy of the array with all falsy values removed.
   *
   * @param   array|Iterator  $array
   * @return  Iterator
   */
  public static function compact($array)
  {
    return static::filter($array, function($value) {
      return !!$value;
    });
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
    $it = new \RecursiveIteratorIterator(
      new FlattenIterator(static::_wrapArray($array))
    );
    $it->setMaxDepth($shallow ? 1 : -1);
    return $it;
  }

  /**
   * Returns a copy of the array with all instances of the values removed.
   *
   * @param   array|Iterator  $array
   * @param   mixed           *$values
   * @return  Iterator
   */
  public static function without($array)
  {
    return static::difference($array, array_slice(func_get_args(), 1));
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
    return new UnionIterator(func_get_args());
  }

  /**
   * Computes the list of values that are the intersection of all the arrays.
   *
   * @param   array|Iterator  $array
   * @param   array|Iterator  *$rest
   * @return  array
   */
  public static function intersection($array)
  {
    $arrays = array_map(__CLASS__.'::toArray', func_get_args());
    return call_user_func_array('array_intersect', $arrays);
  }

  /**
   * Similar to without, but returns the values from array that are not present
   * in the other arrays.
   *
   * @param   array|Iterator  $array
   * @param   array           $others
   * @return  Iterator
   */
  public static function difference($array, array $others)
  {
    return static::filter($array, function($value) use ($ohters) {
      return !in_array($value, $others, true);
    });
  }

  /**
   * Produce a duplicate-free version of the array.
   *
   * Alias: unique
   *
   * @param   array|Iterator  $array
   * @return  array
   */
  public static function uniq($array)
  {
    return array_unique(static::toArray($array), SORT_REGULAR);
  }

  public static function unique($array)
  {
    return static::uniq($array);
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
    return new RangeIterator($start, $stop, $step);
  }

  /**
   * Returns the same value that is used as the argument.
   *
   * @param   mixed  $value
   * @return  mixed  $value
   */
  final public static function identity($value)
  {
    return $value;
  }

  /**
   * Returns a wrapped object. Calling methods on this object will continue to
   * return wrapped objects until value is used.
   *
   * @param   mixed  $obj
   * @return  Chain
   */
  public static function chain($obj)
  {
    return new Chain($obj, get_called_class());
  }

  /**
   * Removes the last element from an array and returns that element.
   *
   * @param   array|Iterator  $array
   * @return  array
   */
  public static function pop($array)
  {
    $array = static::toArray($array);
    array_pop($array);
    return $array;
  }

  /**
   * Adds one or more elements to the end of an array and returns the new length
   * of the array.
   *
   * @param   array|Iterator  $array
   * @param   mixed           *$values
   * @return  array
   */
  public static function push($array)
  {
    $rest = array_slice(func_get_args(), 1);
    return array_merge(static::toArray($array), $rest);
  }

  /**
   * Reverses the order of the elements of an array -- the first becomes the
   * last, and the last becomes the first.
   *
   * @param   array|Iterator  $array
   * @return  array
   */
  public static function reverse($array)
  {
    return array_reverse(static::toArray($array));
  }

  /**
   * Removes the first element from an array and returns that element.
   *
   * @param   array|Iterator  $array
   * @return  array
   */
  public static function shift($array)
  {
    $array = static::toArray($array);
    array_shift($array);
    return $array;
  }

  /**
   * Removes the last element from an array and returns that element.
   *
   * @param   array|Iterator  $array
   * @return  array
   */
  public static function sort($array)
  {
    $array = static::toArray($array);
    sort($array);
    return $array;
  }

  /**
   * Removes the first element from an array and returns that element.
   *
   * @param   array|Iterator  $array
   * @param   int             $index
   * @param   int             $n
   * @return  array
   */
  public static function splice($array, $index, $n)
  {
    $rest = array_slice(func_get_args(), 3);
    return array_splice(static::toArray($array), $index, $n, $rest);
  }

  /**
   * Adds one or more elements to the front of an array and returns the new
   * length of the array.
   *
   * @param   array|Iterator  $array
   * @param   mixed           *$values
   * @return  array
   */
  public static function unshift($array)
  {
    $rest = array_slice(func_get_args(), 1);
    return array_merge($rest, static::toArray($array));
  }

  /**
   * Returns a new array comprised of this array joined with other array(s)
   * and/or value(s).
   *
   * @param   array|Iterator  *$arrays
   * @return  array
   */
  public static function concat()
  {
    return call_user_func_array('array_merge',
                                array_map(__CLASS__.'::toArray', func_get_args()));
  }

  /**
   * Joins all elements of an array into a string.
   *
   * @param   array|Iterator  $array
   * @param   string          $separator
   * @return  string
   */
  public static function join($array, $separator = ' ')
  {
    return implode($separator, static::toArray($array));
  }

  /**
   * Returns a shallow copy of a portion of an array.
   *
   * @param   array|Iterator  $array
   * @param   int             $begin
   * @param   int             $end
   * @return  Iterator
   */
  public static function slice($array, $begin, $end = -1)
  {
    return new \LimitIterator($array, $begin, $end);
  }
}

// __END__
// vim: expandtab softtabstop=2 shiftwidth=2
