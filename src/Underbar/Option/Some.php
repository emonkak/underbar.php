<?php

namespace Underbar\Option;

use Underbar\Option;

final class Some extends Option
{
    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * Returns the option's value.
     *
     * @throws  RuntimeException
     * @return  mixed
     */
    public function get()
    {
        return $this->value;
    }

    /**
     * Returns the option's value if the option is nonempty, * otherwise return
     * the result of evaluating default.
     *
     * @param   mixed  $default
     * @return  mixed
     */
    public function getOrElse($default)
    {
        return $this->value;
    }

    /**
     * @see     IteratorAggregate
     * @return  Traversable
     */
    public function getIterator()
    {
        return new \ArrayIterator(array($this->value));
    }

    /**
     * Returns true if the option is None, false otherwise.
     *
     * @return  boolean
     */
    public function isEmpty()
    {
        return false;
    }

    /**
     * Returns a Some containing the result of applying f to this Option's value
     * if this Option is nonempty. Otherwise return None.
     *
     * @param   callable  $iterator
     * @return  Option
     */
    public function map($iterator)
    {
        $result = call_user_func($iterator, $this->value, 0, $this);
        return Option::fromValue($result);
    }

    /**
     * Returns true if this option is nonempty and the predicate p returns true
     * when applied to this Option's value. Otherwise, returns false.
     *
     * @param   callable  $iterator
     * @return  boolean
     */
    public function exists($iterator)
    {
        return !!call_user_func($iterator, $this->value);
    }

    /**
     * Returns this Option if it is nonempty and applying the predicate p to
     * this Option's value returns true. Otherwise, return None.
     *
     * @param   callable  $iterator
     * @return  Option
     */
    public function filter($iterator)
    {
        return call_user_func($iterator, $this->value, 0, $this)
            ? $this
            : Option_None::instance();
    }

    /**
     * Returns this Option if it is nonempty and applying the predicate p to
     * this Option's value returns false. Otherwise, return None.
     *
     * @param   callable  $iterator
     * @return  Option
     */
    public function filterNot($iterator)
    {
        return call_user_func($iterator, $this->value, 0, $this)
            ? Option_None::instance()
            : $this;
    }

    /**
     * Returns the result of applying f to this Option's value if this Option is
     * nonempty. Returns None if this Option is empty.
     *
     * @param   callable  $iterator
     * @return  Option
     */
    public function flatMap($iterator)
    {
        return call_user_func($iterator, $this->value, 0, $this);
    }

    /**
     * Returns the option's value if it is nonempty, or null if it is empty.
     *
     * @param   Option  $value
     * @return  Option
     */
    public function orElse(Option $value)
    {
        return $this;
    }

    /**
     * Returns the option's value if it is nonempty, or null if it is empty.
     *
     * @return  mixed
     */
    public function orNull()
    {
        return $this->value;
    }
}

// __END__
// vim: expandtab softtabstop=4 shiftwidth=4
