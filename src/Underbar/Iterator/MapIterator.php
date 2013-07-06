<?php
/**
 * This file is part of the Underbar.php package.
 *
 * Copyright (C) 2013 Shota Nozaki <emonkak@gmail.com>
 *
 * Licensed under the MIT License
 */

namespace Underbar\Iterator;

class MapIterator extends \IteratorIterator
{
    private $f;

    private $current;

    public function __construct($xs, $f)
    {
        parent::__construct($xs);
        $this->f = $f;
    }

    public function current()
    {
        return $this->current;
    }

    public function next()
    {
        parent::next();
        if (parent::valid()) {
            $this->current = call_user_func(
                $this->f,
                parent::current(),
                parent::key(),
                $this
            );
        }
    }

    public function rewind()
    {
        parent::rewind();
        if (parent::valid()) {
            $this->current = call_user_func(
                $this->f,
                parent::current(),
                parent::key(),
                $this
            );
        }
    }
}
