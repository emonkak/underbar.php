<?php

namespace Understrike\Internal;

class RangeIterator implements \Iterator
{
    protected $start;

    protected $step;

    protected $len;

    protected $index;

    protected $current;

    public function __construct($start, $stop, $step)
    {
        $this->start = $start;
        $this->step = $step;
        $this->len = max(ceil(($stop - $start) / $step), 0);
    }

    public function current()
    {
        return $this->current;
    }

    public function key()
    {
        return $this->index;
    }

    public function next()
    {
        $this->index++;
        $this->current += $this->step;
    }

    public function rewind()
    {
        $this->index = 0;
        $this->current = $this->start;
    }

    public function valid()
    {
        return $this->index < $this->len;
    }
}

// __END__
// vim: expandtab softtabstop=4 shiftwidth=4
