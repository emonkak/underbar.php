<?php
/**
 * This file is part of the Underbar.php package.
 *
 * Copyright (C) 2013 Shota Nozaki <emonkak@gmail.com>
 *
 * Licensed under the MIT License
 */

namespace Underbar\Iterator;

class GroupByIterator extends \IteratorIterator
{
    private $key;

    private $current;

    private $next;

    private $nextKey;

    private $f;

    public function __construct($xs, $f)
    {
        parent::__construct($xs);
        $this->f = $f;
    }

    public function current()
    {
        return $this->current;
    }

    public function key()
    {
        return $this->key;
    }

    public function next()
    {
        parent::next();
        $this->current = $this->next;
        $this->key = $this->nextKey;
        $this->next = array();
        $this->fill();
    }

    public function rewind()
    {
        parent::rewind();
        $this->current = $this->next = array();
        $this->key = $this->nextKey = null;
        $this->fill();
    }

    public function valid()
    {
        return !empty($this->current) || parent::valid();
    }

    private function fill()
    {
        while (parent::valid()) {
            $key = call_user_func(
                $this->f,
                $value = parent::current(),
                parent::key(),
                $this
            );
            if ($key !== $this->nextKey) {
                if (!empty($this->current)) {
                    $this->key = $this->nextKey;
                    $this->next[] = $value;
                    $this->nextKey = $key;
                    break;
                }
                $this->nextKey = $key;
            }
            $this->current[] = $value;
            parent::next();
        }
    }
}
