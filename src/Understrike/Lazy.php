<?php

namespace Understrike;

if (class_exists('Generator', false))
    class_alias('Understrike\\Lazy\\GeneratorFunctions', 'Understrike\\Lazy');
else
    class_alias('Understrike\\Lazy\\IteratorFunctions', 'Understrike\\Lazy');

// __END__
// vim: expandtab softtabstop=4 shiftwidth=4
