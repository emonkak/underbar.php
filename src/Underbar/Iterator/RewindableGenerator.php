<?php
/**
 * This file is part of the Underbar.php package.
 *
 * Copyright (C) 2013 Shota Nozaki <emonkak@gmail.com>
 *
 * Licensed under the MIT License
 */

namespace Underbar\Iterator;

class RewindableGenerator implements \IteratorAggregate
{
    private $func;

    private $args;

    public function __construct($func, $args)
    {
        $this->func = $func;
        $this->args = $args;
    }

    public function getIterator()
    {
        return call_user_func_array($this->func, $this->args);
    }
}
