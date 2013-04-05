<?php

namespace Underbar;

if (function_exists('trait_exists')) {
    eval('namespace Underbar { abstract class Option extends AbstractOption { use Enumerable; } }');
} else {
    abstract class Option extends AbstractOption {}
}
