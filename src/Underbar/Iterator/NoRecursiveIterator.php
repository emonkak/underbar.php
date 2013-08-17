<?php
/**
 * This file is part of the Underbar.php package.
 *
 * Copyright (C) 2013 Shota Nozaki <emonkak@gmail.com>
 *
 * Licensed under the MIT License
 */

namespace Underbar\Iterator;

class NoRecursiveIterator extends \IteratorIterator implements \RecursiveIterator
{
    public function getChildren()
    {
        return null;
    }

    public function hasChildren()
    {
        return false;
    }
}
