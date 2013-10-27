`$xs`の各要素に対して関数`$f`を呼び出して`$xs`をそのまま返します。
`$f`は`(element, key, array)`の3つの引数を取ります。

```php
_::each([1, 2, 3], function($x, $k, $xs) {
    // 1, 2, 3を順番に出力
    echo $x, PHP_EOL;
});
$xs = ['one' => 1, 'two' => 2, 'three' => 3];
_::each($xs, function($x, $k, $xs) {
    // one-1, two-2, three-3を順番に出力
    echo $k, '-', $x, PHP_EOL;
});
```
