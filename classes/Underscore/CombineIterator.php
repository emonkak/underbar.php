<?php

namespace Underscore;

class CombineIterator implements \Iterator
{
  protected $keys;

  protected $values;

  public function __construct(\Iterator $keys, \Iterator $values)
  {
    $this->keys = $keys;
    $this->values = $values;
  }

  public function current()
  {
    return $this->values->current();
  }

  public function key()
  {
    return $this->keys->current();
  }

  public function next()
  {
    $this->keys->next();
    $this->values->next();
  }

  public function rewind()
  {
    $this->keys->rewind();
    $this->values->rewind();
  }

  public function valid()
  {
    return $this->keys->valid();
  }
}

// __END__
// vim: expandtab softtabstop=2 shiftwidth=2
