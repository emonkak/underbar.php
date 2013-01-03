<?php

namespace Underscore;

class TakeIterator implements \Iterator
{
  protected $list;

  protected $n;

  protected $position = 0;

  public function __construct($list, $n)
  {
    $this->list = is_array($list) ? new \ArrayObject($list) : $list;
    $this->n = $n;
  }

  public static function take($list, $n)
  {
    return new static($list, $n);
  }

  public function current()
  {
    return $this->list->current();
  }

  public function key()
  {
    return $this->list->key();
  }

  public function next()
  {
    $this->position++;
    $this->list->next();
  }

  public function rewind()
  {
    $this->position = 0;
    $this->list->rewind();
  }

  public function valid()
  {
    return $this->position < $this->n && $this->list->valid();
  }
}

// __END__
// vim: expandtab softtabstop=2 shiftwidth=2
