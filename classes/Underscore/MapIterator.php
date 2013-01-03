<?php

namespace Underscore;

class MapIterator implements \Iterator
{
  protected $list;

  protected $iterator;

  public function __construct($list, $iterator)
  {
    $this->list = is_array($list) ? new \ArrayObject($list) : $list;
    $this->iterator = $iterator;
  }

  public static function map($list, $iterator)
  {
    return new static($list, $iterator);
  }

  public function current()
  {
    return call_user_func($this->iterator,
                          $this->list->current(),
                          $this->list->key(),
                          $this->list);
  }

  public function key()
  {
    return $this->list->key();
  }

  public function next()
  {
    $this->list->next();
  }

  public function rewind()
  {
    $this->list->rewind();
  }

  public function valid()
  {
    return $this->list->valid();
  }
}

// __END__
// vim: expandtab softtabstop=2 shiftwidth=2
