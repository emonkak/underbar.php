<?php
/**
 * This file is part of the Underbar.php package.
 *
 * Copyright (C) 2013 Shota Nozaki <emonkak@gmail.com>
 *
 * Licensed under the MIT License
 */

namespace Underbar\Iterator;

class DropIterator implements \Iterator
{
    private $it;
    private $n;

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
        $this->it->next();
    }

    public function rewind()
    {
        $this->it->rewind();
        $n = $this->n;
        while ($this->it->valid()) {
            if ($n-- > 0) {
                $this->it->next();
            } else {
                break;
            }
        }
    }

    public function valid()
    {
        return $this->it->valid();
    }
}
