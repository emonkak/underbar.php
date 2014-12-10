<?php

namespace Underbar\Comparer;

use Underbar\Util\Singleton;

class ComparerResolver implements IComparerResolver
{
    use Singleton;

    private function __construct()
    {
    }

    public function resolveComparer($src)
    {
        if ($src === null) {
            return function($v0, $v1) {
                if (is_string($v0) && is_string($v1)) {
                    return strcmp($v0, $v1);
                }
                if (is_numeric($v0) && is_numeric($v1)) {
                    if ($v0 == $v1) return 0;
                    return ($v0 < $v1) ? -1 : 1;
                }
                return 0;
            };
        }
        if (is_callable($src)) {
            return $src;
        }

        $type = gettype($src);
        throw new \InvalidArgumentException("Invalid comparer, got '$type'.");
    }
}
