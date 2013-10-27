`$xs`をPHP組み込みの[`sort`](http://www.php.net/manual/ja/function.sort.php)を使って要素を昇順にソートします。
比較関数`$compare`が与えられた場合は[`usort`](http://www.php.net/manual/ja/function.usort.php)でソートされます。

```php
_::sort([2, 3, 1]);
=> [1, 2, 3]

_::sort([2, 3, 1], function($x, $y) {
    if ($x === $y) return 0;
    return $x < $y ? 1 : -1;
});
=> [3, 2, 1]
```
