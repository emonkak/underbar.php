<?php

namespace Underbar;

if (function_exists('trait_exists')) {
    eval('namespace Underbar { class Parallel extends AbstractParallel { use Enumerable; } }');
} else {
    class Parallel extends AbstractParallel {}
}
