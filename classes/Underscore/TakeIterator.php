<?php

namespace Underscore;

class TakeIterator implements \Iterator
{
  protected $list;

  protected $n;

  protected $position = 0;

  public function __construct($list, $n)
  {
    $this->list = $list;
    $this->n = $n;
  }

  public static function take($list, $n)
  {
    return new static($list, $n);
  }

  public function current()
  {
    return is_array($this->list) ? current($this->list) : $this->list->current();
  }

  public function key()
  {
    return is_array($this->list) ? key($this->list) : $this->list->key();
  }

  public function next()
  {
    $this->position++;
    is_array($this->list) ? next($this->list) : $this->list->next();
  }

  public function rewind()
  {
    $this->position = 0;
    is_array($this->list) ? reset($this->list) : $this->list->rewind();
  }

  public function valid()
  {
    return $this->position < $this->n
           && (is_array($this->list) ? key($this->list) !== null : $this->list->valid());
  }
}

// __END__
// vim: expandtab softtabstop=2 shiftwidth=2
