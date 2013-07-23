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

    private $class;

    public function __construct($value, $class)
    {
        $this->value = $value;
        $this->class = $class;
    }

    public function __call($name, $aruguments)
    {
        array_unshift($aruguments, $this->value);
        $value = call_user_func_array($this->class.'::'.$name, $aruguments);
        return new static($value, $this->class);
    }

    public function value()
    {
        return $this->value;
    }

    public function eager()
    {
        $this->class = 'Underbar\\Eager';
        return $this;
    }

    public function lazy()
    {
        $this->class = 'Underbar\\Lazy';
        return $this;
    }

    public function getIterator()
    {
        if ($this->value instanceof \Traversable) {
            return $this->value;
        } else {
            return new \ArrayIterator((array) $this->value);
        }
    }
}
