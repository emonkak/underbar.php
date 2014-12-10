<?php
/**
 * This file is part of the Underbar.php package.
 *
 * Copyright (C) 2013 Shota Nozaki <emonkak@gmail.com>
 *
 * Licensed under the MIT License
 */

namespace Underbar\Iterator;

use Underbar\Util\Iterators;

class LazyIterator implements \IteratorAggregate
{
    private $factory;

    public function __construct($factory)
    {
        $this->factory = $factory;
    }

    public function getIterator()
    {
        return Iterators::create(call_user_func($this->factory));
    }
}
