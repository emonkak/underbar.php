<?php

namespace Underscore\Lazy;

class FlattenIterator extends \RecursiveIteratorIterator
{
  public function __construct(\Traversable $array, $shallow)
  {
    parent::__construct(new FlattenIteratorInner($array),
                        $shallow ? self::SELF_FIRST : self::LEAVES_ONLY);
    $this->setMaxDepth($shallow ? 1 : -1);
  }

  public function next()
  {
    $maxDepth = $this->getMaxDepth();
    if ($maxDepth > 0) {
      do {
        parent::next();
      } while (is_array($this->current()) && $this->getDepth() < $maxDepth);
    } else {
      parent::next();
    }
  }

  public function rewind()
  {
    parent::rewind();
    $maxDepth = $this->getMaxDepth();
    if ($maxDepth > 0) {
      while (is_array($this->current()) && $this->getDepth() < $maxDepth)
        parent::next();
    }
  }
}

class FlattenIteratorInner extends \IteratorIterator implements \RecursiveIterator
{
  public function getChildren()
  {
    $current = $this->current();
    return new static(is_array($current) ? new \ArrayObject($current) : $current);
  }

  public function hasChildren()
  {
    $current = $this->current();
    return is_array($current) || $current instanceof \Traversable;
  }
}

// __END__
// vim: expandtab softtabstop=2 shiftwidth=2
