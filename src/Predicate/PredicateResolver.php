<?php

namespace Underbar\Predicate;

use Underbar\Selector\ValueSelector;
use Underbar\Util\Singleton;

class PredicateResolver implements IPredicateResolver
{
    use Singleton;

    private function __construct()
    {
    }

    public function resolvePredicate($src)
    {
        if ($src === null) {
            return ValueSelector::getInstance();
        }
        if (is_string($src)) {
            // key or property selector
            return function($v) use ($src) {
                return is_array($v) ? $v[$src] : $v->$src;
            };
        }
        if (is_callable($src)) {
            return $src;
        }

        $type = gettype($src);
        throw new \InvalidArgumentException("Invalid predicate, got '$type'.");
    }
}
