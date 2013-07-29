<?php
/**
 * This file is part of the Underbar.php package.
 *
 * Copyright (C) 2013 Shota Nozaki <emonkak@gmail.com>
 *
 * Licensed under the MIT License
 */

namespace Underbar\Iterator;

class CycleIterator implements \Iterator
{
    private $it;
    private $n;
    private $remain;

    public function __construct(\Iterator $it, $n = -1)
    {
        $this->it = $it;
        $this->n = $n;
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
        $this->it->next();
        if (!$this->it->valid()) {
            $this->remain--;
            $this->it->rewind();
        }
    }

    public function rewind()
    {
        $this->remain = $this->n;
        $this->it->rewind();
    }

    public function valid()
    {
        return $this->it->valid() && $this->remain !== 0;
    }
}
