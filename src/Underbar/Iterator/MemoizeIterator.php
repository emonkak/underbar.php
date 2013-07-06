<?php
/**
 * This file is part of the Underbar.php package.
 *
 * Copyright (C) 2013 Shota Nozaki <emonkak@gmail.com>
 *
 * Licensed under the MIT License
 */

namespace Underbar\Iterator;

class MemoizeIterator extends \CachingIterator
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
