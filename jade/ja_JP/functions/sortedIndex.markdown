二分探索を実行して`$value`の挿入位置を調べます。

```php
_::sortedIndex([10, 20, 30, 40, 50], 35);
=> 3

$stooges = [
    ['name' => 'moe', 'age' => 40],
    ['name' => 'curly', 'age' => 60]
];
_::sortedIndex($stooges, ['name' => 'larry', 'age' => 50], 'age');
=> 1
```
