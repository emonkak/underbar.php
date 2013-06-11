<?php

namespace Underbar\Iterator;

class FlattenInnerIterator extends \IteratorIterator implements \RecursiveIterator
{
    public function getChildren()
    {
        $current = $this->current();
        return new static(is_array($current) ? new \ArrayObject($current) : $current);
    }

    public function hasChildren()
    {
        $current = $this->current();
        return is_array($current) || $current instanceof \Traversable;
    }
}

// __END__
// vim: expandtab softtabstop=4 shiftwidth=4
