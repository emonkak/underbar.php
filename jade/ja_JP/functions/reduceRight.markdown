`$xs`の要素を右結合で畳み込んだ結果を返します。
`$f`は`($acc, $value, $key, $xs)`の4つの引数を取ります。

```php
$xss = [[0, 1], [2, 3], [4, 5]];
$flat = _::reduceRight($xss, function($acc, $xs) {
    return array_merge($acc, $xs);
}, []);
=> [4, 5, 2, 3, 0, 1]
```
