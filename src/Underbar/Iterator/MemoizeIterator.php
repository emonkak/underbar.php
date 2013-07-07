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
    private $cache;

    public function __construct($xs)
    {
        parent::__construct($xs, self::TOSTRING_USE_KEY | self::FULL_CACHE);
        parent::rewind();
    }

    public function rewind()
    {
        $this->cache = $this->getCache();
        if (empty($this->cache)) {
            parent::rewind();
        }
    }

    public function valid()
    {
        return !empty($this->cache) || parent::valid();
    }

    public function current()
    {
        return empty($this->cache)
            ? parent::current()
            : current($this->cache);
    }

    public function key()
    {
        return empty($this->cache)
            ? parent::key()
            : key($this->cache);
    }

    public function next()
    {
        if (empty($this->cache)) {
            parent::next();
            return;
        }

        next($this->cache);
        if (key($this->cache) === null) {
            $this->cache = null;
            parent::next();
        }
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
