<?php

namespace Underscore;

class ZipIterator implements \Iterator
{
  protected $arrays;

  protected $index;

  public function __construct(array $arrays)
  {
    $this->arrays = $arrays;
  }

  public function current()
  {
    $values = array();

    foreach ($this->arrays as $array)
      $values[] = $array->current();

    return $values;
  }

  public function key()
  {
    return $this->index;
  }

  public function next()
  {
    foreach ($this->arrays as $array)
      $array->next();
    $this->index++;
  }

  public function rewind()
  {
    foreach ($this->arrays as $array)
      $array->rewind();
    $this->index = 0;
  }

  public function valid()
  {
    foreach ($this->arrays as $array) {
      if ($array->valid())
        return true;
    }
    return false;
  }
}

// __END__
// vim: expandtab softtabstop=2 shiftwidth=2
