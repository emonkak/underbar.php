<?php

namespace Underbar\Util;

use Underbar\Iterator\LazyIterator;
use Underbar\Iterator\MemoizeIterator;

class Iterators
{
    private function __construct()
    {
    }

    public static function create($source)
    {
        if ($source instanceof \Iterator) {
            return $source;
        }
        if ($source instanceof \Traversable) {
            return new \IteratorIterator($source);
        }
        if (is_array($source)) {
            return new \ArrayIterator($source);
        }

        $type = gettype($source);
        throw new \InvalidArgumentException("'$type' is not iterable.");
    }

    public static function createLazy($factory)
    {
        return new LazyIterator($factory);
    }

    public static function memoize($source)
    {
        return new MemoizeIterator(self::create($source));
    }

    public static function toArray($source)
    {
        if ($source instanceof \ArrayIterator) {
            return $source->getArrayCopy();
        }
        if ($source instanceof \Traversable) {
            return iterator_to_array($source, true);
        }
        if (is_array($source)) {
            return $source;
        }
        $type = gettype($source);
        throw new \InvalidArgumentException("'$type' can not convert to array.");
    }

    public static function toArrayRec($source, $depth)
    {
        if ($depth === 1) {
            return self::toArray($it);
        } else {
            $acc = array();
            foreach ($source as $k => $v) {
                if ($v instanceof \Traversable) {
                    $acc[$k] = self::toArrayRec($v, $depth - 1);
                } else {
                    $acc[$k] = $v;
                }
            }
            return $acc;
        }
    }

    public static function toList($source)
    {
        if ($source instanceof \ArrayIterator) {
            return array_values($source->getArrayCopy());
        }
        if ($source instanceof \Traversable) {
            return iterator_to_array($source, false);
        }
        if (is_array($source)) {
            return array_values($source);
        }
        $type = gettype($source);
        throw new \InvalidArgumentException("'$type' can not convert to array.");
    }

    public static function toListRec($source, $depth)
    {
        if ($depth === 1) {
            return self::toList($source);
        } else {
            $acc = array();
            foreach ($source as $v) {
                if ($value instanceof \Traversable) {
                    $acc[] = self::toListRec($v, $depth - 1);
                } else {
                    $acc[] = $v;
                }
            }
            return $acc;
        }
    }

    public static function count($source)
    {
        return $source instanceof \Traversable ? iterator_count($source) : count($source);
    }

    public static function isTraversable($value)
    {
        return is_array($value) || $value instanceof \Traversable;
    }
}
