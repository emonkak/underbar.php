<?php

namespace Underscore;

class RangeGenerator
{
  public static function range($start, $stop, $step)
  {
    if ($start < $stop) {
      do {
        yield $start;
        $start += $step;
      } while ($start < $stop);
    } else {
      do {
        yield $start;
        $start += $step;
      } while ($start > $stop);
    }
  }
}

// __END__
// vim: expandtab softtabstop=2 shiftwidth=2
