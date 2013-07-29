<?php
/**
 * This file is part of the Underbar.php package.
 *
 * Copyright (C) 2013 Shota Nozaki <emonkak@gmail.com>
 *
 * Licensed under the MIT License
 */

namespace Underbar\Iterator;

class MapIterator implements \Iterator
{
    private $it;
    private $f;
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
        return $this->it->key();
    }

    public function next()
    {
        $this->it->next();
        $this->processIfValid();
    }

    public function rewind()
    {
        $this->it->rewind();
        $this->processIfValid();
    }

    public function valid()
    {
        return $this->it->valid();
    }

    private function processIfValid()
    {
        if ($this->it->valid()) {
            $this->current = call_user_func(
                $this->f,
                $this->it->current(),
                $this->it->key(),
                $this->it
            );
        }
    }
}
