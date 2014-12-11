<?php

namespace Underbar\Tests;

use Underbar\Provider\IteratorProvider;

class IteratorCollectionTest extends AbstractLazyCollectionTest
{
    protected function getCollectionProvider()
    {
        return IteratorProvider::getInstance();
    }
}
