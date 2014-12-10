<?php

namespace Underbar\Comparer;

use Underbar\Util\Singleton;

class EqualityComparer implements IEqualityComparer
{
    use Singleton;

    private function __construct()
    {
    }

    public function equals($v0, $v1)
    {
        if (is_object($v0) && is_object($v1)) {
            return $v0 == $v1;
        }
        return $v0 === $v1;
    }

    public function hash($v)
    {
        return sha1(serialize($v));
    }
}
