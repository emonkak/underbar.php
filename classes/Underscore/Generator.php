<?php

namespace Underscore;

abstract class Generator
{
  public static function drop($array, $n)
  {
    foreach ($array as $index => $value) {
      if (--$n < 0)
        yield $index => $value;
    }
  }

  public static function filter($list, $iterator)
  {
    foreach ($list as $index => $value) {
      if (call_user_func($iterator, $value, $index, $list))
        yield $index => $value;
    }
  }

  public static function map($list, $iterator)
  {
    foreach ($list as $index => $value)
      yield $index => call_user_func($iterator, $value, $index, $list);
  }

  public static function range($start, $stop, $step)
  {
    if ($start < $stop) {
      do {
        yield $start;
        $start += $step;
      } while ($start <= $stop);
    } else {
      do {
        yield $start;
        $start += $step;
      } while ($start >= $stop);
    }
  }

  public static function take($array, $n)
  {
    foreach ($array as $index => $value) {
      if ($n-- > 0)
        yield $index => $value;
      else
        break;
    }
  }

  public static function union(array $arrays)
  {
    foreach ($arrays as $array) {
      foreach ($array as $value)
        yield $value;
    }
  }
}

// __END__
// vim: expandtab softtabstop=2 shiftwidth=2
