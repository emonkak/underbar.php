<?php

namespace Understrike;

if (class_exists('Generator', false))
    class_alias('Understrike\\Lazy_Generator', 'Understrike\\Lazy');
else
    class_alias('Understrike\\Lazy_Iterator', 'Understrike\\Lazy');

// __END__
// vim: expandtab softtabstop=4 shiftwidth=4
