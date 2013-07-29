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
        return $this->children;
    }

    public function hasChildren()
    {
        return !!$this->children;
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
            if ($this->children->valid()) {
                return;
            }
        }

        $this->it->next();
        $this->fetchNext();
    }

    public function rewind()
    {
        $this->it->rewind();
        $this->fetchNext();
    }

    public function valid()
    {
        if ($this->children) {
            return $this->children->valid();
        } else {
            return $this->it->valid();
        }
    }

    private function fetchNext()
    {
        while ($this->it->valid()) {
            if ($this->fetchChildren()) {
                return;
            }
            $this->it->next();
        }
    }

    private function fetchChildren()
    {
        $current = $this->it->current();

        if ($this->depth !== 0) {
            if (is_array($current)) {
                $this->children = new self(new \ArrayIterator($current), $this->depth - 1);
                $this->children->rewind();
                return $this->children->valid();
            } elseif ($current instanceof \Traversable) {
                $this->children = new self($current, $this->depth - 1);
                $this->children->rewind();
                return $this->children->valid();
            }
        }

        $this->children = null;
        return true;
    }
}
