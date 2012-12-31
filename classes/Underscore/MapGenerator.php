<?php

namespace Underscore;

class MapGenerator
{
  public static function map($list, $iterator)
  {
    foreach ($list as $index => $value)
      yield $index => call_user_func($iterator, $value, $index, $list);
  }
}

// __END__
// vim: expandtab softtabstop=2 shiftwidth=2
