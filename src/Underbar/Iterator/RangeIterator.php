<?php
/**
 * This file is part of the Underbar.php package.
 *
 * Copyright (C) 2013 Shota Nozaki <emonkak@gmail.com>
 *
 * Licensed under the MIT License
 */

namespace Underbar\Iterator;

class RangeIterator implements \Iterator
{
    private $start;

    private $stop;

    private $step;

    private $current;

    public function __construct($start, $stop, $step)
    {
        $this->start = $start;
        $this->stop = $stop;
        $this->step = $step;
    }

    public function current()
    {
        return $this->current;
    }

    public function key()
    {
    }

    public function next()
    {
        $this->current += $this->step;
    }

    public function rewind()
    {
        $this->current = $this->start;
    }

    public function valid()
    {
        return $this->step > 0
            ? $this->current < $this->stop
            : $this->current > $this->stop;
    }
}
