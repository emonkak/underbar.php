<?php
/**
 * This file is part of the Underbar.php package.
 *
 * Copyright (C) 2013 Shota Nozaki <emonkak@gmail.com>
 *
 * Licensed under the MIT License
 */

namespace Underbar\Iterator;

class FilterIterator extends \FilterIterator
{
    private $f;

    public function __construct($xs, $f)
    {
        parent::__construct($xs);
        $this->f = $f;
    }

    public function accept()
    {
        return call_user_func(
            $this->f,
            $this->current(),
            $this->key(),
            $this
        );
    }
}
