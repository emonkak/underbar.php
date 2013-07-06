<?php
/**
 * This file is part of the Underbar.php package.
 *
 * Copyright (C) 2013 Shota Nozaki <emonkak@gmail.com>
 *
 * Licensed under the MIT License
 */

namespace Underbar\Iterator;

class LimitIterator extends \LimitIterator
{
    public function rewind()
    {
        try {
            // Ignore OutOfBoundsException
            parent::rewind();
        } catch (\OutOfBoundsException $e) {
        }
    }
}
