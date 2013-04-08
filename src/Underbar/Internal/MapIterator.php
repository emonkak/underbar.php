<?php

namespace Underbar\Internal;

class MapIterator extends \IteratorIterator
{
    private $f;

    private $current;

    public function __construct($xs, $f)
    {
        parent::__construct($xs);
        $this->f = $f;
    }

    public function current()
    {
        return $this->current;
    }

    public function next()
    {
        parent::next();
        if (parent::valid()) {
            $this->current = call_user_func(
                $this->f,
                parent::current(),
                parent::key(),
                $this
            );
        }
    }

    public function rewind()
    {
        parent::rewind();
        if (parent::valid()) {
            $this->current = call_user_func(
                $this->f,
                parent::current(),
                parent::key(),
                $this
            );
        }
    }
}

// __END__
// vim: expandtab softtabstop=4 shiftwidth=4
