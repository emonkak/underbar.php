<?php

namespace Underbar;

use Underbar\Comparer\ComparerResolver;
use Underbar\Iterator\ParallelIterator;
use Underbar\Predicate\PredicateResolver;
use Underbar\Selector\KeySelectorResolver;
use Underbar\Selector\SelectorResolver;
use Underbar\Util\Iterators;

trait Enumerable
{
    abstract public function getSource();

    abstract public function getProvider();

    public function getIterator()
    {
        return Iterators::create($this->getSource());
    }

    /**
     * @param callable $f
     */
    public function each(callable $f)
    {
        $xs = $this->getSource();
        foreach ($xs as $k => $x) {
            call_user_func($f, $x, $k, $xs);
        }
    }

    /**
     * @param mixed $valueSelector (value, key, source) -> value
     * @param mixed $keySelector (value, key, source) -> key
     * @return Collection
     */
    public function map($valueSelector, $keySelector = null)
    {
        $xs = $this->getSource();
        $valueSelector = $this->resolveSelector($valueSelector);
        $keySelector = $this->resolveKeySelector($keySelector);
        return $this->newCollection($this->getProvider()->map($xs, $valueSelector, $keySelector));
    }

    /**
     * @param mixed $valueSelector (value, key, source) -> values
     * @param mixed $keySelector (value, key, source) -> key
     * @return Collection
     */
    public function concatMap($valueSelector, $keySelector = null)
    {
        $xs = $this->getSource();
        $valueSelector = $this->resolveSelector($valueSelector);
        $keySelector = $this->resolveKeySelector($keySelector);
        return $this->newCollection($this->getProvider()->concatMap($xs, $valueSelector, $keySelector));
    }

    public function parMap(callable $f, $workers = 4, $timeout = null)
    {
        if ($workers <= 0) {
            throw new \InvalidArgumentException('The Worker must be at least one.');
        }
        $it = new ParallelIterator($f, $timeout);
        for ($i = 0; $i < $workers; $i++) {
            $it->fork();
        }
        $it->pushAll($this->getSource());
        return $this->newCollection(Iterators::memoize($it));
    }

    public function reduce(callable $f, $acc)
    {
        $xs = $this->getSource();
        foreach ($xs as $k => $x) {
            $acc = call_user_func($f, $acc, $x, $k, $xs);
        }
        return $acc;
    }

    public function reduceRight(callable $f, $acc)
    {
        return $this->reverse()->reduce($f, $acc);
    }

    public function find($f)
    {
        $xs = $this->getSource();
        foreach ($xs as $k => $x) {
            if (call_user_func($f, $x, $k, $xs)) {
                return $x;
            }
        }
    }

    public function filter($predicate)
    {
        $xs = $this->getSource();
        $predicate = $this->resolvePredicate($predicate);
        return $this->newCollection($this->getProvider()->filter($xs, $predicate));
    }

    public function where($properties)
    {
        return $this->filter(function($x) use ($properties) {
            foreach ($properties as $key => $value) {
                if (!((isset($x->$key) && $x->$key == $value)
                      || (isset($x[$key]) && $x[$key] == $value))) {
                    return false;
                }
            }
            return true;
        });
    }

    public function findWhere($properties)
    {
        return $this->find(function($x) use ($properties) {
            foreach ($properties as $key => $value) {
                if (!((isset($x->$key) && $x->$key == $value)
                      || (isset($x[$key]) && $x[$key] == $value))) {
                    return false;
                }
            }
            return true;
        });
    }

    public function reject($predicate)
    {
        $xs = $this->getSource();
        $predicate = $this->resolvePredicate($predicate);
        $predicate = function($x, $k, $xs) use ($predicate) {
            return !call_user_func($predicate, $x, $k, $xs);
        };
        return $this->newCollection($this->getProvider()->filter($xs, $predicate));
    }

    public function every($predicate = null)
    {
        $xs = $this->getSource();
        $predicate = $this->resolvePredicate($predicate);

        foreach ($xs as $k => $x) {
            if (!call_user_func($predicate, $x, $k, $xs)) {
                return false;
            }
        }

        return true;
    }

    public function some($predicate = null)
    {
        $xs = $this->getSource();
        $predicate = $this->resolvePredicate($predicate);

        foreach ($xs as $k => $x) {
            if (call_user_func($predicate, $x, $k, $xs)) {
                return true;
            }
        }

        return false;
    }

    public function contains($target)
    {
        foreach ($this->getSource() as $x) {
            if ($x === $target) {
                return true;
            }
        }
        return false;
    }

    public function invoke($method)
    {
        $args = array_slice(func_get_args(), 1);
        return $this->map(function($x) use ($method, $args) {
            return call_user_func_array([$x, $method], $args);
        });
    }

    public function pluck($property)
    {
        return $this->map(function($x) use ($property) {
            if (isset($x->$property)) {
                return $x->$property;
            } elseif (isset($x[$property])) {
                return $x[$property];
            } else {
                return null;
            }
        });
    }

    public function max($selector = null)
    {
        $xs = $this->getSource();
        $selector = $this->resolveSelector($selector);
        $computed = -INF;
        $result = -INF;

        foreach ($xs as $k => $x) {
            $current = call_user_func($selector, $x, $k, $xs);
            if ($current > $computed) {
                $computed = $current;
                $result = $x;
            }
        }

        return $result;
    }

    public function min($selector = null)
    {
        $xs = $this->getSource();
        $selector = $this->resolveSelector($selector);
        $computed = INF;
        $result = INF;

        foreach ($xs as $k => $x) {
            $current = call_user_func($selector, $x, $k, $xs);
            if ($current < $computed) {
                $computed = $current;
                $result = $x;
            }
        }

        return $result;
    }

    public function sum($selector = null)
    {
        $xs = $this->getSource();
        $selector = $this->resolveSelector($selector);
        $acc = 0;
        foreach ($xs as $k => $x) {
            $acc += call_user_func($selector, $x, $k, $xs);
        }
        return $acc;
    }

    public function product($selector = null)
    {
        $xs = $this->getSource();
        $selector = $this->resolveSelector($selector);
        $acc = 1;
        foreach ($xs as $k => $x) {
            $acc *= call_user_func($selector, $x, $k, $xs);
        }
        return $acc;
    }

    public function average($selector = null)
    {
        $xs = $this->getSource();
        $selector = $this->resolveSelector($selector);
        $total = 0.0;
        $n = 0;
        foreach ($xs as $k => $x) {
            $total += call_user_func($selector, $x, $k, $xs);
            $n++;
        }
        return $n > 0 ? $total / $n : INF;
    }

    public function sortBy($selector = null)
    {
        $selector = $this->resolveSelector($selector);
        return $this->newLazyCollection(function() use ($selector) {
            $xs = $this->getSource();
            $result = [];

            foreach ($xs as $k => $x) {
                $result[] = [
                    'value' => $x,
                    'key' => $k,
                    'criteria' => call_user_func($selector, $x, $k, $xs),
                ];
            }

            usort($result, function($left, $right) {
                $a = $left['criteria'];
                $b = $right['criteria'];
                if ($a !== $b) {
                    return $a < $b ? -1 : 1;
                } else {
                    return $left['key'] < $right['key'] ? -1 : 1;
                }
            });

            return $this->newCollection($result)->pluck('value');
        });
    }

    public function groupBy($selector = null)
    {
        $selector = $this->resolveSelector($selector);
        return $this->newLazyCollection(function() use ($selector) {
            $xs = $this->getSource();
            $result = [];

            foreach ($xs as $k => $x) {
                $key = call_user_func($selector, $x, $k, $xs);
                $result[$key][] = $x;
            }

            return $result;
        });
    }

    public function indexBy($selector = null)
    {
        $xs = $this->getSource();
        $valueSelector = $this->resolveSelector(null);
        $keySelector = $this->resolveSelector($selector);
        return $this->newCollection($this->getProvider()->map($xs, $valueSelector, $keySelector));
    }

    public function countBy($selector = null)
    {
        $selector = $this->resolveSelector($selector);
        return $this->newLazyCollection(function() use ($selector) {
            $xs = $this->getSource();
            $result = [];

            foreach ($xs as $k => $x) {
                $key = call_user_func($selector, $x, $k, $xs);
                if (isset($result[$key])) {
                    $result[$key]++;
                } else {
                    $result[$key] = 1;
                }
            }

            return $result;
        });
    }

    public function shuffle()
    {
        return $this->newLazyCollection(function() {
            $xs = $this->getSource();
            $array = Iterators::toArray($xs);
            shuffle($array);
            return $array;
        });
    }

    public function sample($n = null)
    {
        $xs = $this->getSource();
        if ($n === null) {
            $array = Iterators::toArray($xs);
            if (empty($array)) {
                return null;
            }
            $key = array_rand($array);
            return $array[$key];
        } else {
            return $this->newCollection($this->getProvider()->sample($xs, $n));
        }
    }

    public function memoize()
    {
        $xs = $this->getSource();
        return $this->newCollection($this->getProvider()->memoize($xs));
    }

    public function toArray()
    {
        return Iterators::toArray($this->getSource());
    }

    public function toArrayRec($depth = null)
    {
        return Iterators::toArrayRec($this->getSource(), $depth);
    }

    public function toList()
    {
        return Iterators::toList($this->getSource());
    }

    public function toListRec($depth = null)
    {
        return Iterators::toListRec($this->getSource(), $depth);
    }

    public function size()
    {
        return Iterators::count($this->getSource());
    }

    public function first($n = null)
    {
        if ($n !== null) {
            $xs = $this->getSource();
            return $this->newCollection($this->getProvider()->take($xs, $n));
        } else {
            foreach ($this->getSource() as $x) {
                return $x;
            }
            throw new \RuntimeException('This collection is empty');
        }
    }

    public function firstOrElse($default)
    {
        foreach ($this->getSource() as $x) {
            return $x;
        }
        return $default;
    }

    public function initial($n = 1)
    {
        $xs = $this->getSource();
        return $this->newCollection($this->getProvider()->initial($xs, $n));
    }

    public function last($n = null)
    {
        if ($n !== null) {
            $xs = $this->getSource();
            return $this->newCollection($this->getProvider()->takeRight($xs, $n));
        } else {
            foreach ($this->getSource() as $x) {
            }
            if (isset($x)) {
                return $x;
            }
            throw new \RuntimeException('This collection is empty');
        }
    }

    public function lastOrElse($default)
    {
        $x = $default;
        foreach ($this->getSource() as $x) {
        }
        return $x;
    }

    public function rest($n = 1)
    {
        $xs = $this->getSource();
        return $this->newCollection($this->getProvider()->drop($xs, $n));
    }

    public function takeWhile($predicate)
    {
        $xs = $this->getSource();
        $predicate = $this->resolvePredicate($predicate);
        return $this->newCollection($this->getProvider()->takeWhile($xs, $predicate));
    }

    public function dropWhile($predicate)
    {
        $xs = $this->getSource();
        $predicate = $this->resolvePredicate($predicate);
        return $this->newCollection($this->getProvider()->dropWhile($xs, $predicate));
    }

    public function compact()
    {
        return $this->filter($this->resolveSelector(null));
    }

    public function flatten($shallow = false)
    {
        $xss = $this->getSource();
        return $this->newCollection($this->getProvider()->flatten($xss, $shallow));
    }

    public function without()
    {
        return $this->difference(func_get_args());
    }

    public function union()
    {
        return call_user_func_array([$this, 'concat'], func_get_args())->uniq();
    }

    public function intersection()
    {
        $xs = $this->getSource();
        $others = func_get_args();
        return $this->newCollection($this->getProvider()->intersection($xs, $others));
    }

    public function difference()
    {
        $yss = func_get_args();
        return $this->filter(function($x) use ($yss) {
            foreach ($yss as $ys) {
                foreach ($ys as $y) {
                    if ($x === $y) {
                        return false;
                    }
                }
            }
            return true;
        });
    }

    public function uniq($selector = null)
    {
        $selector = $this->resolveSelector($selector);
        $xs = $this->getSource();
        return $this->newCollection($this->getProvider()->uniq($xs, $selector));
    }

    public function zip()
    {
        $xss = array_merge([$this->getSource()], func_get_args());
        return $this->newCollection($this->getProvider()->zip($xss));
    }

    public function unzip()
    {
        $xss = $this->getSource();
        return $this->newCollection($this->getProvider()->zip($xss));
    }

    public function cycle($n = null)
    {
        $xs = $this->getSource();
        return $this->newCollection($this->getProvider()->cycle($xs, $n));
    }

    public function reverse()
    {
        return $this->newLazyCollection(function() {
            $xs = $this->getSource();
            return array_reverse(Iterators::toArray($xs));
        });
    }

    public function sort($comparer = null)
    {
        return $this->newLazyCollection(function() use ($comparer) {
            $xs = Iterators::toArray($this->getSource());
            $comparer = $this->resolveComparer($comparer);
            usort($xs, $comparer);
            return $xs;
        });
    }

    public function concat()
    {
        $xss = array_merge([$this->getSource()], func_get_args());
        return $this->newCollection($this->getProvider()->concat($xss));
    }

    public function object($values = null)
    {
        return $this->newLazyCollection(function() use ($values) {
            $result = [];
            $xs = $this->getSource();
            if ($values !== null) {
                $values = Iterators::create($values);
                $values->rewind();
                foreach ($xs as $key) {
                    if (!$values->valid()) {
                        break;
                    }

                    $result[$key] = $values->current();
                    $values->next();
                }
            } else {
                foreach ($xs as $x) {
                    $result[$x[0]] = $x[1];
                }
            }
            return $result;
        });
    }

    public function indexOf($value, $isSorted = 0)
    {
        $xs = Iterators::toArray($this->getSource());

        if ($isSorted === true) {
            $i = $this->newCollection($xs)->sortedIndex($value);
            return (isset($xs[$i]) && $xs[$i] === $value) ? $i : -1;
        } else {
            $l = count($xs);
            $i = $isSorted < 0 ? max(0, $l + $isSorted) : $isSorted;
            for (; $i < $l; $i++) {
                if (isset($xs[$i]) && $xs[$i] === $value) {
                    return $i;
                }
            }
        }

        return -1;
    }

    public function lastIndexOf($x, $fromIndex = null)
    {
        $xs = Iterators::toArray($this->getSource());
        $l = count($xs);
        $i = $fromIndex !== null ? min($l, $fromIndex) : $l;

        while ($i-- > 0) {
            if (isset($xs[$i]) && $xs[$i] === $x) {
                return $i;
            }
        }

        return -1;
    }

    public function sortedIndex($value, $selector = null)
    {
        $xs = Iterators::toArray($this->getSource());
        $selector = $this->resolveSelector($selector);
        $value = call_user_func($selector, $value, null, []);

        $low = 0;
        $high = count($xs);

        while ($low < $high) {
            $mid = ($low + $high) >> 1;
            if (call_user_func($selector, $xs[$mid], $mid, $xs) < $value) {
                $low = $mid + 1;
            } else {
                $high = $mid;
            }
        }

        return $low;
    }

    public function join($separator = ',')
    {
        $str = '';
        foreach ($this->getSource() as $x) {
            $str .= $x . $separator;
        }
        return $separator === '' ? $str : substr($str, 0, -strlen($separator));
    }

    public function keys()
    {
        return $this->map(function($x, $k) {
            return $k;
        });
    }

    public function values()
    {
        $xs = $this->getSource();
        return $this->newCollection($this->getProvider()->renum($xs));
    }

    public function pairs()
    {
        return $this->map(function($x, $k) {
            return [$k, $x];
        });
    }

    public function invert()
    {
        return $this->map(
            function($x, $k) { return $k; },
            function($x, $k) { return $x; }
        );
    }

    public function extend($destination)
    {
        $sources = func_get_args();
        return $this->newLazyCollection(function() use ($sources) {
            $destination = Iterators::toArray($this->getSource());
            foreach ($sources as $xs) {
                foreach ($xs as $k => $x) {
                    $destination[$k] = $x;
                }
            }
            return $destination;
        });
    }

    public function pick()
    {
        $keys = func_get_args();
        $whitelist = [];

        foreach ($keys as $key) {
            if (Iterators::isTraversable($key)) {
                foreach ($key as $k) {
                    $whitelist[$k] = 0;
                }
            } else {
                $whitelist[$key] = 0;
            }
        }

        return $this->filter(function($x, $k) use ($whitelist) {
            return isset($whitelist[$k]);
        });
    }

    public function omit()
    {
        $keys = func_get_args();
        $blacklist = [];

        foreach ($keys as $key) {
            if (Iterators::isTraversable($key)) {
                foreach ($key as $k) {
                    $blacklist[$k] = 0;
                }
            } else {
                $blacklist[$key] = 0;
            }
        }

        return $this->filter(function($x, $k) use ($blacklist) {
            return !isset($blacklist[$k]);
        });
    }

    public function defaults()
    {
        $defaults = func_get_args();
        return $this->newLazyCollection(function() use ($defaults) {
            $xs = Iterators::toArray($this->getSource());
            foreach ($defaults as $default) {
                foreach ($default as $k => $x) {
                    if (!isset($xs[$k])) {
                        $xs[$k] = $x;
                    }
                }
            }
            return $xs;
        });
    }

    public function isEmpty()
    {
        $xs = $this->getSource();
        return Iterators::isEmpty($xs);
    }

    protected function getComparerResolver()
    {
        return ComparerResolver::getInstance();
    }

    protected function getSelectorResolver()
    {
        return SelectorResolver::getInstance();
    }

    protected function getKeySelectorResolver()
    {
        return KeySelectorResolver::getInstance();
    }

    protected function getPredicateResolver()
    {
        return PredicateResolver::getInstance();
    }

    private function newCollection($source)
    {
        return new Collection($source, $this->getProvider());
    }

    private function newLazyCollection($factory)
    {
        return new Collection(Iterators::createLazy($factory), $this->getProvider());
    }

    private function resolveComparer($comparer)
    {
        return $this->getComparerResolver()->resolveComparer($comparer);
    }

    private function resolveSelector($selector)
    {
        return $this->getSelectorResolver()->resolveSelector($selector);
    }

    private function resolveKeySelector($selector)
    {
        return $this->getKeySelectorResolver()->resolveKeySelector($selector);
    }

    private function resolvePredicate($predicate)
    {
        return $this->getPredicateResolver()->resolvePredicate($predicate);
    }
}
