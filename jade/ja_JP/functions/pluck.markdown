`$xs`の要素の中から指定した`$property`の値を抽出します。

```php
$stooges = [
    ['name' => 'moe', 'age' => 40],
    ['name' => 'larry', 'age' => 50],
    ['name' => 'curly', 'age' => 60]
];
_::pluck($stooges, 'name');
=> ["moe", "larry", "curly"]
```
