<?php

namespace Underscore;

class FilterIterator implements \Iterator
{
  protected $list;

  protected $iterator;

  public function __construct($list, $iterator)
  {
    $this->list = is_array($list) ? new \ArrayObject($list) : $list;
    $this->iterator = $iterator;
  }

  public static function filter($list, $iterator)
  {
    return new static($list, $iterator);
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

    while (($key = $this->list->key()) !== null) {
      $value = $this->list->current();
      if (call_user_func($this->iterator, $value, $key, $this->list))
        break;

      $this->list->next();
    }
  }

  public function rewind()
  {
    $this->list->rewind();

    while (($key = $this->list->key()) !== null) {
      $value = $this->list->current();
      if (call_user_func($this->iterator, $value, $key, $this->list))
        break;

      $this->list->next();
    }
  }

  public function valid()
  {
    return $this->list->valid();
  }
}

// __END__
// vim: expandtab softtabstop=2 shiftwidth=2
