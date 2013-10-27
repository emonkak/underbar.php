`$value`が`$n`個分繰り返される配列を返します。
`$n`が与えられなかった場合は`$value`を無限に繰り返し続けます。
[`ArrayImpl`](#ArrayImpl)から呼び出された時に`$n`が与えられなかった場合は[`Overflowexception`](http://php.net/manual/ja/class.overflowexception.php)が発生します。

```php
_::repeat(1, 2);
=> [1, 1]

_::chain(1)->repeat()->take(5)->toList()->value();
=> [1, 1, 1, 1, 1]
```
