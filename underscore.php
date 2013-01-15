<?php

spl_autoload_register(function($className) {
  if (strpos($className, 'Underscore\\') === 0) {
    $fileName = __DIR__
              . DIRECTORY_SEPARATOR
              . 'classes'
              . DIRECTORY_SEPARATOR
              . str_replace('\\', DIRECTORY_SEPARATOR, $className)
              . '.php';
    if (is_file($fileName)) require $fileName;
  }
});

// __END__
// vim: expandtab softtabstop=2 shiftwidth=2
