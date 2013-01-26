<?php

namespace Understrike\Internal;

class TakeWhileIterator extends \IteratorIterator
{
    private $iterator;

    public function __construct(\Traversable $list, $iterator)
    {
        parent::__construct($list);
        $this->iterator = $iterator;
    }

    public function valid()
    {
        return call_user_func($this->iterator,
            parent::current(),
            parent::key(),
            $this);
    }
}

// __END__
// vim: expandtab softtabstop=4 shiftwidth=4
