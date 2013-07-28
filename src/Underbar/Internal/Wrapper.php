<?php
/**
 * This file is part of the Underbar.php package.
 *
 * Copyright (C) 2013 Shota Nozaki <emonkak@gmail.com>
 *
 * Licensed under the MIT License
 */

namespace Underbar\Internal;

use Underbar\Enumerable;

class Wrapper implements \IteratorAggregate
{
    use Enumerable;

    private $value;

    private $impl;

    public function __construct($value, $impl)
    {
        $this->value = $value;
        $this->impl = $impl;
    }

    public function getUnderbarImpl()
    {
        return $this->impl;
    }

    public function getCollection()
    {
        return $this->value;
    }

    public function getIterator()
    {
        if ($this->value instanceof \Traversable) {
            return $this->value;
        } elseif (is_array($this->value)) {
            return new \ArrayIterator($this->value);
        } else {
            return new \EmptyIterator();
        }
    }

    public function value()
    {
        return $this->value;
    }
}
