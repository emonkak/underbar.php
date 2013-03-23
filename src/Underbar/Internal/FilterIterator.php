<?php

namespace Underbar\Internal;

class FilterIterator extends \FilterIterator
{
    private $f;

    public function __construct($xs, $f)
    {
        parent::__construct($xs);
        $this->f = $f;
    }

    public function accept()
    {
        return call_user_func(
            $this->f,
            $this->current(),
            $this->key(),
            $this
        );
    }
}

// __END__
// vim: expandtab softtabstop=4 shiftwidth=4
