<?php

namespace Underscore;

class TakeIterator implements \Iterator
{
  protected $list;

  protected $n;

  public function __construct($list, $n)
  {
    $this->list = $list;
    $this->n = $n;
  }

  public static function drop($list, $n)
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
    is_array($this->list) ? next($this->list) : $this->list->next();
  }

  public function rewind()
  {
    $n = $this->n;

    if (is_array($this->list)) {
      reset($this->list);
      while ($n-- > 0) next($this->list);
    } else {
      $this->list->rewind();
      while ($n-- > 0) $this->list->next();
    }
  }

  public function valid()
  {
    return is_array($this->list) ? key($this->list) !== null : $this->list->valid();
  }
}

// __END__
// vim: expandtab softtabstop=2 shiftwidth=2
