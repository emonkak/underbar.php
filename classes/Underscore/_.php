<?php

namespace Underscore;

abstract class _
{
  /**
   * Iterates over a list of elements, yielding each in turn to an iterator
   * function.
   *
   * @param   array     $list
   * @param   callable  $iterator
   * @return  void
   */
  public static function each($list, $iterator)
  {
    foreach ($list as $index => $value)
      call_user_func($iterator, $value, $index, $list);
  }

  /**
   * Produces a new array of values by mapping each value in list through a
   * transformation function (iterator)
   *
   * Alias: collect
   *
   * @param   array     $list
   * @param   callable  $iterator
   * @return  array
   */
  public static function map($list, $iterator)
  {
    return class_exists('Generator')
         ? MapGenerator::map($list, $iterator)
         : MapIterator::map($list, $iterator);
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
   * @param   array     $list
   * @param   callable  $iterator
   * @param   mixed     $memo
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
   * @param   array     $list
   * @param   callable  $iterator
   * @param   mixed     $memo
   * @return  mixed
   */
  public static function reduceRight($list, $iterator, $memo)
  {
    foreach (array_reverse($list) as $index => $value)
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
   * @param   array     $list
   * @param   callable  $iterator
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
   * @param   array     $list
   * @param   callable  $iterator
   * @return  array
   */
  public static function filter($list, $iterator)
  {
    return class_exists('Generator')
         ? FilterGenerator::filter($list, $iterator)
         : FilterIterator::filter($list, $iterator);
  }

  public static function select($list, $iterator)
  {
    return static::filter($list, $iterator);
  }

  /**
   * Looks through each value in the list, returning an array of all the values
   * that contain all of the key-value pairs listed in properties.
   *
   * @param   array  $list
   * @param   array  $properties
   * @return  array
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
   * @param   array     $list
   * @param   callable  $iterator
   * @return  array
   */
  public static function reject($list, $iterator)
  {
    return static::filter($list, function($value, $index, $list) use ($iterator) {
      return !call_user_func($iterator, $index, $value);
    });
  }

  /**
   * Returns true if all of the values in the list pass the iterator truth test.
   *
   * Alias: all
   *
   * @param   array     $list
   * @param   callable  $iterator
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
   * @param   array     $list
   * @param   callable  $iterator
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
   * @param   array    $list
   * @param   mixed    $target
   * @return  boolean
   */
  public static function contains($list, $target)
  {
    foreach ($list as $index => $value) {
      if ($value === $target)
        return true;
    }

    return false;
  }

  /**
   * Calls the method named by methodName on each value in the list.
   *
   * @param   array   $list
   * @param   string  $methodName
   * @return  array
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
   * @param   array   $list
   * @param   string  $propertyName
   * @return  array
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

  /**
   * Returns the maximum value in list. If iterator is passed, it will be used
   * on each value to generate the criterion by which the value is ranked.
   *
   * @param   array     $list
   * @param   callable  $iterator
   * @return  mixed
   */
  public static function max($list, $iterator = null)
  {
    $result = array('computed' => -PHP_INT_MAX, 'value' => -PHP_INT_MAX);

    foreach ($list as $index => $value) {
      $computed = $iterator
                ? call_user_func($iterator, $value, $index, $list)
                : $value;
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
   * @param   array     $list
   * @param   callable  $iterator
   * @return  mixed
   */
  public static function min($list, $iterator = null)
  {
    $result = array('computed' => PHP_INT_MAX, 'value' => PHP_INT_MAX);

    foreach ($list as $index => $value) {
      $computed = $iterator
                ? call_user_func($iterator, $value, $index, $list)
                : $value;
      if ($computed < $result['computed']) {
        $result['computed'] = $computed;
        $result['value'] = $value;
      }
    }

    return $result['value'];
  }

  private static function _lookupIterator($value)
  {
    return is_callable($value) ? $value : function($obj) use ($value) {
      return is_array($obj) ? $obj[$value] : $obj->$value;
    };
  }

  /**
   * Returns a sorted copy of list, ranked in ascending order by the results of
   * running each value through iterator.
   *
   * @param   array            $list
   * @param   callable|string  $value
   * @return  array
   */
  public static function sortBy($list, $value)
  {
    $iterator = static::_lookupIterator($value);
    $tmp = array();

    foreach ($list as $index => $value) {
      $tmp[] = array(
        'value' => $value,
        'index' => $index,
        'criteria' => call_user_func($iterator, $value, $index, $list),
      );
    }

    usort($tmp, function($left, $right) {
      $a = $left['criteria'];
      $b = $right['criteria'];
      if ($a !== $b)
        return ($a < $b) ? -1 : 1;
      else
        return $left['index'] < $right['index'] ? -1 : 1;
    });

    return static::pluck($tmp, 'value');
  }

  /**
   * Returns a sorted copy of list, ranked in ascending order by the results of
   * running each value through iterator.
   *
   * @param   array            $list
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
   * @param   array            $list
   * @param   callable|string  $value
   * @return  array
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
   * @param   array  $list
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
   * @param   array     $list
   * @return  array
   */
  public static function toArray($list)
  {
    return is_array($list) ? $list : iterator_to_array($list);
  }

  /**
   * Return the number of values in the list.
   *
   * @param   array     $list
   * @return  array
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
   * @param   array  $array
   * @param   int    $n
   * @return  array|mixed
   */
  public static function first($array, $n = 1)
  {
    if ($n > 1) {
      return class_exists('Generator')
           ? TakeGenerator::take($array, $n)
           : TakeIterator::take($array, $n);
    } else {
      foreach ($array as $value)
        return $value;
    }
  }

  public static function head($array, $n = 1)
  {
    return static::first($array, $n);
  }

  public static function take($array, $n = 1)
  {
    return static::first($array, $n);
  }

  public static function chain($collection)
  {
    return new Chain($collection, get_called_class());
  }
}

// __END__
// vim: expandtab softtabstop=2 shiftwidth=2
