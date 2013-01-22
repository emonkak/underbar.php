<?php

spl_autoload_register(function($className) {
  if (strpos($className, 'Understrike\\') === 0) {
    $fileName = __DIR__
              . DIRECTORY_SEPARATOR
              . 'src'
              . DIRECTORY_SEPARATOR
              . str_replace('\\', DIRECTORY_SEPARATOR, $className)
              . '.php';
    if (is_file($fileName)) require $fileName;
  }
});

// __END__
// vim: expandtab softtabstop=2 shiftwidth=2
