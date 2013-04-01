<?php

namespace Underbar\Internal;

class IterateIterator implements \Iterator
{
    private $default;

    private $f;

    private $acc;

    private $index;

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
        return $this->index;
    }

    public function next()
    {
        $this->index++;
        $this->acc = call_user_func($this->f, $this->acc);
    }

    public function rewind()
    {
        $this->index = 0;
        $this->acc = $this->default;
    }

    public function valid()
    {
        return true;
    }
}

// __END__
// vim: expandtab softtabstop=4 shiftwidth=4
