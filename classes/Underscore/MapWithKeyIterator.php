<?php

namespace Underscore;

class MapWithKeyIterator extends MapIterator
{
  protected $key;

  public function key()
  {
    return $this->key;
  }

  public function next()
  {
    $this->collection->next();
    list ($this->key, $this->current) =
      call_user_func($this->iterator,
                     $this->collection->current(),
                     $this->collection->key(),
                     $this->collection);
  }

  public function rewind()
  {
    $this->collection->rewind();
    list ($this->key, $this->current) =
      call_user_func($this->iterator,
                     $this->collection->current(),
                     $this->collection->key(),
                     $this->collection);
  }
}

// __END__
// vim: expandtab softtabstop=2 shiftwidth=2
