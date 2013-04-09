<?php

namespace Underbar\Internal;

class MemorizeIterator extends \CachingIterator
{
    public function __construct($xs)
    {
        parent::__construct($xs, self::TOSTRING_USE_KEY | self::FULL_CACHE);
        $this->rewind();
    }

    public function offsetExists($offset)
    {
        if (parent::offsetExists($offset)) {
            return true;
        }

        do {
            if ($this->key() === $offset) {
                return true;
            }
            $this->next();
        } while ($this->valid());

        return false;
    }

    public function offsetGet($offset)
    {
        if (parent::offsetExists($offset)) {
            return parent::offsetGet($offset);
        }

        do {
            if ($this->key() === $offset) {
                return $this->current();
            }
            $this->next();
        } while ($this->valid());

        return null;
    }
}

// __END__
// vim: expandtab softtabstop=4 shiftwidth=4
