<?php

namespace Underbar\Provider;

use Underbar\Comparer\EqualityComparer;
use Underbar\Iterator\ConcatMapIterator;
use Underbar\Iterator\DropWhileIterator;
use Underbar\Iterator\FlattenIterator;
use Underbar\Iterator\InitialIterator;
use Underbar\Iterator\IntersectIterator;
use Underbar\Iterator\IterateIterator;
use Underbar\Iterator\MapIterator;
use Underbar\Iterator\MemoizeIterator;
use Underbar\Iterator\RangeIterator;
use Underbar\Iterator\RenumIterator;
use Underbar\Iterator\RepeatIterator;
use Underbar\Iterator\SampleIterator;
use Underbar\Iterator\TakeWhileIterator;
use Underbar\Iterator\UniqueIterator;
use Underbar\Iterator\ZipIterator;
use Underbar\Util\Iterators;
use Underbar\Util\Singleton;

class IteratorProvider implements ICollectionProvider
{
    use Singleton;

    private function __construct()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function map($xs, callable $valueSelector, callable $keySelector)
    {
        return new MapIterator(Iterators::create($xs), $valueSelector, $keySelector);
    }

    /**
     * {@inheritdoc}
     */
    public function concatMap($xs, callable $selector)
    {
        $inner = new ConcatMapIterator(Iterators::create($xs), $selector);
        return new \RecursiveIteratorIterator($inner);
    }

    /**
     * {@inheritdoc}
     */
    public function filter($xs, callable $predicate)
    {
        return new \CallbackFilterIterator(Iterators::create($xs), $predicate);
    }

    /**
     * {@inheritdoc}
     */
    public function sample($xs, $n)
    {
        return new SampleIterator(Iterators::toArray($xs), $n);
    }

    /**
     * {@inheritdoc}
     */
    public function memoize($xs)
    {
        return Iterators::memoize($xs);
    }

    /**
     * {@inheritdoc}
     */
    public function initial($xs, $n = 1)
    {
        return new InitialIterator(Iterators::create($xs), $n);
    }

    /**
     * {@inheritdoc}
     */
    public function take($xs, $n)
    {
        return $n > 0
            ? new \LimitIterator(Iterators::create($xs), 0, $n)
            : new \EmptyIterator();
    }

    /**
     * {@inheritdoc}
     */
    public function takeRight($xs, $n)
    {
        return Iterators::createLazy(function() use ($xs, $n) {
            $i = 0;
            $queue = new \SplQueue();

            if ($n > 0) {
                foreach ($xs as $x) {
                    if ($i == $n) {
                        $queue->dequeue();
                        $i--;
                    }
                    $queue->enqueue($x);
                    $i++;
                }
            }

            return $queue;
        });
    }

    /**
     * {@inheritdoc}
     */
    public function drop($xs, $n)
    {
        return new \LimitIterator(Iterators::create($xs), $n);
    }

    /**
     * {@inheritdoc}
     */
    public function takeWhile($xs, callable $predicate)
    {
        return new TakeWhileIterator(Iterators::create($xs), $predicate);
    }

    /**
     * {@inheritdoc}
     */
    public function dropWhile($xs, callable $predicate)
    {
        return new DropWhileIterator(Iterators::create($xs), $predicate);
    }

    /**
     * {@inheritdoc}
     */
    public function flatten($xs, $shallow)
    {
        $inner = new FlattenIterator(Iterators::create($xs), $shallow);
        return new \RecursiveIteratorIterator($inner);
    }

    /**
     * {@inheritdoc}
     */
    public function intersection($xs, $others)
    {
        return new IntersectIterator(
            Iterators::create($xs),
            $others,
            EqualityComparer::getInstance()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function uniq($xs, callable $selector)
    {
        return new UniqueIterator(
            Iterators::create($xs),
            $selector,
            EqualityComparer::getInstance()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function zip($xss)
    {
        $it = new ZipIterator();
        foreach ($xss as $xs) {
            $it->attach(Iterators::create($xs));
        }
        return $it;
    }

    /**
     * {@inheritdoc}
     */
    public function concat($xss)
    {
        $it = new \AppendIterator();
        foreach ($xss as $xs) {
            $it->append(Iterators::create($xs));
        }
        return $it;
    }

    /**
     * {@inheritdoc}
     */
    public function cycle($xs, $n)
    {
        $inner = Iterators::create($xs);
        if ($n === null) {
            return new \InfiniteIterator($inner);
        } else {
            // TODO: Implement CycleIterator
            $it = new \AppendIterator();
            while ($n-- > 0) {
                $it->append($inner);
            }
            return $it;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function range($start, $stop, $step)
    {
        return new RangeIterator($start, $stop, $step);
    }

    /**
     * {@inheritdoc}
     */
    public function repeat($value, $n)
    {
        if ($n === null) {
            return new \InfiniteIterator(new \ArrayIterator([$value]));
        } else {
            return new RepeatIterator($value, $n);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function renum($xs)
    {
        return new RenumIterator(Iterators::create($xs));
    }

    /**
     * {@inheritdoc}
     */
    public function iterate($initial, callable $f)
    {
        return new IterateIterator($initial, $f);
    }
}
