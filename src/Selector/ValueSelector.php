<?php

namespace Underbar\Selector;

use Underbar\Util\Singleton;

/**
 * Represents the Identity function.
 */
class ValueSelector
{
    use Singleton;

    private function __construct()
    {
    }

    /**
     * Returns the given value as it is.
     *
     * @param mixed $value
     * @param mixed $key
     * @param mixed $source
     * @return mixed
     */
    public function __invoke($value, $key, $source)
    {
        return $value;
    }
}
