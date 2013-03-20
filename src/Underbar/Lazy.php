<?php

namespace Underbar;

if (class_exists('Generator', false)) {
    class_alias('Underbar\\LazyGenerator', 'Underbar\\Lazy');
} else {
    class_alias('Underbar\\LazyIterator', 'Underbar\\Lazy');
}

// __END__
// vim: expandtab softtabstop=4 shiftwidth=4
