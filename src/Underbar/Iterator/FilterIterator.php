<?php
/**
 * This file is part of the Underbar.php package.
 *
 * Copyright (C) 2013 Shota Nozaki <emonkak@gmail.com>
 *
 * Licensed under the MIT License
 */

namespace Underbar\Iterator;

class FilterIterator implements \Iterator
{
    private $it;
    private $f;
    private $key;
    private $current;

    public function __construct(\Iterator $it, $f)
    {
        $this->it = $it;
        $this->f = $f;
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
        $this->fetchNext();
    }

    public function rewind()
    {
        $this->it->rewind();
        $this->fetchNext();
    }

    public function valid()
    {
        return $this->it->valid();
    }

    private function fetchNext()
    {
        while ($this->it->valid()) {
            $this->current = $this->it->current();
            $this->key = $this->it->key();
            if (call_user_func(
                $this->f,
                $this->current,
                $this->key,
                $this->it
            )) {
                break;
            } else {
                $this->it->next();
            }
        }
    }
}
