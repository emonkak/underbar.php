`$xs`の要素の左結合で畳み込んだ結果を返します。
`$f`は`($acc, $value, $key, $xs)`の4つの引数を取ります。

```php
$sum = _::reduce([1, 2, 3], function($acc, $n) {
    return $acc + $n;
}, 0);
=> 6
```
