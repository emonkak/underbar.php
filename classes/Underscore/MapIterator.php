<?php

namespace Underscore;

class MapIterator implements \Iterator
{
  protected $collection;

  protected $iterator;

  protected $current;

  public function __construct(\Iterator $list, $iterator)
  {
    $this->collection = $list;
    $this->iterator = $iterator;
  }

  public function current()
  {
    return $this->current;
  }

  public function key()
  {
    $this->collection->key();
  }

  public function next()
  {
    $this->collection->next();
    $this->current = call_user_func($this->iterator,
                                    $this->collection->current(),
                                    $this->collection->key(),
                                    $this->collection);
  }

  public function rewind()
  {
    $this->collection->rewind();
    $this->current = call_user_func($this->iterator,
                                    $this->collection->current(),
                                    $this->collection->key(),
                                    $this->collection);
  }

  public function valid()
  {
    return $this->collection->valid();
  }
}

// __END__
// vim: expandtab softtabstop=2 shiftwidth=2
