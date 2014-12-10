<?php
/**
 * This file is part of the Underbar.php package.
 *
 * Copyright (C) 2013 Shota Nozaki <emonkak@gmail.com>
 *
 * Licensed under the MIT License
 */

namespace Underbar\Iterator;

use Underbar\Util\Iterators;

class ConcatMapIterator implements \RecursiveIterator
{
    private $it;

    private $selector;

    private $key;

    private $current;

    private $children;

    public function __construct(\Iterator $it, callable $selector)
    {
        $this->it = $it;
        $this->selector = $selector;
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
        return $this->current;
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
            $this->key = $this->it->key();
            $this->current = $this->it->current();
            $this->children = call_user_func(
                $this->selector,
                $this->current,
                $this->key,
                $this->it
            );
        }
    }
}
