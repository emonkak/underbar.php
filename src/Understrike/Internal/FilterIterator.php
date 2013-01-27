<?php

namespace Understrike\Internal;

class FilterIterator extends \FilterIterator
{
    private $iterator;

    public function __construct(\Iterator $list, $iterator)
    {
        parent::__construct($list);
        $this->iterator = $iterator;
    }

    public function accept()
    {
        return call_user_func($this->iterator,
            $this->current(),
            $this->key(),
            $this);
    }
}

// __END__
// vim: expandtab softtabstop=4 shiftwidth=4
