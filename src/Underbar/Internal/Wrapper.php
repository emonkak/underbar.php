<?php
/**
 * This file is part of the Underbar.php package.
 *
 * Copyright (C) 2013 Shota Nozaki <emonkak@gmail.com>
 *
 * Licensed under the MIT License
 */

namespace Underbar\Internal;

class Wrapper implements \ArrayAccess, \Countable, \IteratorAggregate
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

    public function strict()
    {
        $this->class = 'Underbar\\Strict';
        return $this;
    }

    public function lazy()
    {
        $this->class = 'Underbar\\Lazy';
        return $this;
    }

    public function count()
    {
        return call_user_func($this->class.'::size', $this->value);
    }

    public function getIterator()
    {
        if ($this->value instanceof \Traversable) {
            return $this->value;
        } else {
            return new \ArrayIterator((array) $this->value);
        }
    }

    public function offsetExists($offset)
    {
        foreach ($this->value as $k => $x) {
            if ($k === $offset) {
                return true;
            }
        }

        return false;
    }

    public function offsetGet($offset)
    {
        foreach ($this->value as $k => $x) {
            if ($k === $offset) {
                return $x;
            }
        }
    }

    public function offsetSet($offset, $value)
    {
        throw new \BadMethodCallException('Not implemented');
    }

    public function offsetUnset($offset)
    {
        throw new \BadMethodCallException('Not implemented');
    }
}
