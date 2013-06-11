<?php

namespace Underbar\Iterator;

class ZipIterator extends \MultipleIterator
{
    public function __construct()
    {
        parent::__construct(self::MIT_NEED_ALL | self::MIT_KEYS_NUMERIC);
    }

    public function key()
    {
    }
}
