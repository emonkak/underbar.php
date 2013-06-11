<?php

namespace Underbar\Iterator;

class IterateIterator implements \Iterator
{
    private $default;

    private $f;

    private $acc;

    public function __construct($default, $f)
    {
        $this->default = $default;
        $this->f = $f;
    }

    public function current()
    {
        return $this->acc;
    }

    public function key()
    {
    }

    public function next()
    {
        $this->acc = call_user_func($this->f, $this->acc);
    }

    public function rewind()
    {
        $this->acc = $this->default;
    }

    public function valid()
    {
        return true;
    }
}

// __END__
// vim: expandtab softtabstop=4 shiftwidth=4
