<?php
/**
 * This file is part of the Underbar.php package.
 *
 * Copyright (C) 2013 Shota Nozaki <emonkak@gmail.com>
 *
 * Licensed under the MIT License
 */

namespace Underbar\Iterator;

class ZipIterator extends \MultipleIterator
{
    public function __construct()
    {
        parent::__construct(self::MIT_NEED_ALL);
    }

    public function key()
    {
        return implode('-', parent::key());
    }
}
