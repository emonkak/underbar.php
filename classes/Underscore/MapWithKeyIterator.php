<?php

namespace Underscore;

class MapWithKeyIterator extends MapIterator
{
  protected $key;

  public function key()
  {
    return $this->key;
  }

  public function current()
  {
    list ($this->key, $current) =
      call_user_func($this->iterator,
                     parent::current(),
                     parent::key(),
                     $this);
    return $current;
  }
}

// __END__
// vim: expandtab softtabstop=2 shiftwidth=2
