<?php

namespace Understrike\Lazy;

class MapWithKeyIterator extends \IteratorIterator
{
    protected $iterator;

    protected $key;

    protected $current;

    public function __construct(\Traversable $list, $iterator)
    {
        parent::__construct($list);
        $this->iterator = $iterator;
    }

    public function key()
    {
        if ($this->key === null) {
            list ($this->key, $current) =
                call_user_func($this->iterator,
                    parent::current(),
                    parent::key(),
                    $this);
        }
        return $this->key;
    }

    public function current()
    {
        if ($this->key === null) {
            list ($this->key, $this->current) =
                call_user_func($this->iterator,
                    parent::current(),
                    parent::key(),
                    $this);
        }
        return $this->current;
    }

    public function rewind()
    {
        parent::rewind();
        $this->key = null;
        $this->current = null;
    }

    public function next()
    {
        parent::next();
        $this->key = null;
        $this->current = null;
    }
}

// __END__
// vim: expandtab softtabstop=4 shiftwidth=4
