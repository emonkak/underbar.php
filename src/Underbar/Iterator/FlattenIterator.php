<?php
/**
 * This file is part of the Underbar.php package.
 *
 * Copyright (C) 2013 Shota Nozaki <emonkak@gmail.com>
 *
 * Licensed under the MIT License
 */

namespace Underbar\Iterator;

class FlattenIterator extends \RecursiveIteratorIterator
{
    public function __construct($xs, $shallow)
    {
        parent::__construct(
            new FlattenInnerIterator($xs),
            $shallow ? self::SELF_FIRST : self::LEAVES_ONLY
        );
        $this->setMaxDepth($shallow ? 1 : -1);
    }

    public function next()
    {
        parent::next();
        $this->fetchIfShallow();
    }

    public function rewind()
    {
        parent::rewind();
        $this->fetchIfShallow();
    }

    protected function fetchIfShallow()
    {
        if (($maxDepth = $this->getMaxDepth()) > 0) {
            $current = $this->current();
            while ((is_array($current) || $current instanceof \Traversable)
                   && $this->getDepth() < $maxDepth) {
                parent::next();
            }
        }
    }
}
