<?php

namespace Underbar\Predicate;

use Underbar\Selector\ValueSelector;
use Underbar\Util\Singleton;

class DefaultPredicateResolver implements PredicateResolver
{
    use Singleton;

    private function __construct()
    {
    }

    public function resolvePredicate($source)
    {
        if ($source === null) {
            return ValueSelector::getInstance();
        }
        if (is_string($source)) {
            // key or property selector
            return function($value) use ($source) {
                return is_array($value) ? $value[$source] : $value->$source;
            };
        }
        if (is_callable($source)) {
            return $source;
        }

        $type = gettype($source);
        throw new \InvalidArgumentException("Invalid predicate, got '$type'.");
    }
}
