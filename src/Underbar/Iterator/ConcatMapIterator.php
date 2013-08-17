<?php
/**
 * This file is part of the Underbar.php package.
 *
 * Copyright (C) 2013 Shota Nozaki <emonkak@gmail.com>
 *
 * Licensed under the MIT License
 */

namespace Underbar\Iterator;

class ConcatMapIterator extends \IteratorIterator implements \RecursiveIterator
{
    private $f;

    public function __construct(\Iterator $it, $f)
    {
        parent::__construct($it);
        $this->f = $f;
    }

    public function getChildren()
    {
        $it = call_user_func(
            $this->f,
            $this->current(),
            $this->key(),
            $this->getInnerIterator()
        );
        if (is_array($it)) {
            $it = new \ArrayIterator($it);
        }
        return new NoRecursiveIterator($it);
    }

    public function hasChildren()
    {
        return true;
    }
}
