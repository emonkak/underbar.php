<?php

namespace Underscore;

class UnionIterator implements \Iterator
{
  protected $arrays;

  protected $current;

  protected $index;

  public function __construct(array $arrays)
  {
    $this->arrays = $arrays;
  }

  public function current()
  {
    return $this->current->current();
  }

  public function key()
  {
    return $this->index;
  }

  public function next()
  {
    $this->current->next();

    while (!$this->current->valid() && ($this->current = next($this->arrays)))
      $this->current->rewind();

    $this->index++;
  }

  public function rewind()
  {
    if ($this->current = reset($this->arrays)) {
      $this->current->rewind();

      while (!$this->current->valid() && ($this->current = next($this->arrays)))
        $this->current->rewind();
    }

    $this->index = 0;
  }

  public function valid()
  {
    return $this->current;
  }
}

// __END__
// vim: expandtab softtabstop=2 shiftwidth=2
