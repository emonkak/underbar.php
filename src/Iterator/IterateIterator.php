<?php
/**
 * This file is part of the Underbar.php package.
 *
 * Copyright (C) 2013 Shota Nozaki <emonkak@gmail.com>
 *
 * Licensed under the MIT License
 */

namespace Underbar\Iterator;

class IterateIterator implements \Iterator
{
    private $initial;
    private $f;
    private $acc;
    private $index;

    public function __construct($initial, callable $f)
    {
        $this->initial = $initial;
        $this->f = $f;
    }

    public function current()
    {
        return $this->acc;
    }

    public function key()
    {
        return $this->index;
    }

    public function next()
    {
        $this->acc = call_user_func($this->f, $this->acc);
        $this->index++;
    }

    public function rewind()
    {
        $this->acc = $this->initial;
        $this->index = 0;
    }

    public function valid()
    {
        return true;
    }
}
