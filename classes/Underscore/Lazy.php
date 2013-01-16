<?php

namespace Underscore;

if (class_exists('Generator', false))
  class_alias('Underscore\\Lazy\\GeneratorFunctions', 'Underscore\\Lazy');
else
  class_alias('Underscore\\Lazy\\IteratorFunctions', 'Underscore\\Lazy');

// __END__
// vim: expandtab softtabstop=2 shiftwidth=2

