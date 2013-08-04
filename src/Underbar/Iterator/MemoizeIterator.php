<?php
/**
 * This file is part of the Underbar.php package.
 *
 * Copyright (C) 2013 Shota Nozaki <emonkak@gmail.com>
 *
 * Licensed under the MIT License
 */

namespace Underbar\Iterator;

class MemoizeIterator implements \Iterator
{
    private $it;
    private $index;
    private $cachedElements = array();
    private $cachedKeys = array();
    private $cacheSize = 0;

    public function __construct(\Iterator $it)
    {
        $this->it = $it;
    }

    public function current()
    {
        return $this->cachedElements[$this->index];
    }

    public function key()
    {
        return $this->cachedKeys[$this->index];
    }

    public function next()
    {
        $this->index++;
        if ($this->cacheSize === $this->index) {
            $this->it->next();
            $this->memo();
        }
    }

    public function rewind()
    {
        $this->index = 0;
        if ($this->cacheSize === 0) {
            $this->it->rewind();
            $this->memo();
        }
    }

    public function valid()
    {
        return $this->index < $this->cacheSize;
    }

    private function memo()
    {
        if (!$this->it->valid()) {
            return;
        }

        $this->cachedElements[] = $this->it->current();
        $this->cachedKeys[] = $this->it->current();
        $this->cacheSize++;
    }
}
