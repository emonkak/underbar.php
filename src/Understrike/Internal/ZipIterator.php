<?php

namespace Understrike\Internal;

class ZipIterator implements \RecursiveIterator
{
    protected $arrays;

    protected $index;

    public function __construct(array $arrays)
    {
        $this->arrays = $arrays;
    }

    public function getChildren()
    {
        return new \RecursiveArrayIterator($this->current());
    }

    public function hasChildren()
    {
        return true;
    }

    public function current()
    {
        $zipped = array();
        $index = $this->index * count($this->arrays);

        foreach ($this->arrays as $array)
            $zipped[$index++] = $array->current();

        return $zipped;
    }

    public function key()
    {
        return $this->index;
    }

    public function next()
    {
        foreach ($this->arrays as $array) $array->next();
        $this->index++;
    }

    public function rewind()
    {
        foreach ($this->arrays as $array) $array->rewind();
        $this->index = 0;
    }

    public function valid()
    {
        foreach ($this->arrays as $array) {
            if ($array->valid())
                return true;
        }
        return false;
    }
}

// __END__
// vim: expandtab softtabstop=4 shiftwidth=4
