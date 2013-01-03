<?php

namespace Underscore;

class DropIterator implements \Iterator
{
  protected $list;

  protected $n;

  public function __construct($list, $n)
  {
    $this->list = is_array($list) ? new \ArrayObject($list) : $list;
    $this->n = $n;
  }

  public static function drop($list, $n)
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
    $this->list->next();
  }

  public function rewind()
  {
    $n = $this->n;

    $this->list->rewind();
    while ($n-- > 0) $this->list->next();
  }

  public function valid()
  {
    return $this->list->valid();
  }
}

// __END__
// vim: expandtab softtabstop=2 shiftwidth=2
