<?php

namespace Underscore;

class Chain
{
  protected $collection;

  protected $class;

  public function __construct($collection, $class)
  {
    $this->collection = $collection;
    $this->class = $class;
  }

  public function __call($name, $aruguments)
  {
    array_unshift($aruguments, $this->collection);
    $result = call_user_func_array($this->class.'::'.$name, $aruguments);
    return (is_array($result) || $result instanceof \Iterator)
         ? new static($result, $this->class)
         : $result;
  }

  public function value()
  {
    return $this->collection;
  }
}

// __END__
// vim: expandtab softtabstop=2 shiftwidth=2
