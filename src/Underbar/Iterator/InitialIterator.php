<?php

namespace Underbar\Iterator;

class InitialIterator extends \IteratorIterator
{
    private $queue;

    private $n;

    private $current;

    public function __construct($xs, $n)
    {
        parent::__construct($xs);
        $this->queue = new \SplQueue();
        $this->n = $n;
    }

    public function current()
    {
        return $this->current;
    }

    public function next()
    {
        parent::next();
        $this->queue->enqueue(parent::current());
        $this->current = $this->queue->dequeue();
    }

    public function rewind()
    {
        parent::rewind();

        $n = $this->n;
        while (parent::valid()) {
            $this->queue->enqueue(parent::current());
            if (--$n < 0) {
                break;
            }
            parent::next();
        }

        if (!$this->queue->isEmpty()) {
            $this->current = $this->queue->dequeue();
        }
    }
}

// __END__
// vim: expandtab softtabstop=4 shiftwidth=4
