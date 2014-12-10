<?php

namespace Underbar\Selector;

use Underbar\Util\Singleton;

/**
 * Represents the Identity function.
 */
class KeySelector
{
    use Singleton;

    private function __construct()
    {
    }

    /**
     * Returns the given key as it is.
     *
     * @param mixed $value
     * @param mixed $key
     * @param mixed $source
     * @return mixed
     */
    public function __invoke($value, $key, $source)
    {
        return $key;
    }
}
