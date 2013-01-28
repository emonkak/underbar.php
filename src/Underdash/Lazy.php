<?php

namespace Underdash;

if (class_exists('Generator', false))
    class_alias('Underdash\\Lazy_Generator', 'Underdash\\Lazy');
else
    class_alias('Underdash\\Lazy_Iterator', 'Underdash\\Lazy');

// __END__
// vim: expandtab softtabstop=4 shiftwidth=4
