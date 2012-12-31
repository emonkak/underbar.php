<?php

namespace Underscore;

class TakeGenerator
{
  public static function take($array, $n)
  {
    foreach ($array as $index => $value) {
      if ($n-- > 0)
        yield $index => $value;
      else
        break;
    }
  }
}

// __END__
// vim: expandtab softtabstop=2 shiftwidth=2
