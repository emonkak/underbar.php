<?php

namespace Underscore;

if (class_exists('Generator')) {
  // Suppress syntax error on less than PHP 5.4.
  eval('namespace Underscore { abstract class _ extends Underscore { use Generator; }}');
} else {
  abstract class _ extends Underscore {}
}

// __END__
// vim: expandtab softtabstop=2 shiftwidth=2
