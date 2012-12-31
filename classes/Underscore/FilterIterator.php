<?php

namespace Underscore;

class FilterIterator implements \Iterator
{
  protected $list;

  protected $iterator;

  public function __construct($list, $iterator)
  {
    $this->list = $list;
    $this->iterator = $iterator;
  }

  public static function filter($list, $iterator)
  {
    return new static($list, $iterator);
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
    if (is_array($this->list)) {
      $value = next($this->list);

      while (($key = key($this->list)) !== null) {
        if (call_user_func($this->iterator, $value, $key, $this->list))
          break;

        $value = next($this->list);
      }
    } else {
      $this->list->next();
      $value = $this->list->current();

      while (($key = $this->list->key()) !== null) {
        if (call_user_func($this->iterator, $value, $key, $this->list))
          break;

        $this->list->next();
        $value = $this->list->current();
      }
    }
  }

  public function rewind()
  {
    if (is_array($this->list)) {
      $value = reset($this->list);

      while (($key = key($this->list)) !== null) {
        if (call_user_func($this->iterator, $value, $key, $this->list))
          break;

        $value = next($this->list);
      }
    } else {
      $this->list->rewind();
      $value = $this->list->current();

      while (($key = $this->list->key()) !== null) {
        if (call_user_func($this->iterator, $value, $key, $this->list))
          break;

        $this->list->next();
        $value = $this->list->current();
      }
    }
  }

  public function valid()
  {
    return is_array($this->list) ? key($this->list) !== null : $this->list->valid();
  }
}

// __END__
// vim: expandtab softtabstop=2 shiftwidth=2
