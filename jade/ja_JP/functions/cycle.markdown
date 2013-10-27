`$xs`の要素が`$n`で指定された回数だけ繰り返された配列を返します。
`$n`が与えられなかった場合は繰り返しを無限に続けます。
[`ArrayImpl`](#ArrayImpl)から`$n`を与えずに呼び出された場合は[`OverflowException`](http://php.net/manual/ja/class.overflowexception.php)が発生します。

```php
_::cycle([1, 2], 2);
=> [1, 2, 1, 2]

_::chain([1, 2, 3])->cycle()->take(5)->toList();
=> [1, 2, 3, 1, 2]
```
