<?php

namespace Understrike\Internal;

class InitialIterator extends \IteratorIterator
{
    private $queue;

    private $n;

    private $index;

    private $current;

    public function __construct(\Traversable $array, $n)
    {
        parent::__construct($array);
        $this->queue = new \SplQueue();
        $this->n = $n;
    }

    public function current()
    {
        return $this->current;
    }

    public function key()
    {
        return $this->index;
    }

    public function next()
    {
        parent::next();
        $this->queue->enqueue(parent::current());
        $this->index++;
        $this->current = $this->queue->dequeue();
    }

    public function rewind()
    {
        parent::rewind();

        $n = $this->n;
        while (parent::valid()) {
            $this->queue->enqueue(parent::current());
            if ($n-- <= 0) break;
            parent::next();
        }

        $this->index = 0;
        $this->current = $this->queue->dequeue();
    }
}

// __END__
// vim: expandtab softtabstop=4 shiftwidth=4
