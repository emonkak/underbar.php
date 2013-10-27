`$xs`の要素をシャッフルします。
内部で組込みの[`shuffle()`](http://php.net/manual/ja/function.shuffle.php)を利用しています。

```php
_::shuffle([1, 2, 3, 4, 5, 6]);
=> [4, 1, 6, 3, 5, 2]
```
