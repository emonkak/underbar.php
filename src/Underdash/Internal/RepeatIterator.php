<?php

namespace Underdash\Internal;

class RepeatIterator implements \Iterator
{
    private $value;

    private $index;

    public function __construct($value)
    {
        $this->value = $value;
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
        return true;
    }
}

// __END__
// vim: expandtab softtabstop=4 shiftwidth=4
