<?php

namespace Underbar;

abstract class Option implements \IteratorAggregate, \Countable
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
             ? Option\None::instance()
             : new Option\Some($value);
    }

    /**
     * Returns the option's value.
     *
     * @throws  OutOfRangeException
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
     * Returns true if this option is nonempty and the predicate p returns true
     * when applied to this Option's value. Otherwise, returns false.
     *
     * @param   callable  $iterator
     * @return  boolean
     */
    abstract public function exists($iterator);

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

    /**
     * Returns the option's value if it is nonempty, or null if it is empty.
     *
     * @param   Option  $value
     * @return  Option
     */
    abstract public function orElse(Option $value);

    /**
     * Returns the option's value if it is nonempty, or null if it is empty.
     *
     * @return  mixed
     */
    abstract public function orNull();
}

// __END__
// vim: expandtab softtabstop=4 shiftwidth=4
