<?php
/**
 * This file is part of the Underbar.php package.
 *
 * Copyright (C) 2013 Shota Nozaki <emonkak@gmail.com>
 *
 * Licensed under the MIT License
 */

namespace Underbar\Iterator;

class TakeWhileIterator implements \Iterator
{
    private $it;
    private $predicate;
    private $current;
    private $key;
    private $accepted;

    public function __construct(\Iterator $it, $predicate)
    {
        $this->it = $it;
        $this->predicate = $predicate;
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
        return $this->accepted;
    }

    private function fetch()
    {
        if ($this->it->valid()) {
            $this->current = $this->it->current();
            $this->key = $this->it->key();
            $this->accepted = call_user_func(
                $this->predicate,
                $this->current,
                $this->key,
                $this
            );
        } else {
            $this->accepted = false;
        }
    }
}
