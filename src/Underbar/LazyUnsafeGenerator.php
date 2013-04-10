<?php

namespace Underbar;

abstract class LazyUnsafeGenerator extends Strict
{
    /**
     * @category  Collections
     * @param     array|Traversable  $xs
     * @param     callable           $f
     * @return    Generator
     */
    public static function map($xs, $f)
    {
        foreach ($xs as $i => $x) {
            yield $i => call_user_func($f, $x, $i, $xs);
        }
    }

    /**
     * @category  Collections
     * @param     array|Traversable  $xs
     * @param     callable           $f
     * @return    Generator
     */
    public static function filter($xs, $f)
    {
        foreach ($xs as $i => $x) {
            if (call_user_func($f, $x, $i, $xs)) {
                yield $i => $x;
            }
        }
    }

    /**
     * @category  Collections
     * @param     array|Traversable  $xs
     * @param     callable|string    $f
     * @param     bool               $isSorted
     * @return    Generator
     */
    public static function groupBy($xs, $f = null, $isSorted = false)
    {
        return $isSorted ? static::_groupBy($xs, $f) : parent::groupBy($xs, $f);
    }

    public static function _groupBy($xs, $f = null)
    {
        $f = static::_lookupIterator($f);
        $acc = array();
        $lastKey = null;

        foreach ($xs as $i => $x) {
            if (($key = call_user_func($f, $x, $i, $xs)) !== $lastKey) {
                if (!empty($acc)) {
                    yield $lastKey => $acc;
                    $acc = array();
                }
                $lastKey = $key;
            }
            $acc[] = $x;
        }

        if (!empty($acc)) {
            yield $lastKey => $acc;
        }
    }

    /**
     * @category  Collections
     * @param     array|Traversable  $xs
     * @param     callable|string    $f
     * @return    int
     */
    public static function countBy($xs, $f = null, $isSorted = false)
    {
        return $isSorted ? static::_countBy($xs, $f) : parent::countBy($xs, $f);
    }

    public static function _countBy($xs, $f = null)
    {
        $f = static::_lookupIterator($f);
        $acc = 0;
        $lastKey = null;

        foreach ($xs as $i => $x) {
            if (($key = call_user_func($f, $x, $i, $xs)) !== $lastKey) {
                if ($acc !== 0) {
                    yield $lastKey => $acc;
                    $acc = 0;
                }
                $lastKey = $key;
            }
            $acc++;
        }

        if ($acc !== 0) {
            yield $lastKey => $acc;
        }
    }

    /**
     * @category  Arrays
     * @param     array|Traversable  $xs
     * @param     int                $n
     * @return    Generator
     */
    public static function _first($xs, $n)
    {
        foreach ($xs as $i => $x) {
            if (--$n < 0) {
                break;
            }
            yield $i => $x;
        }
    }

    /**
     * @category  Arrays
     * @param     array|Traversable  $xs
     * @param     callable           $f
     * @return    Generator
     */
    public static function takeWhile($xs, $f)
    {
        foreach ($xs as $i => $x) {
            if (!call_user_func($f, $x, $i, $xs)) {
                break;
            }
            yield $i => $x;
        }
    }

    /**
     * @category  Arrays
     * @param     array|Traversable  $xs
     * @param     int                $n
     * @return    Generator
     */
    public static function initial($xs, $n = 1, $guard = null)
    {
        $queue = new \SplQueue();
        if ($guard !== null) {
            $n = 1;
        }
        foreach ($xs as $x) {
            $queue->enqueue($x);
            if ($n > 0) {
                $n--;
            } else {
                yield $queue->dequeue();
            }
        }
    }

    /**
     * @category  Arrays
     * @param     array|Traversable  $xs
     * @param     int                $n
     * @return    Generator
     */
    public static function rest($xs, $n = 1, $guard = null)
    {
        if ($guard !== null) {
            $n = 1;
        }
        foreach ($xs as $i => $v) {
            if ($n > 0) {
                $n--;
            } else {
                yield $i => $v;
            }
        }
    }

    /**
     * @category  Arrays
     * @param     array|Traversable  $xs
     * @param     callable           $f
     * @return    mixed|Generator
     */
    public static function dropWhile($xs, $f)
    {
        $accepted = false;
        foreach ($xs as $i => $x) {
            if ($accepted || ($accepted = !call_user_func($f, $x, $i, $xs))) {
                yield $i => $x;
            }
        }
    }

    /**
     * @category  Arrays
     * @param     array|Traversable  *$xss
     * @return    Generator
     */
    public static function zip()
    {
        $yss = $zss = array();
        $loop = true;

        foreach (func_get_args() as $xs) {
            $yss[] = $ys = static::_wrapIterator($xs);
            $ys->rewind();
            $loop = $loop && $ys->valid();
            $zss[] = $ys->current();
        }

        while ($loop) {
            yield $zss;
            $zss = array();
            $loop = true;
            foreach ($yss as $ys) {
                $ys->next();
                $zss[] = $ys->current();
                $loop = $loop && $ys->valid();
            }
        }
    }

    /**
     * @category  Arrays
     * @param     array|Traversable  $xss
     * @param     bool               $shallow
     * @return    Generator
     */
    public static function flatten($xss, $shallow = false)
    {
        foreach ($xss as $xs) {
            if (static::isTraversable($xs)) {
                if ($shallow) {
                    foreach ($xs as $x) {
                        yield $x;
                    }
                } else {
                    foreach (static::flatten($xs, $shallow) as $x) {
                        yield $x;
                    }
                }
            } else {
                yield $xs;
            }
        }
    }

    /**
     * @category  Arrays
     * @param     int       $start
     * @param     int       $stop
     * @param     int       $step
     * @return    Generator
     */
    public static function range($start, $stop = null, $step = 1)
    {
        if ($stop === null) {
            $stop = $start;
            $start = 0;
        }

        $len = max(ceil(($stop - $start) / $step), 0);
        for ($i = 0; $i < $len; $i++) {
            yield $start;
            $start += $step;
        }
    }

    /**
     * @category  Arrays
     * @param     array|Traversable  $array
     * @return    Generator
     */
    public static function cycle($array, $n = null)
    {
        if ($n === null) {
            while (true) {
                foreach ($array as $value) {
                    yield $value;
                }
            }
        } else {
            while ($n-- > 0) {
                foreach ($array as $value) {
                    yield $value;
                }
            }
        }
    }

    /**
     * @category  Arrays
     * @param     mixed     $value
     * @param     int       $n
     * @return    Generator
     */
    public static function repeat($value, $n = -1)
    {
        while ($n--) {
            yield $value;
        }
    }

    /**
     * @category  Arrays
     * @param     mixed              $acc
     * @param     callable           $f
     * @return    Generator
     * @throws    OverflowException
     */
    public static function iterate($acc, $f)
    {
        while (true) {
            yield $acc;
            $acc = call_user_func($f, $acc);
        }
    }

    /**
     * @category  Arrays
     * @param     array|Traversable  *$xss
     * @return    Generator
     */
    public static function concat()
    {
        foreach (func_get_args() as $xs) {
            foreach ($xs as $i => $x) {
                yield $i => $x;
            }
        }
    }
}

// __END__
// vim: expandtab softtabstop=4 shiftwidth=4
