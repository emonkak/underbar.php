<?php

namespace Underscore;

class Chain
{
  protected $xs;

  protected $class;

  public function __construct($xs, $class)
  {
    $this->xs = $xs;
    $this->class = $class;
  }

  public function __call($name, $aruguments)
  {
    array_unshift($aruguments, $this->xs);
    $result = call_user_func_array(array($this->class, $name), $aruguments);
    return (is_array($result) || $result instanceof \Iterator)
         ? new static($result, $this->class)
         : $result;
  }

  public function value()
  {
    return $this->xs;
  }
}

// __END__
// vim: expandtab softtabstop=2 shiftwidth=2
