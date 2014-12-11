<?php

namespace Underbar\Provider;

use Underbar\Comparer\EqualityComparer;
use Underbar\Util\Iterators;
use Underbar\Util\Set;
use Underbar\Util\Singleton;

class GeneratorProvider implements ICollectionProvider
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
        return Iterators::createLazy(function() use ($xs, $valueSelector, $keySelector) {
            foreach ($xs as $k => $x) {
                $key = call_user_func($keySelector, $x, $k, $xs);
                yield $key => call_user_func($valueSelector, $x, $k, $xs);
            }
        });
    }

    /**
     * {@inheritdoc}
     */
    public function concatMap($xs, callable $selector)
    {
        return Iterators::createLazy(function() use ($xs, $selector) {
            foreach ($xs as $k1 => $x1) {
                foreach (call_user_func($selector, $x1, $k1, $xs) as $x2) {
                    yield $k1 => $x2;
                }
            }
        });
    }

    /**
     * {@inheritdoc}
     */
    public function filter($xs, callable $predicate)
    {
        return Iterators::createLazy(function() use ($xs, $predicate) {
            foreach ($xs as $k => $x) {
                if (call_user_func($predicate, $x, $k, $xs)) {
                    yield $k => $x;
                }
            }
        });
    }

    /**
     * {@inheritdoc}
     */
    public function sample($xs, $n)
    {
        return Iterators::createLazy(function() use ($xs, $n) {
            $array = Iterators::toArray($xs);
            while ($n-- > 0 && !empty($array)) {
                $key = array_rand($array);
                yield $array[$key];
                unset($array[$key]);
            }
        });
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
    public function initial($xs, $n)
    {
        return Iterators::createLazy(function() use ($xs, $n) {
            $queue = new \SplQueue();
            foreach ($xs as $k => $x) {
                $queue->enqueue($x);
                if ($n > 0) {
                    $n--;
                } else {
                    yield $k => $queue->dequeue();
                }
            }
        });
    }

    /**
     * {@inheritdoc}
     */
    public function take($xs, $n)
    {
        return Iterators::createLazy(function() use ($xs, $n) {
            foreach ($xs as $k => $x) {
                if (--$n < 0) {
                    break;
                }
                yield $k => $x;
            }
        });
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
        return Iterators::createLazy(function() use ($xs, $n) {
            foreach ($xs as $i => $x) {
                if ($n > 0) {
                    $n--;
                } else {
                    yield $i => $x;
                }
            }
        });
    }

    /**
     * {@inheritdoc}
     */
    public function takeWhile($xs, callable $predicate)
    {
        return Iterators::createLazy(function() use ($xs, $predicate) {
            foreach ($xs as $k => $x) {
                if (!call_user_func($predicate, $x, $k, $xs)) {
                    break;
                }
                yield $k => $x;
            }
        });
    }

    /**
     * {@inheritdoc}
     */
    public function dropWhile($xs, callable $predicate)
    {
        return Iterators::createLazy(function() use ($xs, $predicate) {
            $accepted = false;
            foreach ($xs as $k => $x) {
                if ($accepted || ($accepted = !call_user_func($predicate, $x, $k, $xs))) {
                    yield $k => $x;
                }
            }
        });
    }

    /**
     * {@inheritdoc}
     */
    public function flatten($xs, $shallow)
    {
        return Iterators::createLazy(function() use ($xs, $shallow) {
            foreach ($xs as $i => $child) {
                if (Iterators::isTraversable($child)) {
                    if ($shallow) {
                        foreach ($child as $j => $x) {
                            yield $j => $x;
                        }
                    } else {
                        foreach ($this->flatten($child, $shallow) as $j => $x) {
                            yield $j => $x;
                        }
                    }
                } else {
                    yield $i => $child;
                }
            }
        });
    }

    /**
     * {@inheritdoc}
     */
    public function intersection($xs, $others)
    {
        return Iterators::createLazy(function() use ($xs, $others) {
            $set = new Set(EqualityComparer::getInstance());

            foreach ($others as $other) {
                $set->addAll($other);
            }

            foreach ($xs as $k => $x) {
                if ($set->remove($x)) {
                    yield $k => $x;
                }
            }
        });
    }

    /**
     * {@inheritdoc}
     */
    public function uniq($xs, callable $selector)
    {
        return Iterators::createLazy(function() use ($xs, $selector) {
            $set = new Set(EqualityComparer::getInstance());

            foreach ($xs as $k => $x) {
                if ($set->add(call_user_func($selector, $x, $k, $xs))) {
                    yield $k => $x;
                }
            }
        });
    }

    /**
     * {@inheritdoc}
     */
    public function zip($xss)
    {
        return Iterators::createLazy(function() use ($xss) {
            $iters = $zipped = [];
            $loop = true;

            foreach ($xss as $xs) {
                $iters[] = $it = Iterators::create($xs);
                $it->rewind();
                $loop = $loop && $it->valid();
                $zipped[] = $it->current();
            }

            if (!empty($zipped)) while ($loop) {
                yield $zipped;
                $zipped = [];
                $loop = true;
                foreach ($iters as $it) {
                    $it->next();
                    $zipped[] = $it->current();
                    $loop = $loop && $it->valid();
                }
            }
        });
    }

    /**
     * {@inheritdoc}
     */
    public function concat($xss)
    {
        return Iterators::createLazy(function() use ($xss) {
            foreach ($xss as $xs) {
                foreach ($xs as $k => $x) {
                    yield $k => $x;
                }
            }
        });
    }

    /**
     * {@inheritdoc}
     */
    public function cycle($xs, $n)
    {
        return Iterators::createLazy(function() use ($xs, $n) {
            if ($n === null) {
                while (true) {
                    foreach ($xs as $k => $x) {
                        yield $k => $x;
                    }
                }
            } else {
                while ($n-- > 0) {
                    foreach ($xs as $k => $x) {
                        yield $k => $x;
                    }
                }
            }
        });
    }

    /**
     * {@inheritdoc}
     */
    public function range($start, $stop, $step)
    {
        return Iterators::createLazy(function() use ($start, $stop, $step) {
            $l = max(ceil(($stop - $start) / $step), 0);
            $n = $start;
            for ($i = 0; $i < $l; $i++) {
                yield $i => $n;
                $n += $step;
            }
        });
    }

    /**
     * {@inheritdoc}
     */
    public function repeat($value, $n)
    {
        return Iterators::createLazy(function() use ($value, $n) {
            if ($n === null) {
                while (true) {
                    yield $value;
                }
            } else {
                while ($n-- > 0) {
                    yield $value;
                }
            }
        });
    }

    /**
     * {@inheritdoc}
     */
    public function renum($xs)
    {
        $i = 0;
        foreach ($xs as $x) {
           yield $i++ => $x; 
        }
    }

    /**
     * {@inheritdoc}
     */
    public function iterate($initial, callable $f)
    {
        return Iterators::createLazy(function() use ($initial, $f) {
            $acc = $initial;
            while (true) {
                yield $acc;
                $acc = call_user_func($f, $acc);
            }
        });
    }
}
