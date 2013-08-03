<?php
/**
 * This file is part of the Underbar.php package.
 *
 * Copyright (C) 2013 Shota Nozaki <emonkak@gmail.com>
 *
 * Licensed under the MIT License
 */

namespace Underbar\Iterator;

class FlattenIterator extends \IteratorIterator implements \RecursiveIterator
{
    private $depth;

    public function __construct(\Iterator $it, $depth = -1)
    {
        parent::__construct($it);
        $this->depth = $depth;
    }

    public function getChildren()
    {
        $current = $this->current();
        $inner = is_array($current) ? new \ArrayIterator($current) : $current;
        return new self($inner, $this->depth - 1);
    }

    public function hasChildren()
    {
        $current = $this->current();
        return $this->depth !== 0
               && (is_array($current) || $current instanceof \Traversable);
    }
}
