<?php
/**
 * This file is part of the Underbar.php package.
 *
 * Copyright (C) 2013 Shota Nozaki <emonkak@gmail.com>
 *
 * Licensed under the MIT License
 */

namespace Underbar\Iterator;

class TakeWhileIterator extends \IteratorIterator
{
    private $f;

    public function __construct($xs, $f)
    {
        parent::__construct($xs);
        $this->f = $f;
    }

    public function valid()
    {
        return parent::valid() && call_user_func(
            $this->f,
            parent::current(),
            parent::key(),
            $this
        );
    }
}
