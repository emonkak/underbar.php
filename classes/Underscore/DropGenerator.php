<?php

namespace Underscore;

class DropGenerator
{
  public static function drop($array, $n)
  {
    foreach ($array as $index => $value) {
      if (--$n < 0)
        yield $index => $value;
    }
  }
}

// __END__
// vim: expandtab softtabstop=2 shiftwidth=2
