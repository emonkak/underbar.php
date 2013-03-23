<?php

namespace Underbar\Option;

use Underbar\Option;

final class None extends Option
{
    private static $instance;

    /**
     * Create singleton instance.
     *
     * @return  None
     */
    public static function instance()
    {
        return static::$instance === null
             ? new static()
             : static::$instance;
    }

    private function __construct()
    {
        static::$instance = $this;
    }

    /**
     * Returns the option's value.
     *
     * @throws  RuntimeException
     * @return  mixed
     */
    public function get()
    {
        throw new \RuntimeException();
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
        return $default;
    }

    /**
     * @see     IteratorAggregate
     * @return  Traversable
     */
    public function getIterator()
    {
        return new \EmptyIterator();
    }

    /**
     * Returns true if the option is None, false otherwise.
     *
     * @return  boolean
     */
    public function isEmpty()
    {
        return true;
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
        return $this;
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
        return false;
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
        return $this;
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
        return $this;
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
        return $this;
    }

    /**
     * Returns the option's value if it is nonempty, or null if it is empty.
     *
     * @param   Option  $value
     * @return  Option
     */
    public function orElse(Option $value)
    {
        return $value;
    }

    /**
     * Returns the option's value if it is nonempty, or null if it is empty.
     *
     * @return  mixed
     */
    public function orNull()
    {
        return null;
    }

    /**
     * @see     Countable
     * @return  mixed
     */
    public function count()
    {
        return 0;
    }
}

// __END__
// vim: expandtab softtabstop=4 shiftwidth=4
