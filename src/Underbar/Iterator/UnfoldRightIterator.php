<?php
/**
 * This file is part of the Underbar.php package.
 *
 * Copyright (C) 2013 Shota Nozaki <emonkak@gmail.com>
 *
 * Licensed under the MIT License
 */

namespace Underbar\Iterator;

class UnfoldRightIterator implements \Iterator
{
    /**
     * @var  callable
     */
    private $f;

    /**
     * @var  mixed
     */
    private $initial;

    /**
     * @var  mixed
     */
    private $current;

    /**
     * @param  callable  $f
     * @param  mixed     $initial
     */
    public function __construct($f, $initial)
    {
        $this->f = $f;
        $this->initial = $initial;
    }

    public function current()
    {
        return $this->current[0];
    }

    public function key()
    {
    }

    public function rewind()
    {
        $this->current = call_user_func($this->f, $this->initial);
    }

    public function next()
    {
        $this->current = call_user_func($this->f, $this->current[1]);
    }

    public function valid()
    {
        return is_array($this->current);
    }
}
