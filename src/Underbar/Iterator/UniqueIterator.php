<?php
/**
 * This file is part of the Underbar.php package.
 *
 * Copyright (C) 2013 Shota Nozaki <emonkak@gmail.com>
 *
 * Licensed under the MIT License
 */

namespace Underbar\Iterator;

use Underbar\Internal;

class UniqueIterator implements \Iterator
{
    private $it;
    private $f;
    private $set;
    private $current;
    private $key;

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
        $this->fetch();
    }

    public function rewind()
    {
        $this->set = new Internal\Set();
        $this->it->rewind();
        $this->fetch();
    }

    public function valid()
    {
        return $this->it->valid();
    }

    private function fetch()
    {
        while ($this->it->valid()) {
            $this->key = $this->it->key();
            $this->current = $this->it->current();
            if ($this->set->add(call_user_func(
                $this->f,
                $this->current,
                $this->key,
                $this->it
            ))) {
                break;
            } else {
                $this->it->next();
            }
        }
    }
}
