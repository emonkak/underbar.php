<?php

namespace Underbar\Provider;

interface CollectionProvider
{
    /**
     * @param array|\Traversable $xs
     * @param callable $keySelector
     * @param callable $valueSelector
     * @return array|\Iterator
     */
    public function map($xs, callable $keySelector, callable $valueSelector);

    /**
     * @param array|\Traversable $xs
     * @param callable $selector
     * @return array|\Iterator
     */
    public function concatMap($xs, callable $selector);

    /**
     * @param array|\Traversable $xs
     * @param callable $predicate
     * @return array|\Iterator
     */
    public function filter($xs, callable $predicate);

    /**
     * @param array|\Traversable $xs
     * @param int $n
     * @return array|\Iterator
     */
    public function sample($xs, $n);

    /**
     * @param array|\Traversable $xs
     * @return array|\Iterator
     */
    public function memoize($xs);

    /**
     * @param array|\Traversable $xs
     * @param int $n
     * @return array|\Iterator
     */
    public function initial($xs, $n);

    /**
     * @param array|\Traversable $xs
     * @param int $n
     * @return array|\Iterator
     */
    public function take($xs, $n);

    /**
     * @param array|\Traversable $xs
     * @param int $n
     * @return array|\Iterator
     */
    public function takeRight($xs, $n);

    /**
     * @param array|\Traversable $xs
     * @param int $n
     * @return array|\Iterator
     */
    public function drop($xs, $n);

    /**
     * @param array|\Traversable $xs
     * @param callable $predicate
     * @return array|\Iterator
     */
    public function takeWhile($xs, callable $predicate);

    /**
     * @param array|\Traversable $xs
     * @param callable $predicate
     * @return array|\Iterator
     */
    public function dropWhile($xs, callable $predicate);

    /**
     * @param array|\Traversable $xss
     * @param boolean $shallow
     * @return array|\Iterator
     */
    public function flatten($xss, $shallow);

    /**
     * @param array|\Traversable $xs
     * @param array|\Traversable $others
     * @return array|\Iterator
     */
    public function intersection($xs, $others);

    /**
     * @param array|\Traversable $xss
     * @param callable $selector
     * @return array|\Iterator
     */
    public function uniq($xs, callable $selector);

    /**
     * @param array|\Traversable $xss
     * @return array|\Iterator
     */
    public function zip($xss);

    /**
     * @param array|\Traversable $xss
     * @return array|\Iterator
     */
    public function concat($xss);

    /**
     * @param array|\Traversable $xs
     * @param int $n
     * @return array|\Iterator
     */
    public function cycle($xs, $n);

    /**
     * @param int $start
     * @param int $stop
     * @param int $step
     * @return array|\Iterator
     */
    public function range($start, $stop, $step);

    /**
     * @param array|\Traversable $xs
     * @param int $n
     * @return array|\Iterator
     */
    public function repeat($value, $n);

    /**
     * @param array|\Traversable $xs
     * @return array|\Iterator
     */
    public function renum($xs);

    /**
     * @param mixed $initial
     * @param callable $f
     * @return array|\Iterator
     */
    public function iterate($initial, callable $f);
}
