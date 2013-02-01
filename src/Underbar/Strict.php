<?php

namespace Underbar;

abstract class Strict
{
    /**
     * Iterates over a list of elements, yielding each in turn to an iterator
     * function.
     *
     * @category  Collections
     * @param     array|Traversable  $list
     * @param     callable           $iterator
     * @return    void
     */
    public static function each($list, $iterator)
    {
        foreach ($list as $index => $value)
            call_user_func($iterator, $value, $index, $list);
    }

    /**
     * Produces a new array of values by mapping each value in list through a
     * transformation function (iterator).
     *
     * Alias: collect
     *
     * @category  Collections
     * @param     array|Traversable  $list
     * @param     callable           $iterator
     * @return    array|Iterator
     */
    public static function map($list, $iterator)
    {
        $result = array();

        foreach ($list as $index => $value)
            $result[$index] = call_user_func($iterator, $value, $index, $list);

        return $result;
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
     * @category  Collections
     * @param     array|Traversable  $list
     * @param     callable           $iterator
     * @param     mixed              $memo
     * @return    mixed
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
     * @category  Collections
     * @param     array|Traversable  $list
     * @param     callable           $iterator
     * @param     mixed              $memo
     * @return    mixed
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
     * Looks through each value in the list, returning the first one that passes
     * a truth test (iterator).
     *
     * Alias: detect
     *
     * @category  Collections
     * @param     array|Traversable  $list
     * @param     callable           $iterator
     * @return    mixed
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
     * Alias: detectSafe
     *
     * @category  Collections
     * @param     array|Traversable  $list
     * @param     callable           $iterator
     * @return    Option
     */
    public static function findSafe($list, $iterator)
    {
        return Option::fromValue(static::find($list, $iterator));
    }

    public static function detectSafe($list, $iterator)
    {
        return Option::fromValue(static::find($list, $iterator));
    }

    /**
     * Looks through each value in the list, returning an array of all the
     * values that pass a truth test (iterator).
     *
     * Alias: select
     *
     * @category  Collections
     * @param     array|Traversable  $list
     * @param     callable           $iterator
     * @return    array|Iterator
     */
    public static function filter($list, $iterator)
    {
        $result = array();

        foreach ($list as $index => $value) {
            if (call_user_func($iterator, $value, $index, $list))
                $result[$index] = $value;
        }

        return $result;
    }

    public static function select($list, $iterator)
    {
        return static::filter($list, $iterator);
    }

    /**
     * Looks through each value in the list, returning an array of all the
     * values that contain all of the key-value pairs listed in properties.
     *
     * @category  Collections
     * @param     array|Traversable  $list
     * @param     array|Traversable  $properties
     * @return    boolean
     */
    public static function where($list, $properties)
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
     * Looks through the list and returns the first value that matches all of
     * the key-value pairs listed in properties.
     *
     * @category  Collections
     * @param     array|Traversable  $list
     * @param     array|Traversable  $properties
     * @return    mixed
     */
    public static function findWhere($list, $properties)
    {
        return static::find($list, function($value) use ($properties) {
            foreach ($properties as $propKey => $propValue) {
                if (!((isset($value->$propKey) && $value->$propKey === $propValue)
                      || (isset($value[$propKey]) && $value[$propKey] === $propValue)))
                    return false;
            }
            return true;
        });
    }

    public static function findWhereSafe($list, $properties)
    {
        return Option::fromValue(static::findWhere($list, $properties));
    }

    /**
     * Returns the values in list without the elements that the truth test
     * (iterator) passes. The opposite of filter.
     *
     * @category  Collections
     * @param     array|Traversable  $list
     * @param     callable           $iterator
     * @return    array|Iterator
     */
    public static function reject($list, $iterator)
    {
        return static::filter($list, function($value, $index, $list) use ($iterator) {
            return !call_user_func($iterator, $value, $index, $list);
        });
    }

    /**
     * Returns true if all of the values in the list pass the iterator truth
     * test.
     *
     * Alias: all
     *
     * @category  Collections
     * @param     array|Traversable  $list
     * @param     callable           $iterator
     * @return    boolean
     */
    public static function every($list, $iterator = null)
    {
        $result = true;
        if (!$iterator) $iterator = get_called_class().'::identity';

        foreach ($list as $index => $value) {
            if (!($result = call_user_func($iterator, $value, $index, $list)))
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
     * @category  Collections
     * @param     array|Traversable  $list
     * @param     callable           $iterator
     * @return    boolean
     */
    public static function some($list, $iterator = null)
    {
        $result = false;
        if (!$iterator) $iterator = get_called_class().'::identity';

        foreach ($list as $index => $value) {
            if ($result = call_user_func($iterator, $value, $index, $list))
                break;
        }

        return !!$result;
    }

    public static function any($list, $iterator = null)
    {
        return static::some($list, $iterator);
    }

    /**
     * @category  Collections
     * @param     array|Traversable  $list
     * @return    int
     */
    public static function sum($list)
    {
        $result = 0;
        foreach ($list as $value) $result += $value;
        return $result;
    }

    /**
     * @param   array|Traversable  $list
     * @return  int
     */
    public static function product($list)
    {
        $result = 1;
        foreach ($list as $value) $result *= $value;
        return $result;
    }

    /**
     * Returns true if the value is present in the list.
     *
     * @category  Collections
     * @param     array|Traversable  $list
     * @param     mixed              $target
     * @return    boolean
     */
    public static function contains($list, $target)
    {
        foreach ($list as $value) {
            if ($value === $target) return true;
        }

        return false;
    }

    /**
     * Calls the method named by methodName on each value in the list.
     *
     * @category  Collections
     * @param     array|Traversable  $list
     * @param     string             $method
     * @param     miexed             *$arguments
     * @return    array|Iterator
     */
    public static function invoke($list, $method)
    {
        $args = array_slice(func_get_args(), 2);

        return static::map($list, function($value) use ($method, $args) {
            return call_user_func_array(array($value, $method), $args);
        });
    }

    /**
     * A convenient version of what is perhaps the most common use-case for map:
     * extracting a list of property values.
     *
     * @category  Collections
     * @param     array|Traversable  $list
     * @param     string             $property
     * @return    array|Iterator
     */
    public static function pluck($list, $property)
    {
        return static::map($list, function($value) use ($property) {
            if (is_array($value) && isset($value[$property]))
                return $value[$property];
            elseif (is_object($value) && isset($value->$property))
                return $value->$property;
            else
                return null;
        });
    }

    /**
     * Returns the maximum value in list. If iterator is passed, it will be used
     * on each value to generate the criterion by which the value is ranked.
     *
     * @category  Collections
     * @param     array|Traversable  $list
     * @param     callable           $iterator
     * @return    mixed
     */
    public static function max($list, $iterator = null)
    {
        if (!$iterator) {
            $array = static::toArray($list);
            return empty($array) ? -INF : max($array);
        }

        $computed = -INF;
        $result = -INF;
        foreach ($list as $index => $value) {
            $current = call_user_func($iterator, $value, $index, $list);
            if ($current > $computed) {
                $computed = $current;
                $result = $value;
            }
        }

        return $result;
    }

    /**
     * Returns the minimum value in list. If iterator is passed, it will be used
     * on each value to generate the criterion by which the value is ranked.
     *
     * @category  Collections
     * @param     array|Traversable  $list
     * @param     callable           $iterator
     * @return    mixed
     */
    public static function min($list, $iterator = null)
    {
        if (!$iterator) {
            $array = static::toArray($list);
            return empty($array) ? INF : min($array);
        }

        $computed = INF;
        $result = INF;
        foreach ($list as $index => $value) {
            $current = call_user_func($iterator, $value, $index, $list);
            if ($current < $computed) {
                $computed = $current;
                $result = $value;
            }
        }

        return $result;
    }

    /**
     * Returns a sorted copy of list, ranked in ascending order by the results
     * of running each value through iterator.
     *
     * @category  Collections
     * @param     array|Traversable  $list
     * @param     callable|string    $value
     * @return    array
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

        return self::pluck($result, 'value');
    }

    /**
     * Returns a sorted copy of list, ranked in ascending order by the results of
     * running each value through iterator.
     *
     * @category  Collections
     * @param     array|Traversable  $list
     * @param     callable|string    $value
     * @return    array
     */
    public static function groupBy($list, $value = null)
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
     * @category  Collections
     * @param     array|Traversable  $list
     * @param     callable|string    $value
     * @return    int
     */
    public static function countBy($list, $value = null)
    {
        $iterator = static::_lookupIterator($value);
        $result = array();

        foreach ($list as $index => $value) {
            $key = call_user_func($iterator, $value, $index, $list);
            if (isset($result[$key]))
                $result[$key]++;
            else
                $result[$key] = 1;
        }

        return $result;
    }

    /**
     * Returns a shuffled copy of the list.
     *
     * @category  Collections
     * @param     array|Traversable  $list
     * @return    array
     */
    public static function shuffle($list)
    {
        $result = static::toArray($list);
        shuffle($result);
        return $result;
    }

    /**
     * Converts the list (anything that can be iterated over), into a real
     * Array.
     *
     * @category  Collections
     * @param     mixed    $list
     * @param     boolean  $preserveKeys
     * @return    array
     */
    public static function toArray($list, $preserveKeys = null)
    {
        if (is_array($list))
            return $preserveKeys === false ? array_values($list) : $list;
        elseif ($list instanceof \Traversable)
            return iterator_to_array($list, $preserveKeys);
        elseif (is_string($list))
            return str_split($list);
        else
            return (array) $list;
    }

    /**
     * Return the number of values in the list.
     *
     * Alias: count
     *
     * @category  Collections
     * @param     mixed  $list
     * @return    int
     */
    public static function size($list)
    {
        if ($list instanceof \Countable) return count($list);
        if ($list instanceof \Traversable) return iterator_count($list);
        if (is_string($list)) return mb_strlen($list);
        return count($list);
    }

    public static function count($list)
    {
        return static::size($list);
    }

    /**
     * Returns the first element of an array.
     * Passing n will return the first n elements of the array.
     *
     * Alias: head, take
     *
     * @category  Arrays
     * @param     array|Traversable  $array
     * @param     int                $n
     * @return    mixed|Iterator
     */
    public static function first($array, $n = null, $guard = null)
    {
        if ($n !== null && $guard === null)
            return $n > 0 ? array_slice(static::toArray($array), 0, $n) : array();
        else
            foreach ($array as $value) return $value;
    }

    public static function head($array, $n = null, $guard = null)
    {
        return static::first($array, $n, $guard);
    }

    public static function take($array, $n = null, $guard = null)
    {
        return static::first($array, $n, $guard);
    }

    /**
     * Alias: headSafe, takeSafe
     *
     * @category  Arrays
     * @param     array|Traversable  $array
     * @param     int                $n
     * @return    Option
     */
    public static function firstSafe($array, $n = null, $guard = null)
    {
        return Option::fromValue(static::first($array, $n, $guard));
    }

    public static function headSafe($array, $n = null, $guard)
    {
        return Option::fromValue(static::first($array, $n, $guard));
    }

    public static function takeSafe($array, $n = null, $guard)
    {
        return Option::fromValue(static::first($array, $n, $guard));
    }

    /**
     * @category  Arrays
     * @param     array|Traversable  $array
     * @param     callable           $iterator
     * @return    Iterator
     */
    public static function takeWhile($array, $iterator)
    {
        $result = array();
        foreach ($array as $index => $value) {
            if (!call_user_func($iterator, $value, $index, $array)) break;
            $result[$index] = $value;
        }
        return $result;
    }

    /**
     * Returns everything but the last entry of the array.
     *
     * @category  Arrays
     * @param     array|Traversable  $array
     * @param     int                $n
     * @return    array|Iterator
     */
    public static function initial($array, $n = 1, $guard = null)
    {
        if ($guard !== null) $n = 1;
        return $n > 0
            ? array_slice(static::toArray($array), 0, -$n)
            : array();
    }

    /**
     * Returns the last element of an array.
     *
     * @category  Arrays
     * @param     array|Traversable  $array
     * @param     int                $n
     * @return    array|mixed
     */
    public static function last($array, $n = null, $guard = null)
    {
        $array = static::toArray($array);
        if ($n !== null && $guard === null)
            return $n > 0 ? array_slice($array, -$n) : array();
        else
            return empty($array) ? null : end($array);
    }

    public static function lastSafe($array, $n = null, $guard)
    {
        return Option::fromValue(static::last($array, $n, $guard));
    }

    /**
     * Returns the rest of the elements in an array.
     *
     * Alias: tail, drop
     *
     * @category  Arrays
     * @param     array|Traversable  $array
     * @param     int                $index
     * @return    array|Iterator
     */
    public static function rest($array, $index = 1, $guard = null)
    {
        if ($guard !== null) $index = 1;
        return array_slice(static::toArray($array), $index);
    }

    public static function tail($array, $index = 1, $guard = null)
    {
        return self::rest($array, $index, $guard);
    }

    public static function drop($array, $index = 1, $guard = null)
    {
        return self::rest($array, $index, $guard);
    }

    /**
     * @category  Arrays
     * @param     array|Traversable  $array
     * @param     callable           $iterator
     * @return    mixed|Iterator
     */
    public static function dropWhile($array, $iterator)
    {
        $result = array();
        $accepted = false;
        foreach ($array as $index => $value) {
            if ($accepted
                || ($accepted = !call_user_func($iterator, $value, $index, $array)))
                $result[$index] = $value;
        }
        return $result;
    }

    /**
     * Returns a copy of the array with all falsy values removed.
     *
     * @category  Arrays
     * @param     array|Traversable  $array
     * @return    array|Iterator
     */
    public static function compact($array)
    {
        return static::filter($array, get_called_class().'::identity');
    }

    /**
     * Flattens a nested array (the nesting can be to any depth).
     *
     * @category  Arrays
     * @param     array|Traversable  $array
     * @param     boolean            $shallow
     * @return    array|Iterator
     */
    public static function flatten($array, $shallow = false)
    {
        return static::_flatten($array, $shallow);
    }

    private static function _flatten($array, $shallow, &$output = array())
    {
        foreach ($array as $key => $value) {
            if (is_array($value) || $value instanceof \Traversable) {
                if ($shallow)
                    foreach ($value as $childValue) $output[] = $childValue;
                else
                    static::_flatten($value, $shallow, $output);
            } else {
                $output[] = $value;
            }
        }
        return $output;
    }

    /**
     * Returns a copy of the array with all instances of the values removed.
     *
     * @category  Arrays
     * @param     array|Traversable  $array
     * @param     mixed              *$values
     * @return    array|Iterator
     */
    public static function without($array)
    {
        return static::difference($array, array_slice(func_get_args(), 1));
    }

    /**
     * Computes the union of the passed-in arrays: the list of unique items,
     * in order, that are present in one or more of the arrays.
     *
     * @category  Arrays
     * @param     array|Traversable  *$arrays
     * @return    array|Iterator
     */
    public static function union()
    {
        return static::uniq(call_user_func_array(
            get_called_class().'::concat',
            func_get_args())
        );
    }

    /**
     * Computes the list of values that are the intersection of all the arrays.
     *
     * @param   array|Traversable  $array
     * @param   array|Traversable  *$rest
     * @return  array
     */
    public static function intersection()
    {
        $arrays = array_map(get_called_class().'::toArray', func_get_args());
        return call_user_func_array('array_intersect', $arrays);
    }

    /**
     * Similar to without, but returns the values from array that are not present
     * in the other arrays.
     *
     * @category  Arrays
     * @param     array|Traversable  $array
     * @param     array              *$others
     * @return    array|Iterator
     */
    public static function difference($array)
    {
        $rest = array_slice(func_get_args(), 1);
        return static::filter($array, function($value) use ($rest) {
            foreach ($rest as $others) {
                if (in_array($value, $others, true))
                    return false;
            }
            return true;
        });
    }

    /**
     * Produce a duplicate-free version of the array.
     *
     * Alias: unique
     *
     * @category  Arrays
     * @param     array|Traversable  $array
     * @param     callable           $iterator
     * @return    array|iterator
     */
    public static function uniq($array, $iterator = null)
    {
        $seenScalar = $seenObjects = $seenOthers = array();
        $iterator = static::_lookupIterator($iterator);
        return static::filter($array, function($value, $index, $list) use (
            $iterator,
            &$seenScalar,
            &$seenObjects,
            &$seenOthers
        ) {
            $value = call_user_func($iterator, $value, $index, $list);

            if (is_scalar($value)) {
                if (!isset($seenScalar[$value])) {
                    $seenScalar[$value] = 0;
                    return true;
                }
            } elseif (is_object($value)) {
                $hash = spl_object_hash($value);
                if (!isset($seenObjects[$hash])) {
                    $seenObjects[$hash] = 0;
                    return true;
                }
            } elseif (!in_array($value, $seenOthers, true)) {
                $seenOthers[] = $value;
                return true;
            }

            return false;
        });
    }

    public static function unique($array, $iterator = null)
    {
        return static::uniq($array);
    }

    /**
     * Merges together the values of each of the arrays with the values at the
     * corresponding position.
     *
     * @category  Arrays
     * @param     array|Traversable  *$arrays
     * @return    array|Iterator
     */
    public static function zip()
    {
        $arrays = $zipped = $result = array();;
        $loop = false;

        foreach (func_get_args() as $array) {
            $arrays[] = $wrapped = static::_wrapIterator($array);
            $wrapped->rewind();
            $loop = $loop || $wrapped->valid();
            $zipped[] = $wrapped->current();
        }

        while ($loop) {
            $result[] = $zipped;
            $zipped = array();
            $loop = false;
            foreach ($arrays as $array) {
                $array->next();
                $zipped[] = $array->current();
                $loop = $loop || $array->valid();
            }
        }

        return $result;
    }

    /**
     * @category  Arrays
     * @param     array|Traversable  *$arrays
     * @param     callable           $iterator
     * @return    array|Iterator
     */
    public static function zipWith()
    {
        $arrays = func_get_args();
        $iterator = array_pop($arrays);
        $zipped = call_user_func_array(get_called_class().'::zip', $arrays);
        return static::map($zipped, function($values, $index, $array) use ($iterator) {
            $values[] = $index;
            $values[] = $array;
            return call_user_func_array($iterator, $values);
        });
    }

    /**
     * Converts arrays into objects.
     *
     * @category  Arrays
     * @param     array|Traversable  $list
     * @param     array|Traversable  $values
     * @return    array|Iterator
     */
    public static function object($list, $values = null)
    {
        $result = array();
        $values = static::_wrapIterator($values);
        if ($values) {
            $values->rewind();
            foreach ($list as $key) {
                if ($values->valid()) {
                    $result[$key] = $values->current();
                    $values->next();
                } else {
                    $result[$key] = null;
                }
            }
        } else {
            foreach ($list as $value) $result[$value[0]] = $value[1];
        }
        return $result;
    }

    /**
     * Returns the index at which value can be found in the array, or -1 if
     * value is not present in the array.
     *
     * @category  Arrays
     * @param     array|Traversable  $array
     * @param     mixed              $value
     * @param     boolean|int        $isSorted
     * @return    int
     */
    public static function indexOf($array, $value, $isSorted = 0)
    {
        $array = static::toArray($array);

        if ($isSorted === true) {
            $i = static::sortedIndex($array, $value);
            return (isset($array[$i]) && $array[$i] === $value) ? $i : -1;
        } else {
            $l = count($array);
            $i = ($isSorted < 0) ? max(0, $l + $isSorted) : $isSorted;
            for (; $i < $l; $i++) {
                if (isset($array[$i]) && $array[$i] === $value)
                    return $i;
            }
        }

        return -1;
    }

    /**
     * Returns the index of the last occurrence of value in the array, or -1 if
     * value is not present.
     *
     * @category  Arrays
     * @param     array|Traversable  $array
     * @param     mixed              $value
     * @param     int                $fromIndex
     * @return    int
     */
    public static function lastIndexOf($array, $value, $fromIndex = null)
    {
        $array = static::toArray($array);
        $l = count($array);
        $i = ($fromIndex !== null) ? min($l, $fromIndex) : $l;

        while ($i-- > 0) {
            if (isset($array[$i]) && $array[$i] === $value) return $i;
        }

        return -1;
    }

    /**
     * Returns the index at which value can be found in the array, or -1 if
     * value is not present in the array.
     *
     * @category  Arrays
     * @param     array|Traversable  $list
     * @param     mixed              $values
     * @param     callable           $iterator
     * @return    int
     */
    public static function sortedIndex($list, $value, $iterator = null)
    {
        $array = static::toArray($list);
        $iterator = static::_lookupIterator($iterator);
        $value = call_user_func($iterator, $value);

        $low = 0;
        $high = count($array);

        while ($low < $high) {
            $mid = ($low + $high) >> 1;
            if (call_user_func($iterator, $array[$mid]) < $value)
                $low = $mid + 1;
            else
                $high = $mid;
        }

        return $low;
    }

    /**
     * A function to create flexibly-numbered lists of integers,
     * handy for each and map loops.
     *
     * @category  Arrays
     * @param     int             $start
     * @param     int             $stop
     * @param     int             $step
     * @return    array|Iterator
     */
    public static function range($start, $stop = null, $step = 1)
    {
        if ($stop === null) {
            $stop = $start;
            $start = 0;
        }

        $len = max(ceil(($stop - $start) / $step), 0);
        $range = array();

        for ($i = 0; $i < $len; $i++) {
            $range[] = $start;
            $start += $step;
        }

        return $range;
    }

    /**
     * @category  Arrays
     * @param     array|Traversable  $array
     * @return    array|Iterator
     * @throws    OverflowException
     */
    public static function cycle($array, $n = null)
    {
        $result = array();
        if ($n !== null) {
            throw new \OverflowException();
        } else {
            while ($n-- > 0) foreach ($array as $value) $result[] = $value;
        }
        return $result;
    }

    /**
     * @category  Arrays
     * @param     mixed              $value
     * @param     int                $n
     * @return    array|Iterator
     * @throws    OverflowException
     */
    public static function repeat($value, $n = -1)
    {
        if ($n < 0) throw new \OverflowException();
        return array_fill(0, $n, $value);
    }

    /**
     * @category  Arrays
     * @param     mixed           $memo
     * @param     callable        $iterator
     * @return    array|Iterator
     * @throws    OverflowException
     */
    public static function iterate($memo, $iterator)
    {
        throw new \OverflowException();
    }

    /**
     * Removes the last element from an array and returns that element.
     *
     * @category  Arrays
     * @param     array|Traversable  $array
     * @return    array|Iterator
     */
    public static function pop($array)
    {
        return static::initial($array);
    }

    /**
     * Adds one or more elements to the end of an array and returns the new
     * length of the array.
     *
     * @category  Arrays
     * @param     array|Traversable  $array
     * @param     mixed              *$values
     * @return    array
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
     * @category  Arrays
     * @param     array|Traversable  $array
     * @return    array
     */
    public static function reverse($array)
    {
        return array_reverse(static::toArray($array, true), true);
    }

    /**
     * Removes the first element from an array and returns that element.
     *
     * @category  Arrays
     * @param     array|Traversable  $array
     * @return    array|Iterator
     */
    public static function shift($array)
    {
        return static::rest($array);
    }

    /**
     * Removes the last element from an array and returns that element.
     *
     * @category  Arrays
     * @param     array|Traversable  $array
     * @return    array
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
     * @category  Arrays
     * @param     array|Traversable  $array
     * @param     int                $index
     * @param     int                $n
     * @return    array
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
     * @category  Arrays
     * @param     array|Traversable  $array
     * @param     mixed              *$values
     * @return    array
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
     * @category  Arrays
     * @param     array|Traversable  *$arrays
     * @return    array|Iterator
     */
    public static function concat()
    {
        return call_user_func_array(
            'array_merge',
            array_map(get_called_class().'::toArray', func_get_args())
        );
    }

    /**
     * Joins all elements of an array into a string.
     *
     * @category  Arrays
     * @param     array|Traversable  $array
     * @param     string             $separator
     * @return    string
     */
    public static function join($array, $separator = ',')
    {
        return implode($separator, static::toArray($array));
    }

    /**
     * Returns a shallow copy of a portion of an array.
     *
     * @category  Arrays
     * @param     array|Traversable  $array
     * @param     int                $begin
     * @param     int                $end
     * @return    array
     */
    public static function slice($array, $begin, $end = -1)
    {
        return array_slice(static::toArray($array), $begin, $end);
    }

    /**
     * Retrieve all the names of the object's properties.
     *
     * @category  Objects
     * @param     array|Traversable  $object
     * @return    array
     */
    public static function keys($object)
    {
        $result = array();
        foreach ($object as $key => $value) $result[] = $key;
        return $result;
    }

    /**
     * Return all of the values of the object's properties.
     *
     * @category  Objects
     * @param     array|Traversable  $object
     * @return    array
     */
    public static function values($object)
    {
        $result = array();
        foreach ($object as $value) $result[] = $value;
        return $result;
    }

    /**
     * Convert an object into a list of [key, value] pairs.
     *
     * @category  Objects
     * @param     array|Traversable  $object
     * @return    array
     */
    public static function pairs($object)
    {
        $result = array();
        foreach ($object as $key => $value) $result[] = array($key, $value);
        return $result;
    }

    /**
     * Returns a copy of the object where the keys have become the values and the
     * values the keys.
     *
     * @category  Objects
     * @param     array|Traversable  $object
     * @return    array
     */
    public static function invert($object)
    {
        $result = array();
        foreach ($object as $key => $value) $result[$value] = $key;
        return $result;
    }

    /**
     * Copy all of the properties in the source objects over to the destination
     * object, and return the destination object.
     *
     * @category  Objects
     * @param     array|Traversable  $destination
     * @param     array|Traversable  *$sources
     * @return    array
     */
    public static function extend($destination)
    {
        if ($destination instanceof \Traversable)
            $destination = iterator_to_array($destination, true);

        if (is_array($destination)) {
            foreach (array_slice(func_get_args(), 1) as $object) {
                foreach ($object as $key => $value)
                    $destination[$key] = $value;
            }
        } else {
            foreach (array_slice(func_get_args(), 1) as $object) {
                foreach ($object as $key => $value)
                    $destination->$key = $value;
            }
        }

        return $destination;
    }

    /**
     * Return a copy of the object, filtered to only have values for the
     * whitelisted keys (or array of valid keys).
     *
     * @category  Objects
     * @param     array|Traversable         $object
     * @param     array|string|Traversable  *$keys
     * @return    array|Iterator
     */
    public static function pick($object)
    {
        $whitelist = array();
        foreach (array_slice(func_get_args(), 1) as $keys) {
            foreach (static::_toTraversable($keys) as $key) $whitelist[$key] = 0;
        }

        return static::filter($object, function($value, $key) use ($whitelist) {
            return isset($whitelist[$key]);
        });
    }

    /**
     * Return a copy of the object, filtered to omit the blacklisted keys
     * (or array of keys).
     *
     * @category  Objects
     * @param     array|Traversable         $object
     * @param     array|string|Traversable  *$keys
     * @return    array|Iterator
     */
    public static function omit($object)
    {
        $blacklist = array();
        foreach (array_slice(func_get_args(), 1) as $keys) {
            foreach (static::_toTraversable($keys) as $key) $blacklist[$key] = 0;
        }

        return static::filter($object, function($value, $key) use ($blacklist) {
            return !isset($blacklist[$key]);
        });
    }

    /**
     * Copy all of the properties in the source objects over to the destination
     * object, and return the destination object.
     *
     * @category  Objects
     * @param     array|Traversable  $object
     * @param     array|Traversable  *$defaults
     * @return    array|object
     */
    public static function defaults($object)
    {
        if ($object instanceof \Traversable)
            $object = iterator_to_array($destination, true);

        if (is_array($object)) {
            foreach (array_slice(func_get_args(), 1) as $default) {
                foreach ($default as $key => $value) {
                    if (!isset($object[$key])) $object[$key] = $value;
                }
            }
        } else {
            foreach (array_slice(func_get_args(), 1) as $default) {
                foreach ($default as $key => $value) {
                    if (!isset($object->$key)) $object->$key = $value;
                }
            }
        }

        return $object;
    }

    /**
     * Invokes interceptor with the object, and then returns object.
     *
     * @category  Objects
     * @param     mixed     $object
     * @param     callable  $interceptor
     * @return    mixed
     */
    public static function tap($object, $interceptor)
    {
        call_user_func($interceptor, $object);
        return $object;
    }

    /**
     * Create a shallow-copied clone of the object. Any nested objects or arrays
     * will be copied by reference, not duplicated.
     *
     * @category  Objects
     * @param     mixed  $object
     * @return    mixed
     */
    public static function duplicate($object)
    {
        return is_object($object) ? clone $object : $object;
    }

    /**
     * Does the object contain the given key?
     *
     * @category  Objects
     * @param     array|object|Traversable  $object
     * @param     int|string                $key
     * @return    boolean
     */
    public static function has($object, $key)
    {
        if (is_array($object)) return isset($object[$key]);
        if ($object instanceof \Traversable) {
            foreach ($object as $k => $_) {
                if ($k === $key) return true;
            }
            return false;
        }

        // given a object
        return isset($object->$key);
    }

    /**
     * Partially apply a function by filling in any number of its arguments,
     * without changing its dynamic this value. A close cousin of bind.
     *
     * @category  Functions
     * @param     callable  $func
     * @param     mixed     *$args
     * @return    callable
     */
    public static function partial($func)
    {
        $args = array_slice(func_get_args(), 1);
        return function() use ($func, $args) {
            return call_user_func_array($func, array_merge($args, func_get_args()));
        };
    }

    /**
     * Memoizes a given function by caching the computed result. Useful for
     * speeding up slow-running computations.
     *
     * @category  Functions
     * @param     callable  $func
     * @param     callable  $hasher
     * @return    callable
     */
    public static function memoize($func, $hasher = null)
    {
        $memo = array();
        if (!$hasher) $hasher = get_called_class().'::identity';
        return function() use ($func, $hasher, &$memo) {
            $args = func_get_args();
            $key = call_user_func_array($hasher, $args);
            return isset($memo[$key])
                ? $memo[$key]
                : ($memo[$key] = call_user_func_array($func, $args));
        };
    }

    /**
     * Creates a version of the function that can only be called one time.
     *
     * @category  Functions
     * @param     callable  $func
     * @return    callable
     */
    public static function once($func)
    {
        $run = false;
        $memo = null;
        return function() use ($func, &$run, &$memo) {
            if ($run) return $memo;
            $run = true;
            return $memo = call_user_func_array($func, func_get_args());
        };
    }

    /**
     * Creates a version of the function that can only be called one time.
     *
     * @category  Functions
     * @param     int       $times
     * @param     callable  $func
     * @return    callable
     */
    public static function after($times, $func)
    {
        if ($times < 1) return $func;
        return function() use (&$times, $func) {
            if (--$times < 1)
                return call_user_func_array($func, func_get_args());
        };
    }

    /**
     * Wraps the first function inside of the wrapper function, passing it as
     * the first argument.
     *
     * @category  Functions
     * @param     callable  $func
     * @param     callable  $wrapper
     * @return    callable
     */
    public static function wrap($func, $wrapper)
    {
        return function() use ($func, $wrapper) {
            $args = func_get_args();
            array_unshift($args, $func);
            return call_user_func_array($wrapper, $args);
        };
    }

    /**
     * Returns the composition of a list of functions, where each function
     * consumes the return value of the function that follows.
     *
     * @category  Functions
     * @param     callable   *$functions
     * @return    callable
     */
    public static function compose()
    {
        $functions = func_get_args();
        return function() use ($functions) {
            $args = func_get_args();
            for ($i = count($functions); $i--;)
                $args = array(call_user_func_array($functions[$i], $args));
            return $args[0];
        };
    }

    /**
     * Returns the same value that is used as the argument.
     *
     * @category  Utility
     * @param     mixed    $value
     * @return    mixed
     */
    public static function identity($value)
    {
        return $value;
    }

    /**
     * Invokes the given iterator function n times. Each invocation of iterator
     * is called with an index argument.
     *
     * @category  Utility
     * @param     int       $n
     * @param     callable  $iterator
     * @return    array
     */
    public static function times($n, $iterator)
    {
        $accum = array();
        for ($i = 0; $i < $n; $i++) $accum[] = call_user_func($iterator, $i);
        return $accum;
    }

    /**
     * Returns a wrapped object. Calling methods on this object will continue to
     * return wrapped objects until value is used.
     *
     * @category  Chaining
     * @param     mixed             $value
     * @return    Internal\Wrapper
     */
    public static function chain($value)
    {
        return new Internal\Wrapper($value, get_called_class());
    }

    protected static function _lookupIterator($value)
    {
        if (is_callable($value))
            return $value;
        elseif (is_scalar($value))
            return function($obj) use ($value) {
                return is_array($obj) ? $obj[$value] : $obj->$value;
            };
        else
            return get_called_class().'::identity';
    }

    protected static function _wrapIterator($list)
    {
        if (is_array($list))
            return new \ArrayIterator($list);
        elseif ($list instanceof \Iterator)
            return $list;
        elseif ($list instanceof \Traversable)
            return new \IteratorIterator($list);
        else
            return $list;
    }

    protected static function _toTraversable($list)
    {
        if (is_array($list) || $list instanceof \Traversable)
            return $list;
        else
            return (array) $list;
    }
}

// __END__
// vim: expandtab softtabstop=4 shiftwidth=4
