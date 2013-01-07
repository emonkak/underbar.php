<?php

namespace Underscore\Lazy;

if (class_exists('Generator')) {
  abstract class _ extends GeneratorFunction {}
} else {
  abstract class _ extends IteratorFunction {}
}

// __END__
// vim: expandtab softtabstop=2 shiftwidth=2

