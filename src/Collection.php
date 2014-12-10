<?php

namespace Underbar;

use Underbar\Provider\GeneratorProvider;
use Underbar\Provider\ICollectionProvider;
use Underbar\Provider\IteratorProvider;
use Underbar\Util\Iterators;

class Collection implements \IteratorAggregate
{
    use Enumerable;
    use EnumerableAliases;

    private static $defaultProvider;

    private $source;

    private $provider;

    public static function from($source)
    {
        if (!Iterators::isTraversable($source)) {
            throw new \InvalidArgumentException('This source can not be traversable.');
        }

        return new Collection($source, self::$defaultProvider);
    }

    public static function range($start, $stop = null, $step = 1)
    {
        if ($stop === null) {
            $stop = $start;
            $start = 0;
        }
        return new Collection(
            self::$defaultProvider->range($start, $stop, $step),
            self::$defaultProvider
        );
    }

    public static function iterate($initial, $f)
    {
        return new Collection(
            self::$defaultProvider->iterate($initial, $f),
            self::$defaultProvider
        );
    }

    public static function repeat($value, $n = null)
    {
        return new Collection(
            self::$defaultProvider->repeat($value, $n),
            self::$defaultProvider
        );
    }

    public static function setDefaultProvider(ICollectionProvider $provider)
    {
        self::$defaultProvider = $provider;
    }

    public static function getDefaultProvider()
    {
        return self::$defaultProvider;
    }

    public function __construct($source, ICollectionProvider $provider)
    {
        $this->source = $source;
        $this->provider = $provider;
    }

    public function getSource()
    {
        return $this->source;
    }

    public function getProvider()
    {
        return $this->provider;
    }
}

if (class_exists('Generator')) {
    Collection::setDefaultProvider(GeneratorProvider::getInstance());
} else {
    Collection::setDefaultProvider(IteratorProvider::getInstance());
}
