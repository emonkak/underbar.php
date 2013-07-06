<?php
/**
 * This file is part of the Underbar.php package.
 *
 * Copyright (C) 2013 Shota Nozaki <emonkak@gmail.com>
 *
 * Licensed under the MIT License
 */

namespace Underbar\Iterator;

class DropWhileIterator extends \FilterIterator
{
    private $accepted = false;
    private $f;

    public function __construct($xs, $f)
    {
        parent::__construct($xs);
        $this->f = $f;
    }

    public function accept()
    {
        if (!$this->accepted) {
            $this->accepted = !call_user_func(
                $this->f,
                $this->current(),
                $this->key(),
                $this
            );
        }
        return $this->accepted;
    }

    public function rewind()
    {
        $this->accepted = false;
        parent::rewind();
    }
}
