<?php

namespace Underscore;

spl_autoload_register(function($className) {
  if (strpos($className, 'Underscore\\') === 0) {
    $fileName = __DIR__
              . DIRECTORY_SEPARATOR
              . 'classes'
              . DIRECTORY_SEPARATOR
              . str_replace('\\', DIRECTORY_SEPARATOR, $className)
              . '.php';
    require($fileName);
  }
});

if (class_exists('Generator')) {
  // Suppress syntax error on less than PHP 5.4.
  eval('namespace Underscore { abstract class _ extends Underscore { use Generator; }}');
} else {
  abstract class _ extends Underscore {}
}

// __END__
// vim: expandtab softtabstop=2 shiftwidth=2
