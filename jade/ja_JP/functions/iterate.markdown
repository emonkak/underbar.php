`$acc`に対して`$f`を繰り返し適用していく[`Iterator`](http://php.net/manual/ja/class.iterator.php)を返します。
[`ArrayImpl`](#ArrayImpl)から呼び出された場合は[`OverflowException`](http://php.net/manual/ja/class.overflowexception.php)が発生します。

```php
_::chain(0)
  ->iterate(function($n) {
    return $n + 1;
  })
  ->take(5)
  ->toList()
  ->value();
=> [0, 1, 2, 3, 4]
```
