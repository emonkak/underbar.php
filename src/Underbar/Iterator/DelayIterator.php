<?php
/**
 * This file is part of the Underbar.php package.
 *
 * Copyright (C) 2013 Shota Nozaki <emonkak@gmail.com>
 *
 * Licensed under the MIT License
 */

namespace Underbar\Iterator;

class DelayIterator implements \Iterator
{
    private $f;
    private $it;

    public function __construct($f)
    {
        $this->f = $f;
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
    }

    public function rewind()
    {
        $this->it = call_user_func($this->f);
        $this->it->rewind();
    }

    public function valid()
    {
        return $this->it->valid();
    }
}
