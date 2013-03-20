<?php

namespace Underbar\Internal;

class MapKeyIterator extends \IteratorIterator
{
    private $iterator;

    public function __construct(\Iterator $list, $iterator)
    {
        parent::__construct($list);
        $this->iterator = $iterator;
    }

    public function key()
    {
        return call_user_func($this->iterator,
            parent::current(),
            parent::key(),
            $this);
    }
}

// __END__
// vim: expandtab softtabstop=4 shiftwidth=4
