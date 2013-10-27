`$xs`の要素を`$f`を呼び出した結果をキーとした新しい配列にします。
`$f`に文字列を指定した場合は該当するプロパティを基準にします。

```php
$stooges = [
    ['name' => 'moe', 'age' => 40],
    ['name' => 'larry', 'age' => 50],
    ['name' => 'curly', 'age' => 60]
];
_::indexBy(stooges, 'age');
=> [
  40 => ['name' => 'moe', 'age' => 40],
  50 => ['name' => 'larry', 'age' => 50],
  60 => ['name' => 'curly', 'age' => 60]
]
```
