<?php

namespace Underbar\Comparer;

use Underbar\Util\Singleton;

class DefaultComparerResolver implements ComparerResolver
{
    use Singleton;

    private function __construct()
    {
    }

    public function resolve($value)
    {
        if ($value === null) {
            return function($x, $y) {
                if (is_string($x) && is_string($y)) {
                    return strcmp($x, $y);
                }
                if (is_numeric($x) && is_numeric($y)) {
                    if ($x == $y) return 0;
                    return ($x < $y) ? -1 : 1;
                }
                return 0;
            };
        }
        if (is_callable($value)) {
            return $value;
        }

        $type = gettype($value);
        throw new \InvalidArgumentException("Invalid comparer, got '$type'.");
    }
}
