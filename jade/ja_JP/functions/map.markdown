`$xs`の各要素に対して関数`$f`を適用します。
`$f`は`(element, key, array)`の3つの引数を取ります。

`$g`が与えられた場合はキーに対しても関数を適用します。
`$g`は`(key, element, array)`の3つの引数を取ります。

```php
_::map([1, 2, 3], function($x) {
    return $x * 3;
});
=> [3, 6, 9]
_::map(['one' => 1, 'two' => 2, 'three' => 3], function($x) {
    return $x * 3;
});
=> [3, 6, 9]
```
