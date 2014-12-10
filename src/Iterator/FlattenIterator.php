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
    private $shallow;

    public function __construct(\Iterator $it, $shallow)
    {
        parent::__construct($it);
        $this->shallow = $shallow;
    }

    public function getChildren()
    {
        $inner = $this->current();
        if (is_array($inner)) {
            $inner = new \ArrayIterator($inner);
        }
        return $this->shallow
            ? new NonRecursiveIterator($inner)
            : new self($inner, $this->shallow);
    }

    public function hasChildren()
    {
        $inner = $this->current();
        return is_array($inner) || $inner instanceof \Traversable;
    }
}
