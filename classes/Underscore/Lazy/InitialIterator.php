<?php

namespace Underscore\Lazy;

class InitialIterator extends \IteratorIterator
{
  protected $queue;

  protected $n;

  protected $index;

  public function __construct(\Traversable $array, $n)
  {
    parent::__construct($array);
    $this->queue = new \SplQueue();
    $this->n = $n;
  }

  public function current()
  {
    return $this->queue->dequeue();
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
  }

  public function rewind()
  {
    parent::rewind();

    $n = $this->n;
    do {
      if (!parent::valid()) break;
      $this->queue->enqueue(parent::current());
      parent::next();
    } while ($n-- > 0);

    $this->index = 0;
  }
}

// __END__
// vim: expandtab softtabstop=2 shiftwidth=2
