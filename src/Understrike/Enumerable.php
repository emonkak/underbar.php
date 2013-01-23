<?php

namespace Understrike;

trait Enumerable
{
    public function __call($name, $aruguments)
    {
        array_unshift($aruguments, $this);
        return call_user_func_array(__NAMESPACE__.'\\Strict::'.$name, $aruguments);
    }

    public function lazy()
    {
        return new Wrapper($this, __NAMESPACE__.'\\Lazy');
    }
}
