<?php

namespace Underbar\Internal;

class RewindableGenerator implements \IteratorAggregate
{
    private $generator;

    public function __construct(\Generator $generator)
    {
        $this->generator = $generator;
    }

    public function getIterator()
    {
        return clone $this->generator;
    }
}
