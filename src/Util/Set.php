<?php
/**
 * This file is part of the Underbar.php package.
 *
 * Copyright (C) 2013 Shota Nozaki <emonkak@gmail.com>
 *
 * Licensed under the MIT License
 */

namespace Underbar\Util;

class Set implements \Iterator
{
    /**
     * @var array
     */
    private $hashTable = array();

    /**
     * @var array
     */
    private $list;

    /**
     * Add the element to this set.
     *
     * @param mixed $element
     * @return boolean
     */
    public function add($element)
    {
        if (!$this->contains($element)) {
            $hash = $this->hash($element);
            $this->hashTable[$hash][] = $element;
            return true;
        }
        return false;
    }

    /**
     * Add all elements to this set.
     *
     * @param array|\Traversable $elements
     * @return boolean
     */
    public function addAll($elements)
    {
        foreach ($elements as $element) {
            $this->add($element);
        }
    }

    /**
     * Returns true if this set contains the element.
     *
     * @param mixed $element
     * @return boolean
     */
    public function contains($element)
    {
        $hash = $this->hash($element);
        return isset($this->hashTable[$hash])
               && in_array($element, $this->hashTable[$hash], true);
    }

    /**
     * Delete the element from this set.
     *
     * @param mixed $element
     * @return boolean
     */
    public function remove($element)
    {
        $hash = $this->hash($element);
        if (isset($this->hashTable[$hash])) {
            $i = array_search($element, $this->hashTable[$hash], true);
            if ($i !== false) {
                unset($this->hashTable[$hash][$i]);
                return true;
            }
        }
        return false;
    }

    /**
     * @see Iterator
     * @return mixed
     */
    public function current()
    {
        return current($this->list);
    }

    /**
     * @see Iterator
     * @return mixed
     */
    public function key()
    {
        return key($this->hashTable);
    }

    /**
     * @see Iterator
     * @return void
     */
    public function next()
    {
        next($this->list);
        if (key($this->list) === null) {
            next($this->hashTable);
            $this->fetchNextList();
        }
    }

    /**
     * @see Iterator
     * @return mixed
     */
    public function rewind()
    {
        reset($this->hashTable);
        $this->fetchNextList();
    }

    /**
     * @see Iterator
     * @return boolean
     */
    public function valid()
    {
        return is_array($this->list) && key($this->list) !== null;
    }

    /**
     * Calculate hash of the element
     *
     * @param mixed $element
     * @return string
     */
    private function hash($element)
    {
        return is_object($element)
             ? spl_object_hash($element) : sha1(serialize($element));
    }

    /**
     * @return void
     */
    private function fetchNextList()
    {
        while (is_array($this->list = current($this->hashTable))) {
            if (empty($this->list)) {
                next($this->hashTable);
            } else {
                reset($this->list);
                break;
            }
        }
    }
}
