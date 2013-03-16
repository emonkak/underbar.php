<?php

namespace Underbar;

if (class_exists('Generator', false)) {
    class_alias('Underbar\\Lazy\\Generator', 'Underbar\\Underbar');
} else {
    class_alias('Underbar\\Lazy\\Iterator', 'Underbar\\Underbar');
}

// __END__
// vim: expandtab softtabstop=4 shiftwidth=4
