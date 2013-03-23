<?php

namespace Underbar\Internal;

class MapIterator extends \IteratorIterator
{
    private $f;

    public function __construct($xs, $f)
    {
        parent::__construct($xs);
        $this->f = $f;
    }

    public function current()
    {
        return call_user_func(
            $this->f,
            parent::current(),
            parent::key(),
            $this
        );
    }
}

// __END__
// vim: expandtab softtabstop=4 shiftwidth=4
