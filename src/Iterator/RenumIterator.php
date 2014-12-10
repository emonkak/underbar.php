<?php

namespace Underbar\Iterator;

class RenumIterator implements \Iterator
{
    private $it;

    private $i;

    public function __construct(\Iterator $it)
    {
        $this->it = $it;
    }

    public function current()
    {
        return $this->it->current();
    }

    public function key() 
    {
        return $this->i;
    }

    public function next()
    {
        $this->i++;
        $this->it->next();
    }

    public function rewind()
    {
        $this->i = 0;
        $this->it->rewind();
    }

    public function valid()
    {
        return $this->it->valid();
    }
