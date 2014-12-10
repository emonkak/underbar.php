<?php
/**
 * This file is part of the Underbar.php package.
 *
 * Copyright (C) 2013 Shota Nozaki <emonkak@gmail.com>
 *
 * Licensed under the MIT License
 */

namespace Underbar\Util;

use Underbar\Comparer\IEqualityComparer;
use Underbar\Iterator\FlattenIterator;

class Set implements \IteratorAggregate
{
    /**
     * @var IEqualityComparer
     */
    private $equalityComparer;

    /**
     * @var array
     */
    private $hashTable = [];

    /**
     * @param IEqualityComparer $equalityComparer
     */
    public function __construct(IEqualityComparer $equalityComparer)
    {
        $this->equalityComparer = $equalityComparer;
    }

    /**
     * Add the element to this set.
     *
     * @param mixed $element
     * @return boolean
     */
    public function add($element)
    {
        $hash = $this->equalityComparer->hash($element);
        if (isset($this->hashTable[$hash])) {
            foreach ($this->hashTable[$hash] as $other) {
                if ($this->equalityComparer->equals($element, $other)) {
                    return false;
                }
            }
        }
        $this->hashTable[$hash][] = $element;
        return true;
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
        $hash = $this->equalityComparer->hash($element);
        if (isset($this->hashTable[$hash])) {
            foreach ($this->hashTable[$hash] as $other) {
                if ($this->equalityComparer->equals($element, $other)) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Delete the element from this set.
     *
     * @param mixed $element
     * @return boolean
     */
    public function remove($element)
    {
        $hash = $this->equalityComparer->hash($element);
        if (isset($this->hashTable[$hash])) {
            foreach ($this->hashTable[$hash] as $i => $other) {
                if ($this->equalityComparer->equals($element, $other)) {
                    unset($this->hashTable[$hash][$i]);
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * @see \IteratorAggregate
     * @return \Traversable
     */
    public function getIterator()
    {
        return new FlattenIterator(new \ArrayIterator($this->hashTable), true);
    }
}
