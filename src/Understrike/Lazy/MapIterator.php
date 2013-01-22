<?php

namespace Understrike\Lazy;

class MapIterator extends \IteratorIterator
{
    protected $iterator;

    public function __construct(\Traversable $list, $iterator)
    {
        parent::__construct($list);
        $this->iterator = $iterator;
    }

    public function current()
    {
        return call_user_func($this->iterator,
            parent::current(),
            parent::key(),
            $this);
    }
}

// __END__
// vim: expandtab softtabstop=4 shiftwidth=4
