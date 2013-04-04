<?php

namespace Underbar\Internal;

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

// __END__
// vim: expandtab softtabstop=4 shiftwidth=4
