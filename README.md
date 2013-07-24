# underbar.php [![Build Status](https://travis-ci.org/emonkak/underbar.php.png)](https://travis-ci.org/emonkak/underbar.php)

underbar.php is a underscore.js like library.

However not aim full compatibility of undersocre.js.

# Requirements

- PHP 5.3 or higher (Suggest PHP 5.4 or higher)
- [Composer](http://getcomposer.org/)

# Licence

MIT Licence

# Getting Started

1. Install [Composer](http://getcomposer.org/).
2. Create the `composer.json`
3. Execute `composer.phar install`

**composer.json**

```json
{
    "require": {
        "emonkak/underbar.php": "dev-master"
    }
}
```

# Example

```php
require __DIR__ . '/vendor/autoload.php';

use Underbar\Lazy as _;

// Take five elements from a even infinite list
_::chain(0)
    ->iterate(function($n) { return $n + 1; })
    ->filter(function($n) { return $n % 2 === 0; })
    ->take(5)
    ->each(functions($n) { echo $n, PHP_EOL; });
// => 0
//    2
//    4
//    6
//    8

// Get a first element
echo _::first(array(100)), PHP_EOL;
// => 100
```

# API

See https://emonkak.github.io/underbar.php/
