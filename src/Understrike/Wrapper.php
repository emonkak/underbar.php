<?php

namespace Understrike;

class Wrapper
{
    private $value;

    private $class;

    public function __construct($value, $class)
    {
        $this->value = $value;
        $this->class = $class;
    }

    public function __call($name, $aruguments)
    {
        array_unshift($aruguments, $this->value);
        return call_user_func_array($this->class.'::'.$name, $aruguments);
    }
}

// __END__
// vim: expandtab softtabstop=4 shiftwidth=4
