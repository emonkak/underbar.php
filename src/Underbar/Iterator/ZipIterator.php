<?php
/**
 * This file is part of the Underbar.php package.
 *
 * Copyright (C) 2013 Shota Nozaki <emonkak@gmail.com>
 *
 * Licensed under the MIT License
 */

namespace Underbar\Iterator;

class ZipIterator implements \Iterator
{
    private $iterators = array();
    private $current;
    private $index;

    public function attachIterator(\Iterator $it)
    {
        $hash = spl_object_hash($it);
        $this->iterators[$hash] = $it;
    }

    public function detachIterator(\Iterator $it)
    {
        $hash = spl_object_hash($it);
        unset($this->iterators[$hash]);
    }

    public function countIterator()
    {
        return count($this->iterators);
    }

    public function current()
    {
        return $this->current;
    }

    public function key()
    {
        return $this->index;
    }

    public function next()
    {
        $this->index++;
        $this->current = array();
        foreach ($this->iterators as $it) {
            $it->next();
            $this->current[] = $it->current();
        }
    }

    public function rewind()
    {
        $this->index = 0;
        $this->current = array();
        foreach ($this->iterators as $it) {
            $it->rewind();
            $this->current[] = $it->current();
        }
    }

    public function valid()
    {
        $isValidAll = !empty($this->iterators);
        foreach ($this->iterators as $it) {
            $isValidAll = $isValidAll && $it->valid();
        }
        return $isValidAll;
    }
}
