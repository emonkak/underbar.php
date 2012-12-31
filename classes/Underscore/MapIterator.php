<?php

namespace Underscore;

class MapIterator implements \Iterator
{
  protected $list;

  protected $iterator;

  public function __construct($list, $iterator)
  {
    $this->list = $list;
    $this->iterator = $iterator;
  }

  public static function map($list, $iterator)
  {
    return new static($list, $iterator);
  }

  public function current()
  {
    return is_array($this->list)
         ? call_user_func($this->iterator, current($this->list), key($this->list), $this->list)
         : call_user_func($this->iterator, $this->list->current(), $this->list->key, $this->list);
  }

  public function key()
  {
    return is_array($this->list) ? key($this->list) : $this->list->key();
  }

  public function next()
  {
    is_array($this->list) ? next($this->list) : $this->list->next();
  }

  public function rewind()
  {
    is_array($this->list) ? reset($this->list) : $this->list->rewind();
  }

  public function valid()
  {
    return is_array($this->list) ? key($this->list) !== null : $this->list->valid();
  }
}

// __END__
// vim: expandtab softtabstop=2 shiftwidth=2
