<?php
/**
 * This file is part of the Underbar.php package.
 *
 * Copyright (C) 2013 Shota Nozaki <emonkak@gmail.com>
 *
 * Licensed under the MIT License
 */

namespace Underbar\Iterator;

class FlattenIterator implements \RecursiveIterator
{
    private $it;
    private $depth;
    private $children;

    public function __construct(\Iterator $it, $depth)
    {
        $this->it = $it;
        $this->depth = $depth;
    }

    public function getChildren()
    {
        $current = $this->it->current();
        $it = is_array($current) ? new \ArrayIterator($current) : $current;
        return new self($it, $this->depth - 1);
    }

    public function hasChildren()
    {
        if ($this->depth === 0) {
            return false;
        }
        $current = $this->it->current();
        return is_array($current) || $current instanceof \Traversable;
    }

    public function current()
    {
        if ($this->children) {
            return $this->children->current();
        } else {
            return $this->it->current();
        }
    }

    public function key()
    {
        if ($this->children) {
            return $this->children->key();
        } else {
            return $this->it->key();
        }
    }

    public function next()
    {
        if ($this->children) {
            $this->children->next();
        } else {
            $this->it->next();
            $this->fetchChildren();
        }
    }

    public function rewind()
    {
        if ($this->children) {
            $this->children->rewind();
        } else {
            $this->it->rewind();
            $this->fetchChildren();
        }
    }

    public function valid()
    {
        if ($this->children) {
            if ($this->children->valid()) {
                return true;
            }

            $this->it->next();
            $this->fetchChildren();
        }

        return $this->it->valid();
    }

    private function fetchChildren()
    {
        if (!$this->hasChildren()) {
            $this->children = null;
            return;
        }

        $this->children = $this->getChildren();
        $this->children->rewind();
    }
}
