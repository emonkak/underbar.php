<?php

namespace Underbar\Selector;

use Underbar\Util\Singleton;

class KeySelectorResolver implements IKeySelectorResolver
{
    use Singleton;

    private function __construct()
    {
    }

    public function resolveKeySelector($src)
    {
        if ($src === null) {
            return KeySelector::getInstance();
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
        throw new \InvalidArgumentException("Invalid selector, got '$type'.");
    }
}
