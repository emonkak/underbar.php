<?php

namespace Underbar\Iterator;

use Underbar\Strict as _;

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
        if (($maxDepth = $this->getMaxDepth()) > 0) {
            while (_::isTraversable($this->current())
                   && $this->getDepth() < $maxDepth) {
                parent::next();
            }
        }
    }

    public function rewind()
    {
        parent::rewind();
        if (($maxDepth = $this->getMaxDepth()) > 0) {
            while (_::isTraversable($this->current())
                   && $this->getDepth() < $maxDepth) {
                parent::next();
            }
        }
    }
}
