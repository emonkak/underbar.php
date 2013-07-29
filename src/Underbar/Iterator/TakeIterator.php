<?php
/**
 * This file is part of the Underbar.php package.
 *
 * Copyright (C) 2013 Shota Nozaki <emonkak@gmail.com>
 *
 * Licensed under the MIT License
 */

namespace Underbar\Iterator;

class TakeIterator implements \Iterator
{
    private $it;
    private $n;
    private $index;

    public function __construct(\Iterator $it, $n)
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
        $this->index++;
        $this->it->next();
    }

    public function rewind()
    {
        $this->index = 0;
        $this->it->rewind();
    }

    public function valid()
    {
        return $this->it->valid() && $this->index < $this->n;
    }
}
