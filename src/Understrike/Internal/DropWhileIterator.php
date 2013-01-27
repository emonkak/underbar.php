<?php

namespace Understrike\Internal;

class DropWhileIterator extends \FilterIterator
{
    private $accepted = false;

    public function __construct(\Iterator $list, $iterator)
    {
        parent::__construct($list);
        $this->iterator = $iterator;
    }

    public function accept()
    {
        return $this->accepted
            || ($this->accepted =
                    !call_user_func($this->iterator,
                                    $this->current(),
                                    $this->key(),
                                    $this));
    }

    public function rewind()
    {
        $this->accepted = false;
        parent::rewind();
    }
}

// __END__
// vim: expandtab softtabstop=4 shiftwidth=4
