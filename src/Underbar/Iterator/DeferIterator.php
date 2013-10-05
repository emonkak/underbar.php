<?php
/**
 * This file is part of the Underbar.php package.
 *
 * Copyright (C) 2013 Shota Nozaki <emonkak@gmail.com>
 *
 * Licensed under the MIT License
 */

namespace Underbar\Iterator;

class DeferIterator implements \IteratorAggregate
{
    private $f;

    public function __construct($f)
    {
        $this->f = $f;
    }

    public function getIterator()
    {
        return call_user_func($this->f);
    }
}
