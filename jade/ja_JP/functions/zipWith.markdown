[`zip()`](#zip)した結果に`$f`を適用させます。

```php
_::zipWith([1, 2, 3], [4, 5, 6], function($x, $y) {
    return $x + $y;
});
=> [5, 7, 9]
```
