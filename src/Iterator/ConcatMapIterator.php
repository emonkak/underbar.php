<?php
/**
 * This file is part of the Underbar.php package.
 *
 * Copyright (C) 2013 Shota Nozaki <emonkak@gmail.com>
 *
 * Licensed under the MIT License
 */

namespace Underbar\Iterator;

use Underbar\Core\Iterators;

class ConcatMapIterator extends \IteratorIterator implements \RecursiveIterator
{
    private $valueSelector;

    private $keySelector;

    private $key;

    private $children;

    public function __construct(\Iterator $it, callable $valueSelector, callbale $keySelector)
    {
        parent::__construct($it);
        $this->valueSelector = $valueSelector;
        $this->keySelector = $keySelector;
    }

    public function getChildren()
    {
        $it = Iterators::create($this->children);
        return new NonRecursiveIterator($it);
    }

    public function hasChildren()
    {
        return true;
    }

    public function current()
    {
        return $this->it->current();
    }

    public function key()
    {
        return $this->key;
    }

    public function next()
    {
        $this->it->next();
        $this->fetch();
    }

    public function rewind()
    {
        $this->it->rewind();
        $this->fetch();
    }

    public function valid()
    {
        return $this->it->valid();
    }

    private function fetch()
    {
        if ($this->it->valid()) {
            $current = $this->it->current();
            $key = $this->it->key();
            $this->key = call_user_func(
                $this->keySelector,
                $current,
                $key,
                $this->it
            );
            $this->children = call_user_func(
                $this->valueSelector,
                $current,
                $key,
                $this->it
            );
        }
    }
}
