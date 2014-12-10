<?php

namespace Underbar\Util;

use Underbar\Iterator\LazyIterator;
use Underbar\Iterator\MemoizeIterator;

class Iterators
{
    private function __construct()
    {
    }

    public static function create($src)
    {
        if ($src instanceof \Iterator) {
            return $src;
        }
        if ($src instanceof \Traversable) {
            return new \IteratorIterator($src);
        }
        if (is_array($src)) {
            return new \ArrayIterator($src);
        }

        $type = gettype($src);
        throw new \InvalidArgumentException("'$type' is not iterable.");
    }

    public static function createLazy($factory)
    {
        return new LazyIterator($factory);
    }

    public static function memoize($src)
    {
        return new MemoizeIterator(self::create($src));
    }

    public static function toArray($src)
    {
        if ($src instanceof \ArrayIterator) {
            return $src->getArrayCopy();
        }
        if ($src instanceof \Traversable) {
            return iterator_to_array($src, true);
        }
        if (is_array($src)) {
            return $src;
        }
        $type = gettype($src);
        throw new \InvalidArgumentException("'$type' can not convert to array.");
    }

    public static function toArrayRec($src, $depth)
    {
        if ($depth === 1) {
            return self::toArray($it);
        } else {
            $acc = [];
            foreach ($src as $k => $v) {
                if ($v instanceof \Traversable) {
                    $acc[$k] = self::toArrayRec($v, $depth - 1);
                } else {
                    $acc[$k] = $v;
                }
            }
            return $acc;
        }
    }

    public static function toList($src)
    {
        if (is_array($src)) {
            return array_values($src);
        }
        if ($src instanceof \ArrayIterator) {
            return array_values($src->getArrayCopy());
        }
        if ($src instanceof \Traversable) {
            return iterator_to_array($src, false);
        }
        $type = gettype($src);
        throw new \InvalidArgumentException("'$type' can not convert to array.");
    }

    public static function toListRec($src, $depth)
    {
        if ($depth === 1) {
            return self::toList($src);
        } else {
            $acc = [];
            foreach ($src as $v) {
                if ($v instanceof \Traversable) {
                    $acc[] = self::toListRec($v, $depth - 1);
                } else {
                    $acc[] = $v;
                }
            }
            return $acc;
        }
    }

    public static function count($src)
    {
        if (is_array($src)) {
            return count($src);
        }
        while ($src instanceof \IteratorAggregate) {
            $src = $src->getIterator();
        }
        if ($src instanceof \Countable) {
            return count($src);
        }
        if ($src instanceof \Traversable) {
            return iterator_count($src);
        }
        $type = gettype($src);
        throw new \InvalidArgumentException("'$type' is not countable.");
    }

    public static function isTraversable($src)
    {
        return is_array($src) || $src instanceof \Traversable;
    }

    public static function isEmpty($src)
    {
        if (is_array($src)) {
            return empty($src);
        }
        while ($src instanceof \IteratorAggregate) {
            $src = $src->getIterator();
        }
        if ($src instanceof \Countable) {
            return count($src) === 0;
        }
        if ($src instanceof \Iterator) {
            $src->rewind();
            return !$src->valid();
        }
        if ($src instanceof \Traversable) {
            foreach ($src as $v) {
                return true;
            }
            return false;
        }
        $type = gettype($src);
        throw new \InvalidArgumentException("'$type' is not countable.");
    }
}
