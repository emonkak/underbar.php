<?php

namespace Underbar;

trait EnumerableAliases
{
    abstract public function map($valueSelector, $keySelector = null);

    public function collect($valueSelector, $keySelector = null)
    {
        return $this->map($valueSelector, $keySelector = null);
    }

    abstract public function concatMap($valueSelector, $keySelector = null);

    public function flatMap($valueSelector, $keySelector = null)
    {
        return $this->concatMap($valueSelector, $keySelector = null);
    }

    abstract public function reduce(callable $f, $acc);

    public function inject(callable $f, $acc)
    {
        return $this->reduce($f, $acc);
    }

    public function foldl(callable $f, $acc)
    {
        return $this->reduce($f, $acc);
    }

    abstract public function reduceRight(callable $f, $acc);

    public function foldr(callable $f, $acc)
    {
        return $this->reduceRight($f, $acc);
    }

    abstract public function find($f);

    public function detect($f)
    {
        return $this->find($f);
    }

    abstract public function filter($predicate);

    public function select($predicate)
    {
        return $this->filter($predicate);
    }

    abstract public function every($predicate = null);

    public function all($predicate = null)
    {
        return $this->every($predicate);
    }

    abstract public function some($predicate = null);

    public function any($predicate = null)
    {
        return $this->some($predicate);
    }

    abstract public function contains($value);

    public function includes($value)
    {
        return $this->contains($value);
    }

    abstract public function first($n = null);

    public function head($n = null)
    {
        return $this->first($n);
    }

    public function take($n = null)
    {
        return $this->first($n);
    }

    abstract public function rest($n = 1);

    public function tail($n = 1)
    {
        return $this->rest($n);
    }

    public function drop($n = 1)
    {
        return $this->rest($n);
    }

    abstract public function uniq($selector = null);

    public function unique($selector = null)
    {
        return $this->uniq($n);
    }
}
