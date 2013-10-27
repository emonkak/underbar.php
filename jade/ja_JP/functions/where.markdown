`$xs`の要素から`$properties`のキーと値のペアに一致するものを選択します。

```php
$members = [
    ['name' => 'Yui Ogura', 'age' => 17],
    ['name' => 'Rina Hidaka', 'age' => 19],
    ['name' => 'Yuka Iguchi', 'age' => 24],
    ['name' => 'Yoko Hikasa', 'age' => 27],
    ['name' => 'Kana Hanazawa', 'age' => 24]
];
_::where($members, ['age' => 24]);
=> [["name" => "Yuka Iguchi", "age" => 24],
    ["name" => "Kana Hanazawa", "age" => 24]]
```
