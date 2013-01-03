<?php

namespace Underscore;

class UnionIterator implements \Iterator
{
  protected $arrays;

  protected $current;

  protected $index;

  public function __construct(array $arrays)
  {
    $this->arrays = $arrays;
  }

  public function current()
  {
    return is_array($this->current)
         ? current($this->current)
         : $this->current->current();
  }

  public function key()
  {
    return $this->index;
  }

  public function next()
  {
    $this->index++;
    is_array($this->current) ? next($this->current) : $this->current->next();
  }

  public function rewind()
  {
    foreach ($this->arrays as &$array)
      is_array($array) ? reset($array) : $array->rewind();

    $this->index = 0;
    $this->current = reset($this->arrays);
  }

  public function valid()
  {
    do {
      if (is_array($this->current))
        $result = key($this->current) !== null;
      elseif ($this->current instanceof \Iterator)
        $result = $this->current->valid();
      else
        $result = false;
    } while (!$result && ($this->current = next($this->arrays)));

    return $result;
  }
}

// __END__
// vim: expandtab softtabstop=2 shiftwidth=2
