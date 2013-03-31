<?php

namespace Underbar;

class Strict
{
    final private function __construct()
    {
        // Not allow to create an instance
    }

    /**
     * Iterates over a list of elements, yielding each in turn to an iterator
     * function.
     *
     * @category  Collections
     * @param     array|Traversable  $xs
     * @param     callable           $f
     * @return    void
     */
    public static function each($xs, $f)
    {
        foreach ($xs as $i => $x) {
            call_user_func($f, $x, $i, $xs);
        }
    }

    /**
     * Produces a new array of values by mapping each value in list through a
     * transformation function (iterator).
     *
     * Alias: collect
     *
     * @category  Collections
     * @param     array|Traversable  $xs
     * @param     callable           $f
     * @return    array
     */
    public static function map($xs, $f)
    {
        $ys = array();
        foreach ($xs as $i => $x) {
            $ys[$i] = call_user_func($f, $x, $i, $xs);
        }
        return $ys;
    }

    public static function collect($xs, $f)
    {
        return static::map($xs, $f);
    }

    /**
     * Alias: collectKey
     *
     * @category  Collections
     * @param     array|Traversable  $xs
     * @param     callable           $f
     * @return    array
     */
    public static function mapKey($xs, $f)
    {
        $ys = array();
        foreach ($xs as $i => $x) {
            $ys[call_user_func($f, $x, $i, $xs)] = $x;
        }
        return $ys;
    }

    public static function collectKey($xs, $f)
    {
        return static::mapKey($xs, $f);
    }

    /**
     * Alias: parallelCollect
     *
     * @category  Collections
     * @param     array|Traversable  $xs
     * @param     callable           $f
     * @param     int                $timeout
     * @return    Parallel
     */
    public static function parallelMap($xs, $f, $n = 1, $timeout = null)
    {
        $concurrent = new Parallel($f, $n, $timeout);
        $concurrent->pushAll($xs);
        return $concurrent;
    }

    public static function parallelCollect($xs, $f, $n = 1, $timeout = null)
    {
        return static::parallelMap($xs, $f, $n, $timeout);
    }

    /**
     * Also known as inject and foldl, reduce boils down a list of values into a
     * single value.
     *
     * Alias: inject, foldl
     *
     * @category  Collections
     * @param     array|Traversable  $xs
     * @param     callable           $f
     * @param     mixed              $acc
     * @return    mixed
     */
    public static function reduce($xs, $f, $acc)
    {
        foreach ($xs as $i => $x) {
            $acc = call_user_func($f, $acc, $x, $i, $xs);
        }
        return $acc;
    }

    public static function inject($xs, $f, $acc)
    {
        return static::reduce($xs, $f, $acc);
    }

    public static function foldl($xs, $f, $acc)
    {
        return static::reduce($xs, $f, $acc);
    }

    /**
     * The right-associative version of reduce.
     *
     * Alias: foldr
     *
     * @category  Collections
     * @param     array|Traversable  $xs
     * @param     callable           $f
     * @param     mixed              $acc
     * @return    mixed
     */
    public static function reduceRight($xs, $f, $acc)
    {
        foreach (static::reverse($xs) as $i => $x) {
            $acc = call_user_func($f, $acc, $x, $i, $xs);
        }
        return $acc;
    }

    public static function foldr($xs, $f, $acc)
    {
        return static::reduceRight($xs, $f, $acc);
    }

    /**
     * Looks through each value in the list, returning the first one that passes
     * a truth test (iterator).
     *
     * Alias: detect
     *
     * @category  Collections
     * @param     array|Traversable  $xs
     * @param     callable           $f
     * @return    mixed
     */
    public static function find($xs, $f)
    {
        foreach ($xs as $i => $x) {
            if (call_user_func($f, $x, $i, $xs)) {
                return $x;
            }
        }
    }

    public static function detect($xs, $f)
    {
        return static::find($xs, $f);
    }

    /**
     * Alias: detectSafe
     *
     * @category  Collections
     * @param     array|Traversable  $xs
     * @param     callable           $f
     * @return    Option
     */
    public static function findSafe($xs, $f)
    {
        foreach ($xs as $i => $x) {
            if (call_user_func($f, $x, $i, $xs)) {
                return new Option\Some($x);
            }
        }

        return Option\None::instance();
    }

    public static function detectSafe($xs, $f)
    {
        return static::findSafe($xs, $f);
    }

    /**
     * Looks through each value in the list, returning an array of all the
     * values that pass a truth test (iterator).
     *
     * Alias: select
     *
     * @category  Collections
     * @param     array|Traversable  $xs
     * @param     callable           $f
     * @return    array
     */
    public static function filter($xs, $f)
    {
        $ys = array();
        foreach ($xs as $i => $x) {
            if (call_user_func($f, $x, $i, $xs)) {
                $ys[$i] = $x;
            }
        }
        return $ys;
    }

    public static function select($xs, $f)
    {
        return static::filter($xs, $f);
    }

    /**
     * Looks through each value in the list, returning an array of all the
     * values that contain all of the key-value pairs listed in properties.
     *
     * @category  Collections
     * @param     array|Traversable  $xs
     * @param     array|Traversable  $properties
     * @return    boolean
     */
    public static function where($xs, $properties)
    {
        return static::filter($xs, function($x) use ($properties) {
            foreach ($properties as $key => $value) {
                if (!((isset($x->$key) && $x->$key === $value)
                      || (isset($x[$key]) && $x[$key] === $value))) {
                    return false;
                }
            }
            return true;
        });
    }

    /**
     * Looks through the list and returns the first value that matches all of
     * the key-value pairs listed in properties.
     *
     * @category  Collections
     * @param     array|Traversable  $xs
     * @param     array|Traversable  $properties
     * @return    mixed
     */
    public static function findWhere($xs, $properties)
    {
        return static::find($xs, function($x) use ($properties) {
            foreach ($properties as $key => $value) {
                if (!((isset($x->$key) && $x->$key === $value)
                      || (isset($x[$key]) && $x[$key] === $value))) {
                    return false;
                }
            }
            return true;
        });
    }

    public static function findWhereSafe($xs, $properties)
    {
        return static::findSafe($xs, function($x) use ($properties) {
            foreach ($properties as $key => $value) {
                if (!((isset($x->$key) && $x->$key === $value)
                      || (isset($x[$key]) && $x[$key] === $value))) {
                    return false;
                }
            }
            return true;
        });
    }

    /**
     * Returns the values in list without the elements that the truth test
     * (iterator) passes. The opposite of filter.
     *
     * @category  Collections
     * @param     array|Traversable  $xs
     * @param     callable           $f
     * @return    array
     */
    public static function reject($xs, $f)
    {
        return static::filter($xs, function($x, $i, $xs) use ($f) {
            return !call_user_func($f, $x, $i, $xs);
        });
    }

    /**
     * Returns true if all of the values in the list pass the iterator truth
     * test.
     *
     * Alias: all
     *
     * @category  Collections
     * @param     array|Traversable  $xs
     * @param     callable           $f
     * @return    boolean
     */
    public static function every($xs, $f = null)
    {
        $f = static::_lookupIterator($f);

        foreach ($xs as $i => $x) {
            if (!call_user_func($f, $x, $i, $xs)) {
                return false;
            }
        }

        return true;
    }

    public static function all($xs, $f = null)
    {
        return static::every($xs, $f);
    }

    /**
     * Returns true if any of the values in the list pass the iterator truth test.
     *
     * Alias: any
     *
     * @category  Collections
     * @param     array|Traversable  $xs
     * @param     callable           $f
     * @return    boolean
     */
    public static function some($xs, $f = null)
    {
        $f = static::_lookupIterator($f);

        foreach ($xs as $i => $x) {
            if (call_user_func($f, $x, $i, $xs)) {
                return true;
            }
        }

        return false;
    }

    public static function any($xs, $f = null)
    {
        return static::some($xs, $f);
    }

    /**
     * @category  Collections
     * @param     array|Traversable  $xs
     * @return    int
     */
    public static function sum($xs)
    {
        $acc = 0;
        foreach ($xs as $x) {
            $acc += $x;
        }
        return $acc;
    }

    /**
     * @category  Collections
     * @param     array|Traversable  $xs
     * @return    int
     */
    public static function product($xs)
    {
        $acc = 1;
        foreach ($xs as $x) {
            $acc *= $x;
        }
        return $acc;
    }

    /**
     * Returns true if the value is present in the list.
     *
     * @category  Collections
     * @param     array|Traversable  $xs
     * @param     mixed              $target
     * @return    boolean
     */
    public static function contains($xs, $target)
    {
        foreach ($xs as $x) {
            if ($x === $target) {
                return true;
            }
        }
        return false;
    }

    /**
     * Calls the method named by methodName on each value in the list.
     *
     * @category  Collections
     * @param     array|Traversable  $xs
     * @param     string             $method
     * @param     miexed             *$arguments
     * @return    array
     */
    public static function invoke($xs, $method)
    {
        $args = array_slice(func_get_args(), 2);
        return static::map($xs, function($x) use ($method, $args) {
            return call_user_func_array(array($x, $method), $args);
        });
    }

    /**
     * A convenient version of what is perhaps the most common use-case for map:
     * extracting a list of property values.
     *
     * @category  Collections
     * @param     array|Traversable  $xs
     * @param     string             $property
     * @return    array
     */
    public static function pluck($xs, $property)
    {
        return static::map($xs, function($x) use ($property) {
            if (isset($x->$property)) {
                return $x->$property;
            } elseif (isset($x[$property])) {
                return $x[$property];
            } else {
                return null;
            }
        });
    }

    /**
     * Returns the maximum value in list. If iterator is passed, it will be used
     * on each value to generate the criterion by which the value is ranked.
     *
     * @category  Collections
     * @param     array|Traversable  $xs
     * @param     callable           $f
     * @return    mixed
     */
    public static function max($xs, $f = null)
    {
        if ($f === null) {
            $xs = static::toArray($xs);
            return empty($xs) ? -INF : max($xs);
        }

        $computed = -INF;
        $result = -INF;
        foreach ($xs as $i => $x) {
            $current = call_user_func($f, $x, $i, $xs);
            if ($current > $computed) {
                $computed = $current;
                $result = $x;
            }
        }

        return $result;
    }

    /**
     * Returns the minimum value in list. If iterator is passed, it will be used
     * on each value to generate the criterion by which the value is ranked.
     *
     * @category  Collections
     * @param     array|Traversable  $xs
     * @param     callable           $f
     * @return    mixed
     */
    public static function min($xs, $f = null)
    {
        if ($f === null) {
            $xs = static::toArray($xs);
            return empty($xs) ? INF : min($xs);
        }

        $computed = INF;
        $result = INF;
        foreach ($xs as $i => $x) {
            $current = call_user_func($f, $x, $i, $xs);
            if ($current < $computed) {
                $computed = $current;
                $result = $x;
            }
        }

        return $result;
    }

    /**
     * Returns a sorted copy of list, ranked in ascending order by the results
     * of running each value through iterator.
     *
     * @category  Collections
     * @param     array|Traversable  $xs
     * @param     callable|string    $x
     * @return    array
     */
    public static function sortBy($xs, $x)
    {
        $f = static::_lookupIterator($x);
        $result = array();

        foreach ($xs as $i => $x) {
            $result[] = array(
                'value' => $x,
                'index' => $i,
                'criteria' => call_user_func($f, $x, $i, $xs),
            );
        }

        usort($result, function($left, $right) {
            $a = $left['criteria'];
            $b = $right['criteria'];
            if ($a !== $b) {
                return $a < $b ? -1 : 1;
            } else {
                return $left['index'] < $right['index'] ? -1 : 1;
            }
        });

        return self::pluck($result, 'value');
    }

    /**
     * Returns a sorted copy of list, ranked in ascending order by the results of
     * running each value through iterator.
     *
     * @category  Collections
     * @param     array|Traversable  $xs
     * @param     callable|string    $f
     * @return    array
     */
    public static function groupBy($xs, $f = null)
    {
        $f = static::_lookupIterator($f);
        $result = array();

        foreach ($xs as $i => $x) {
            $key = call_user_func($f, $x, $i, $xs);
            $result[$key][] = $x;
        }

        return $result;
    }

    /**
     * Sorts a list into groups and returns a count for the number of objects in
     * each group. Similar to groupBy, but instead of returning a list of values,
     * returns a count for the number of values in that group.
     *
     * @category  Collections
     * @param     array|Traversable  $xs
     * @param     callable|string    $x
     * @return    int
     */
    public static function countBy($xs, $f = null)
    {
        $f = static::_lookupIterator($f);
        $result = array();

        foreach ($xs as $i => $x) {
            $key = call_user_func($f, $x, $i, $xs);
            if (isset($result[$key])) {
                $result[$key]++;
            } else {
                $result[$key] = 1;
            }
        }

        return $result;
    }

    /**
     * Returns a shuffled copy of the list.
     *
     * @category  Collections
     * @param     array|Traversable  $xs
     * @return    array
     */
    public static function shuffle($xs)
    {
        $xs = static::toArray($xs);
        shuffle($xs);
        return $xs;
    }

    /**
     * Converts the list (anything that can be iterated over), into a real
     * Array.
     *
     * @category  Collections
     * @param     mixed    $xs
     * @param     boolean  $preserveKeys
     * @return    array
     */
    public static function toArray($xs, $preserveKeys = null)
    {
        if (is_array($xs)) {
            return $preserveKeys === false ? array_values($xs) : $xs;
        }
        if ($xs instanceof \Traversable) {
            return iterator_to_array($xs, $preserveKeys);
        }
        if (is_string($xs)) {
            return str_split($xs);
        }
        return (array) $xs;
    }

    /**
     * Return the number of values in the list.
     *
     * @category  Collections
     * @param     mixed  $xs
     * @return    int
     */
    public static function size($xs)
    {
        if ($xs instanceof \Countable) {
            return count($xs);
        }
        if ($xs instanceof \Traversable) {
            return iterator_count($xs);
        }
        if (is_string($xs)) {
            return mb_strlen($xs);
        }
        return count($xs);
    }

    /**
     * @category  Collections
     * @param     mixed     $xs
     * @param     callable  $f
     * @return    array
     */
    public static function span($xs, $f)
    {
        $ys = array(array(), array());
        $inPrefix = true;

        foreach ($xs as $i => $x) {
            if ($inPrefix = $inPrefix && call_user_func($f, $x, $i, $xs)) {
                $ys[0][] = $x;
            } else {
                $ys[1][] = $x;
            }
        }

        return $ys;
    }

    /**
     * Returns the first element of an array.
     * Passing n will return the first n elements of the array.
     *
     * Alias: head, take
     *
     * @category  Arrays
     * @param     array|Traversable  $xs
     * @param     int                $n
     * @return    array|mixed
     */
    public static function first($xs, $n = null, $guard = null)
    {
        if ($n !== null && $guard === null) {
            return static::_first($xs, $n);
        }

        foreach ($xs as $x) {
            return $x;
        }
    }

    public static function head($xs, $n = null, $guard = null)
    {
        return static::first($xs, $n, $guard);
    }

    public static function take($xs, $n = null, $guard = null)
    {
        return static::first($xs, $n, $guard);
    }

    /**
     * Alias: headSafe, takeSafe
     *
     * @category  Arrays
     * @param     array|Traversable  $xs
     * @param     int                $n
     * @return    Option
     */
    public static function firstSafe($xs, $n = null, $guard = null)
    {
        if ($n !== null && $guard === null) {
            return static::_first($xs, $n);
        }

        foreach ($xs as $x) {
            return new Option\Some($x);
        }

        return Option\None::instance();
    }

    public static function headSafe($xs, $n = null, $guard)
    {
        return static::firstSafe($xs, $n, $guard);
    }

    public static function takeSafe($xs, $n = null, $guard)
    {
        return static::firstSafe($xs, $n, $guard);
    }

    protected static function _first($xs, $n = null)
    {
        return $n > 0 ? array_slice(static::toArray($xs), 0, $n) : array();
    }

    /**
     * @category  Arrays
     * @param     array|Traversable  $xs
     * @param     callable           $f
     * @return    array
     */
    public static function takeWhile($xs, $f)
    {
        $result = array();
        foreach ($xs as $i => $x) {
            if (!call_user_func($f, $x, $i, $xs)) {
                break;
            }
            $result[$i] = $x;
        }
        return $result;
    }

    /**
     * Returns everything but the last entry of the array.
     *
     * @category  Arrays
     * @param     array|Traversable  $xs
     * @param     int                $n
     * @return    array
     */
    public static function initial($xs, $n = 1, $guard = null)
    {
        if ($guard !== null) {
            $n = 1;
        }
        return $n > 0 ? array_slice(static::toArray($xs), 0, -$n) : array();
    }

    /**
     * Returns the last element of an array.
     *
     * @category  Arrays
     * @param     array|Traversable  $xs
     * @param     int                $n
     * @return    array|mixed
     */
    public static function last($xs, $n = null, $guard = null)
    {
        if ($n !== null && $guard === null) {
            return static::_last($xs, $n);
        }
        $x = null;
        foreach ($xs as $x);
        return $x;
    }

    public static function lastSafe($xs, $n = null, $guard = null)
    {
        $xs = static::toArray($xs);
        if ($n !== null && $guard === null) {
            return static::_last($xs, $n);
        }
        foreach ($xs as $x);
        return isset($x) ? new Option\Some($x) : Option\None::instance();
    }

    protected static function _last($xs, $n = null)
    {
        $queue = new \SplQueue();
        if ($n <= 0) {
            return $queue;
        }

        $i = 0;
        foreach ($xs as $x) {
            if ($i === $n) {
                $queue->dequeue();
                $i--;
            }
            $queue->enqueue($x);
            $i++;
        }

        return $queue;
    }

    /**
     * Returns the rest of the elements in an array.
     *
     * Alias: tail, drop
     *
     * @category  Arrays
     * @param     array|Traversable  $xs
     * @param     int                $n
     * @return    array
     */
    public static function rest($xs, $n = 1, $guard = null)
    {
        if ($guard !== null) {
            $n = 1;
        }
        return array_slice(static::toArray($xs), $n);
    }

    public static function tail($xs, $n = 1, $guard = null)
    {
        return self::rest($xs, $n, $guard);
    }

    public static function drop($xs, $n = 1, $guard = null)
    {
        return self::rest($xs, $n, $guard);
    }

    /**
     * @category  Arrays
     * @param     array|Traversable  $xs
     * @param     callable           $f
     * @return    mixed|Iterator
     */
    public static function dropWhile($xs, $f)
    {
        $result = array();
        $accepted = false;
        foreach ($xs as $i => $x) {
            if ($accepted || ($accepted = !call_user_func($f, $x, $i, $xs))) {
                $result[$i] = $x;
            }
        }
        return $result;
    }

    /**
     * Returns a copy of the array with all falsy values removed.
     *
     * @category  Arrays
     * @param     array|Traversable  $xs
     * @return    array
     */
    public static function compact($xs)
    {
        return static::filter($xs, get_called_class().'::identity');
    }

    /**
     * Flattens a nested array (the nesting can be to any depth).
     *
     * @category  Arrays
     * @param     array|Traversable  $xs
     * @param     boolean            $shallow
     * @return    array
     */
    public static function flatten($xs, $shallow = false)
    {
        return static::_flatten($xs, $shallow);
    }

    private static function _flatten($xss, $shallow, &$output = array())
    {
        foreach ($xss as $xs) {
            if (static::_isTraversable($xs)) {
                if ($shallow) {
                    foreach ($xs as $x) {
                        $output[] = $x;
                    }
                } else {
                    static::_flatten($xs, $shallow, $output);
                }
            } else {
                $output[] = $xs;
            }
        }
        return $output;
    }

    /**
     * Returns a copy of the array with all instances of the values removed.
     *
     * @category  Arrays
     * @param     array|Traversable  $xs
     * @param     mixed              *$values
     * @return    array
     */
    public static function without($xs)
    {
        return static::difference($xs, array_slice(func_get_args(), 1));
    }

    /**
     * Computes the union of the passed-in arrays: the list of unique items,
     * in order, that are present in one or more of the arrays.
     *
     * @category  Arrays
     * @param     array|Traversable  *$xss
     * @return    array
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
     * @param   array|Traversable  $xs
     * @param   array|Traversable  *$rest
     * @return  array
     */
    public static function intersection()
    {
        $xss = array_map(get_called_class().'::toArray', func_get_args());
        return call_user_func_array('array_intersect', $xss);
    }

    /**
     * Similar to without, but returns the values from array that are not present
     * in the other arrays.
     *
     * @category  Arrays
     * @param     array|Traversable  $xs
     * @param     array              *$others
     * @return    array
     */
    public static function difference($xs)
    {
        $others = array_slice(func_get_args(), 1);
        return static::filter($xs, function($x) use ($others) {
            foreach ($others as $other) {
                if (in_array($x, $other, true)) {
                    return false;
                }
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
     * @param     array|Traversable  $xs
     * @param     callable           $f
     * @return    array
     */
    public static function uniq($xs, $f = null)
    {
        $seenScalar = $seenObjects = $seenOthers = array();
        $f = static::_lookupIterator($f);
        return static::filter($xs, function($x, $i, $xs) use (
            $f,
            &$seenScalar,
            &$seenObjects,
            &$seenOthers
        ) {
            $x = call_user_func($f, $x, $i, $xs);

            if (is_scalar($x)) {
                if (!isset($seenScalar[$x])) {
                    $seenScalar[$x] = 0;
                    return true;
                }
            } elseif (is_object($x)) {
                $hash = spl_object_hash($x);
                if (!isset($seenObjects[$hash])) {
                    $seenObjects[$hash] = 0;
                    return true;
                }
            } elseif (!in_array($x, $seenOthers, true)) {
                $seenOthers[] = $x;
                return true;
            }

            return false;
        });
    }

    public static function unique($xs, $f = null)
    {
        return static::uniq($xs);
    }

    /**
     * Merges together the values of each of the arrays with the values at the
     * corresponding position.
     *
     * @category  Arrays
     * @param     array|Traversable  *$xss
     * @return    array
     */
    public static function zip()
    {
        $xss = $zipped = $result = array();;
        $loop = false;

        foreach (func_get_args() as $xs) {
            $xss[] = $wrapped = static::_wrapIterator($xs);
            $wrapped->rewind();
            $loop = $loop || $wrapped->valid();
            $zipped[] = $wrapped->current();
        }

        while ($loop) {
            $result[] = $zipped;
            $zipped = array();
            $loop = false;
            foreach ($xss as $xs) {
                $xs->next();
                $zipped[] = $xs->current();
                $loop = $loop || $xs->valid();
            }
        }

        return $result;
    }

    /**
     * @category  Arrays
     * @param     array|Traversable  *$xss
     * @param     callable           $f
     * @return    array
     */
    public static function zipWith()
    {
        $xss = func_get_args();
        $f = array_pop($xss);
        $zipped = call_user_func_array(get_called_class().'::zip', $xss);
        return static::map($zipped, function($xs, $i, $xs) use ($f) {
            $xs[] = $i;
            $xs[] = $xs;
            return call_user_func_array($f, $xs);
        });
    }

    /**
     * Converts arrays into objects.
     *
     * @category  Arrays
     * @param     array|Traversable  $xs
     * @param     array|Traversable  $values
     * @return    array
     */
    public static function object($xs, $values = null)
    {
        $result = array();
        $values = static::_wrapIterator($values);
        if ($values !== null) {
            $values->rewind();
            foreach ($xs as $key) {
                if ($values->valid()) {
                    $result[$key] = $values->current();
                    $values->next();
                } else {
                    $result[$key] = null;
                }
            }
        } else {
            foreach ($xs as $x) {
                $result[$x[0]] = $x[1];
            }
        }
        return $result;
    }

    /**
     * Returns the index at which value can be found in the array, or -1 if
     * value is not present in the array.
     *
     * @category  Arrays
     * @param     array|Traversable  $xs
     * @param     mixed              $value
     * @param     boolean|int        $isSorted
     * @return    int
     */
    public static function indexOf($xs, $value, $isSorted = 0)
    {
        $xs = static::toArray($xs);

        if ($isSorted === true) {
            $i = static::sortedIndex($xs, $value);
            return (isset($xs[$i]) && $xs[$i] === $value) ? $i : -1;
        } else {
            $l = count($xs);
            $i = $isSorted < 0 ? max(0, $l + $isSorted) : $isSorted;
            for (; $i < $l; $i++) {
                if (isset($xs[$i]) && $xs[$i] === $value) {
                    return $i;
                }
            }
        }

        return -1;
    }

    /**
     * Returns the index of the last occurrence of value in the array, or -1 if
     * value is not present.
     *
     * @category  Arrays
     * @param     array|Traversable  $xs
     * @param     mixed              $x
     * @param     int                $fromIndex
     * @return    int
     */
    public static function lastIndexOf($xs, $x, $fromIndex = null)
    {
        $xs = static::toArray($xs);
        $l = count($xs);
        $i = $fromIndex !== null ? min($l, $fromIndex) : $l;

        while ($i-- > 0) {
            if (isset($xs[$i]) && $xs[$i] === $x) {
                return $i;
            }
        }

        return -1;
    }

    /**
     * Returns the index at which value can be found in the array, or -1 if
     * value is not present in the array.
     *
     * @category  Arrays
     * @param     array|Traversable  $xs
     * @param     mixed              $xs
     * @param     callable           $f
     * @return    int
     */
    public static function sortedIndex($xs, $x, $f = null)
    {
        $xs = static::toArray($xs);
        $f = static::_lookupIterator($f);
        $x = call_user_func($f, $x);

        $low = 0;
        $high = count($xs);

        while ($low < $high) {
            $mid = ($low + $high) >> 1;
            if (call_user_func($f, $xs[$mid]) < $x) {
                $low = $mid + 1;
            } else {
                $high = $mid;
            }
        }

        return $low;
    }

    /**
     * A function to create flexibly-numbered lists of integers,
     * handy for each and map loops.
     *
     * @category  Arrays
     * @param     int     $start
     * @param     int     $stop
     * @param     int     $step
     * @return    array
     */
    public static function range($start, $stop = null, $step = 1)
    {
        if ($stop === null) {
            $stop = $start;
            $start = 0;
        }

        $l = max(ceil(($stop - $start) / $step), 0);
        $range = array();

        for ($i = 0; $i < $l; $i++) {
            $range[] = $start;
            $start += $step;
        }

        return $range;
    }

    /**
     * @category  Arrays
     * @param     array|Traversable  $xs
     * @param     int                $n
     * @return    array
     * @throws    OverflowException
     */
    public static function cycle($xs, $n = null)
    {
        $result = array();
        if ($n !== null) {
            throw new \OverflowException();
        }
        while ($n-- > 0) {
            foreach ($xs as $x) {
                $result[] = $x;
            }
        }
        return $result;
    }

    /**
     * @category  Arrays
     * @param     mixed   $x
     * @param     int     $n
     * @return    array
     * @throws    OverflowException
     */
    public static function repeat($x, $n = -1)
    {
        if ($n < 0) {
            throw new \OverflowException();
        }
        return array_fill(0, $n, $x);
    }

    /**
     * @category  Arrays
     * @param     mixed     $acc
     * @param     callable  $f
     * @return    array
     * @throws    OverflowException
     */
    public static function iterate($acc, $f)
    {
        throw new \OverflowException();
    }

    /**
     * Removes the last element from an array and returns that element.
     *
     * @category  Arrays
     * @param     array|Traversable  $xs
     * @return    array
     */
    public static function pop($xs)
    {
        return static::initial($xs);
    }

    /**
     * Adds one or more elements to the end of an array and returns the new
     * length of the array.
     *
     * @category  Arrays
     * @param     array|Traversable  $xs
     * @param     mixed              *$values
     * @return    array
     */
    public static function push($xs)
    {
        $values = array_slice(func_get_args(), 1);
        return array_merge(static::toArray($xs), $values);
    }

    /**
     * Reverses the order of the elements of an array -- the first becomes the
     * last, and the last becomes the first.
     *
     * @category  Arrays
     * @param     array|Traversable  $xs
     * @return    array
     */
    public static function reverse($xs)
    {
        return array_reverse(static::toArray($xs, true), true);
    }

    /**
     * Removes the first element from an array and returns that element.
     *
     * @category  Arrays
     * @param     array|Traversable  $xs
     * @return    array
     */
    public static function shift($xs)
    {
        return static::rest($xs);
    }

    /**
     * @category  Arrays
     * @param     array|Traversable  $xs
     * @param     callable           $compare
     * @return    array
     */
    public static function sort($xs, $compare = null)
    {
        $xs = static::toArray($xs);
        is_callable($compare) ? usort($xs, $compare) : sort($xs);
        return $xs;
    }

    /**
     * @category  Arrays
     * @param     array|Traversable  $xs
     * @param     callable           $compare
     * @return    array
     */
    public static function sortByKey($xs, $compare = null)
    {
        $xs = static::toArray($xs);
        is_callable($compare) ? uksort($xs, $compare) : ksort($xs);
        return $xs;
    }

    /**
     * Removes the first element from an array and returns that element.
     *
     * @category  Arrays
     * @param     array|Traversable  $xs
     * @param     int                $index
     * @param     int                $n
     * @return    array
     */
    public static function splice($xs, $index, $n)
    {
        $rest = array_slice(func_get_args(), 3);
        return array_splice(static::toArray($xs), $index, $n, $rest);
    }

    /**
     * Adds one or more elements to the front of an array and returns the new
     * length of the array.
     *
     * @category  Arrays
     * @param     array|Traversable  $xs
     * @param     mixed              *$values
     * @return    array
     */
    public static function unshift($xs)
    {
        $values = array_slice(func_get_args(), 1);
        return array_merge($values, static::toArray($xs));
    }

    /**
     * Returns a new array comprised of this array joined with other array(s)
     * and/or value(s).
     *
     * @category  Arrays
     * @param     array|Traversable  *$xss
     * @return    array
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
     * @param     array|Traversable  $xs
     * @param     string             $separator
     * @return    string
     */
    public static function join($xs, $separator = ',')
    {
        $str = '';
        foreach ($xs as $x) {
            $str .= $x . $separator;
        }
        return substr($str, 0, -strlen($separator));
    }

    /**
     * Returns a shallow copy of a portion of an array.
     *
     * @category  Arrays
     * @param     array|Traversable  $xs
     * @param     int                $begin
     * @param     int                $end
     * @return    array
     */
    public static function slice($xs, $begin, $end = -1)
    {
        return array_slice(static::toArray($xs), $begin, $end);
    }

    /**
     * Retrieve all the names of the object's properties.
     *
     * @category  Objects
     * @param     array|Traversable  $object
     * @return    array
     */
    public static function keys($xs)
    {
        if (is_array($xs)) {
            return array_keys($xs);
        }
        return static::map($xs, function($x, $k) {
            return $k;
        });
    }

    /**
     * Return all of the values of the object's properties.
     *
     * @category  Objects
     * @param     array|Traversable  $xs
     * @return    array
     */
    public static function values($xs)
    {
        if (is_array($xs)) {
            return array_values($xs);
        }
        $i = 0;
        return static::mapKey($xs, function() use (&$i) {
            return $i++;
        });
    }

    /**
     * Convert an object into a list of [key, value] pairs.
     *
     * @category  Objects
     * @param     array|Traversable  $xs
     * @return    array
     */
    public static function pairs($xs)
    {
        return static::map($xs, function($x, $k) {
            return array($k, $x);
        });
    }

    /**
     * Returns a copy of the object where the keys have become the values and the
     * values the keys.
     *
     * @category  Objects
     * @param     array|Traversable  $xs
     * @return    array
     */
    public static function invert($xs)
    {
        $result = array();
        foreach ($xs as $k => $x) {
            $result[$x] = $k;
        }
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
        if ($destination instanceof \Traversable) {
            $destination = iterator_to_array($destination, true);
        }

        $sources = array_slice(func_get_args(), 1);
        foreach ($sources as $xs) {
            if (is_array($destination)) {
                foreach ($xs as $k => $x) {
                    $destination[$k] = $x;
                }
            } else {
                foreach ($xs as $k => $x) {
                    $destination->$k = $x;
                }
            }
        }

        return $destination;
    }

    /**
     * Return a copy of the object, filtered to only have values for the
     * whitelisted keys (or array of valid keys).
     *
     * @category  Objects
     * @param     array|Traversable         $xs
     * @param     array|string|Traversable  *$keys
     * @return    array
     */
    public static function pick($xs)
    {
        $whitelist = array();
        $keys = array_slice(func_get_args(), 1);

        foreach ($keys as $key) {
            if (static::_isTraversable($key)) {
                foreach ($key as $k) {
                    $whitelist[$k] = 0;
                }
            } else {
                $whitelist[$key] = 0;
            }
        }

        return static::filter($xs, function($x, $k) use ($whitelist) {
            return isset($whitelist[$k]);
        });
    }

    /**
     * Return a copy of the object, filtered to omit the blacklisted keys
     * (or array of keys).
     *
     * @category  Objects
     * @param     array|Traversable         $xs
     * @param     array|string|Traversable  *$keys
     * @return    array
     */
    public static function omit($xs)
    {
        $blacklist = array();
        $keys = array_slice(func_get_args(), 1);

        foreach ($keys as $key) {
            if (static::_isTraversable($key)) {
                foreach ($key as $k) {
                    $blacklist[$k] = 0;
                }
            } else {
                $blacklist[$key] = 0;
            }
        }

        return static::filter($xs, function($x, $k) use ($blacklist) {
            return !isset($blacklist[$k]);
        });
    }

    /**
     * Copy all of the properties in the source objects over to the destination
     * object, and return the destination object.
     *
     * @category  Objects
     * @param     array|Traversable  $xs
     * @param     array|Traversable  *$defaults
     * @return    array
     */
    public static function defaults($xs)
    {
        if ($xs instanceof \Traversable) {
            $xs = iterator_to_array($xs, true);
        }

        $defaults = array_slice(func_get_args(), 1);
        if (is_array($xs)) {
            foreach ($defaults as $default) {
                foreach ($default as $k => $x) {
                    if (!isset($xs[$k])) {
                        $xs[$k] = $x;
                    }
                }
            }
        } else {
            foreach ($defaults as $default) {
                foreach ($default as $k => $x) {
                    if (!isset($xs->$k)) {
                        $xs->$k = $x;
                    }
                }
            }
        }

        return $xs;
    }

    /**
     * Invokes interceptor with the object, and then returns object.
     *
     * @category  Objects
     * @param     mixed     $value
     * @param     callable  $interceptor
     * @return    mixed
     */
    public static function tap($value, $interceptor)
    {
        call_user_func($interceptor, $value);
        return $value;
    }

    /**
     * Create a shallow-copied clone of the object. Any nested objects or arrays
     * will be copied by reference, not duplicated.
     *
     * @category  Objects
     * @param     mixed  $xs
     * @return    mixed
     */
    public static function duplicate($xs)
    {
        return is_object($xs) ? clone $xs : $xs;
    }

    /**
     * Does the object contain the given key?
     *
     * @category  Objects
     * @param     array|object|Traversable  $xs
     * @param     int|string                $key
     * @return    boolean
     */
    public static function has($xs, $key)
    {
        if (is_array($xs)) {
            return isset($xs[$key]);
        }
        if ($xs instanceof \Traversable) {
            foreach ($xs as $k => $_) {
                if ($k === $key) {
                    return true;
                }
            }
            return false;
        }

        // given a object
        return isset($xs->$key);
    }

    /**
     * Returns the same value that is used as the argument.
     *
     * @category  Utility
     * @param     mixed  $value
     * @return    mixed
     */
    public static function identity($value)
    {
        return $value;
    }

    /**
     * @category  Utility
     * @param     array|callable  $f
     * @param     mixed           *$args
     * @return    float
     */
    public static function bench($f)
    {
        $args = array_slice(func_get_args(), 1);
        $start = microtime(true);
        call_user_func_array($f, $args);
        return microtime(true) - $start;
    }

    /**
     * Returns a wrapped object. Calling methods on this object will continue to
     * return wrapped objects until value is used.
     *
     * @category  Chaining
     * @param     mixed    $value
     * @return    Wrapper
     */
    public static function chain($value)
    {
        return new Wrapper($value, get_called_class());
    }

    protected static function _lookupIterator($value)
    {
        if (is_callable($value)) {
            return $value;
        }
        if (is_scalar($value)) {
            return function($xs) use ($value) {
                return is_array($xs) ? $xs[$value] : $xs->$value;
            };
        }
        return get_called_class().'::identity';
    }

    protected static function _wrapIterator($xs)
    {
        if (is_array($xs)) {
            return new \ArrayIterator($xs);
        }
        if ($xs instanceof \Iterator) {
            return $xs;
        }
        if ($xs instanceof \Traversable) {
            return new \IteratorIterator($xs);
        }
        return $xs;
    }

    protected static function _isTraversable($xs)
    {
        return is_array($xs) || $xs instanceof \Traversable;
    }

    protected static function _toTraversable($xs)
    {
        return static::_isTraversable($xs) ? $xs : (array) $xs;
    }
}

// __END__
// vim: expandtab softtabstop=4 shiftwidth=4
