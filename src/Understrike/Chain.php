<?php

namespace Understrike;

class Chain
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
        $result = call_user_func_array($this->class.'::'.$name, $aruguments);
        return new static($result, $this->class);
    }

    public function value()
    {
        return $this->value;
    }
}

// __END__
// vim: expandtab softtabstop=4 shiftwidth=4
