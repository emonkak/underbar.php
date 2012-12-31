<?php

namespace Underscore;

class FilterGenerator
{
  public static function filter($list, $iterator)
  {
    foreach ($list as $index => $value) {
      if (call_user_func($iterator, $value, $index, $list))
        yield $index => $value;
    }
  }
}

// __END__
// vim: expandtab softtabstop=2 shiftwidth=2
