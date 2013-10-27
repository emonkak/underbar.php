`$f`が最初に`true`を返すまでの要素の配列を返します。

```php
_::takeWhile([1, 2, 3, 4, 5, 1, 2, 3], function($x) {
    return $x < 3;
});
=> [1, 2]
```
