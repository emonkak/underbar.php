<?php

namespace Underbar\Internal;

class RewindableGenerator implements \IteratorAggregate
{
    private $func;

    private $args;

    public function __construct($func, $args)
    {
        $this->func = $func;
        $this->args = $args;
    }

    public function getIterator()
    {
        return call_user_func_array($this->func, $this->args);
    }
}
