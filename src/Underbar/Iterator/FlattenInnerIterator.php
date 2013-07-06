<?php
/**
 * This file is part of the Underbar.php package.
 *
 * Copyright (C) 2013 Shota Nozaki <emonkak@gmail.com>
 *
 * Licensed under the MIT License
 */

namespace Underbar\Iterator;

class FlattenInnerIterator extends \IteratorIterator implements \RecursiveIterator
{
    public function getChildren()
    {
        $current = $this->current();
        return new static(is_array($current) ? new \ArrayObject($current) : $current);
    }

    public function hasChildren()
    {
        $current = $this->current();
        return is_array($current) || $current instanceof \Traversable;
    }
}
