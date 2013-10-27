`$xs`の末尾に`$values`を追加します。
[`Iterator`](http://php.net/manual/ja/class.iterator.php)に対して呼び出された場合は配列に変換されます。

```php
_::push([1, 2, 3], 4)
=> [1, 2, 3, 4]

_::push([1, 2, 3], 4, 5)
=> [1, 2, 3, 4, 5]
```
