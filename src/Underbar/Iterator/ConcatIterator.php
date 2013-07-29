<?php
/**
 * This file is part of the Underbar.php package.
 *
 * Copyright (C) 2013 Shota Nozaki <emonkak@gmail.com>
 *
 * Licensed under the MIT License
 */

namespace Underbar\Iterator;

class ConcatIterator implements \Iterator
{
    private $iterators = array();
    private $it;

    public function append(\Iterator $it)
    {
        $this->iterators[] = $it;
    }

    public function current()
    {
        return $this->it->current();
    }

    public function key()
    {
        return $this->it->key();
    }

    public function next()
    {
        if ($this->it) {
            $this->it->next();
            if (!$this->it->valid()) {
                $this->it = null;
                $this->next();
            }
        } else {
            $this->it = next($this->iterators);
            if ($this->it && !$this->it->valid()) {
                $this->it = null;
                $this->next();
            }
        }
    }

    public function rewind()
    {
        reset($this->iterators);
        $this->it = current($this->iterators);
        if ($this->it) {
            $this->it->rewind();
        }
    }

    public function valid()
    {
        return $this->it && $this->it->valid();
    }
}
