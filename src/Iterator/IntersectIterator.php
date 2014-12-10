<?php
/**
 * This file is part of the Underbar.php package.
 *
 * Copyright (C) 2013 Shota Nozaki <emonkak@gmail.com>
 *
 * Licensed under the MIT License
 */

namespace Underbar\Iterator;

use Underbar\Comparer\EqualityComparer;
use Underbar\Util\Set;

class IntersectIterator implements \Iterator
{
    private $it;
    private $others;
    private $equalityComparer;
    private $set;

    public function __construct(\Iterator $it, $others, EqualityComparer $equalityComparer)
    {
        $this->it = $it;
        $this->others = $others;
        $this->equalityComparer = $equalityComparer;
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
        $this->fetch();
    }

    public function rewind()
    {
        $this->set = new Set($this->equalityComparer);
        foreach ($this->others as $other) {
            $this->set->addAll($other);
        }
        $this->fetch();
    }

    public function valid()
    {
        return $this->it->valid();
    }

    private function fetch()
    {
        while ($this->it->valid()) {
            $this->current = $this->it->current();
            if ($this->set->remove($this->current)) {
                break;
            } else {
                $this->it->next();
            }
        }
    }
}
