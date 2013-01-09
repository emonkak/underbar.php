<?php

namespace Underscore;

if (class_exists('Generator')) {
  abstract class Lazy extends Lazy\GeneratorFunction {}
} else {
  abstract class Lazy extends Lazy\IteratorFunction {}
}

// __END__
// vim: expandtab softtabstop=2 shiftwidth=2

