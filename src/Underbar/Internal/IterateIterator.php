<?php

namespace Underbar\Internal;

class IterateIterator implements \Iterator
{
    private $memo;

    private $iterator;

    private $current;

    private $index;

    public function __construct($memo, $iterator)
    {
        $this->memo = $memo;
        $this->iterator = $iterator;
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
        $this->current = call_user_func($this->iterator, $this->current);
    }

    public function rewind()
    {
        $this->index = 0;
        $this->current = $this->memo;
    }

    public function valid()
    {
        return true;
    }
}

// __END__
// vim: expandtab softtabstop=4 shiftwidth=4
