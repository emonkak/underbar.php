<?php

namespace Underdash;

abstract class Option implements \IteratorAggregate
{
    use Enumerable;

    /**
     * @param   mixed   $value
     * @param   mixed   $none
     * @return  Option
     */
    final public static function fromValue($value, $none = null)
    {
        return $value === $none
            ? Option_None::instance()
            : new Option_Some($value);
    }

    /**
     * Returns the option's value.
     *
     * @return  mixed
     */
    abstract public function get();

    /**
     * Returns the option's value if the option is nonempty, * otherwise return
     * the result of evaluating default.
     *
     * @param   mixed  $default
     * @return  mixed
     */
    abstract public function getOrElse($default);

    /**
     * Returns true if the option is None, false otherwise.
     *
     * @return  boolean
     */
    abstract public function isEmpty();

    /**
     * Returns true if the option is an instance of Some, false otherwise.
     *
     * @return  boolean
     */
    public function isDefined()
    {
        return !$this->isEmpty();
    }

    /**
     * Returns a Some containing the result of applying f to this Option's value
     * if this Option is nonempty. Otherwise return None.
     *
     * @param   callable  $iterator
     * @return  Option
     */
    abstract public function map($iterator);

    /**
     * Returns this Option if it is nonempty and applying the predicate p to
     * this Option's value returns true. Otherwise, return None.
     *
     * @param   callable  $iterator
     * @return  Option
     */
    abstract public function filter($iterator);

    /**
     * Returns this Option if it is nonempty and applying the predicate p to
     * this Option's value returns false. Otherwise, return None.
     *
     * @param   callable  $iterator
     * @return  Option
     */
    abstract public function filterNot($iterator);

    /**
     * Returns the result of applying f to this Option's value if this Option is
     * nonempty. Returns None if this Option is empty.
     *
     * @param   callable  $iterator
     * @return  Option
     */
    abstract public function flatMap($iterator);
}

// __END__
// vim: expandtab softtabstop=4 shiftwidth=4
