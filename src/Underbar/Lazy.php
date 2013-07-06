<?php
/**
 * This file is part of the Underbar.php package.
 *
 * Copyright (C) 2013 Shota Nozaki <emonkak@gmail.com>
 *
 * Licensed under the MIT License
 */

namespace Underbar;

if (class_exists('Generator', false)) {
    class_alias('Underbar\\LazyGenerator', 'Underbar\\Lazy');
} else {
    class_alias('Underbar\\LazyIterator', 'Underbar\\Lazy');
}
