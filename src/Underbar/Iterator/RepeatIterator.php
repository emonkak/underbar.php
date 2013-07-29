<?php
/**
 * This file is part of the Underbar.php package.
 *
 * Copyright (C) 2013 Shota Nozaki <emonkak@gmail.com>
 *
 * Licensed under the MIT License
 */

namespace Underbar\Iterator;

class RepeatIterator implements \Iterator
{
    private $value;
    private $index;
    private $n;

    public function __construct($value, $n)
    {
        $this->value = $value;
        $this->n = $n;
    }

    public function current()
    {
        return $this->value;
    }

    public function key()
    {
        return $this->index;
    }

    public function next()
    {
        $this->index++;
    }

    public function rewind()
    {
        $this->index = 0;
    }

    public function valid()
    {
        return $this->n - $this->index;
    }
}
