<?php

namespace Underbar\Internal;

class CountByIterator extends \IteratorIterator
{
    private $key;

    private $current;

    private $next;

    private $nextKey;

    private $f;

    public function __construct($xs, $f)
    {
        parent::__construct($xs);
        $this->f = $f;
    }

    public function current()
    {
        return $this->current;
    }

    public function key()
    {
        return $this->key;
    }

    public function next()
    {
        parent::next();
        $this->current = $this->next;
        $this->key = $this->nextKey;
        $this->next = 0;
        $this->fill();
    }

    public function rewind()
    {
        parent::rewind();
        $this->current = $this->next = 0;
        $this->key = $this->nextKey = null;
        $this->fill();
    }

    public function valid()
    {
        return $this->current !== 0 || parent::valid();
    }

    private function fill()
    {
        while (parent::valid()) {
            $key = call_user_func(
                $this->f,
                parent::current(),
                parent::key(),
                $this
            );
            if ($key !== $this->nextKey) {
                if ($this->current !== 0) {
                    $this->key = $this->nextKey;
                    $this->next = 1;
                    $this->nextKey = $key;
                    break;
                }
                $this->nextKey = $key;
            }
            $this->current++;
            parent::next();
        }
    }
}

// __END__
// vim: expandtab softtabstop=4 shiftwidth=4
