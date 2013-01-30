<?php

namespace Underbar;

if (class_exists('Generator', false))
    class_alias('Underbar\\Lazy_Generator', 'Underbar\\Lazy');
else
    class_alias('Underbar\\Lazy_Iterator', 'Underbar\\Lazy');

// __END__
// vim: expandtab softtabstop=4 shiftwidth=4
