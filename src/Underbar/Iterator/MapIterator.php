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
    private $g;
    private $current;
    private $key;

    public function __construct(\Iterator $it, $f, $g)
    {
        $this->it = $it;
        $this->f = $f;
        $this->g = $g;
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
        $this->fetch();
    }

    public function rewind()
    {
        $this->it->rewind();
        $this->fetch();
    }

    public function valid()
    {
        return $this->it->valid();
    }

    private function fetch()
    {
        if ($this->it->valid()) {
            $key = $this->it->key();
            $current = $this->it->current();
            $this->key = call_user_func(
                $this->g,
                $key,
                $current,
                $this->it
            );
            $this->current = call_user_func(
                $this->f,
                $current,
                $key,
                $this->it
            );
        }
    }
}
