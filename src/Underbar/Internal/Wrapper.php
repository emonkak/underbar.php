<?php
/**
 * This file is part of the Underbar.php package.
 *
 * Copyright (C) 2013 Shota Nozaki <emonkak@gmail.com>
 *
 * Licensed under the MIT License
 */

namespace Underbar\Internal;

class Wrapper implements \IteratorAggregate
{
    private $value;

    private $impl;

    public function __construct($value, $impl)
    {
        $this->value = $value;
        $this->impl = $impl;
    }

    public function __call($name, $aruguments)
    {
        array_unshift($aruguments, $this->value);
        $value = call_user_func_array($this->impl.'::'.$name, $aruguments);
        return new static($value, $this->impl);
    }

    public function value()
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
}
