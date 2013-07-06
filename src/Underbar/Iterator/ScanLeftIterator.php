<?php
/**
 * This file is part of the Underbar.php package.
 *
 * Copyright (C) 2013 Shota Nozaki <emonkak@gmail.com>
 *
 * Licensed under the MIT License
 */

namespace Underbar\Iterator;

class ScanLeftIterator extends \IteratorIterator
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
    private $acc;

    public function __construct($it, $f, $initial)
    {
        parent::__construct($it);
        $this->f = $f;
        $this->initial = $initial;
    }

    public function current()
    {
        return $this->acc;
    }

    public function rewind()
    {
        parent::rewind();
        $this->acc = call_user_func($this->f, parent::current(), $this->initial);
    }

    public function next()
    {
        parent::next();
        $this->acc = call_user_func($this->f, parent::current(), $this->acc);
    }
}
