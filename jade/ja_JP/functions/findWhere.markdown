`$xs`の要素から`$properties`のキーと値のペアに最初に一致するものを返します。
見付からなかった場合は`null`を返します。

```php
$members = [
    ['name' => 'Yukari Tamura', 'age' => 17],
    ['name' => 'Yui Horie', 'age' => 17]
];
_::findWhere($members, ['age' => 17]);
=> ["name" => "Yukari Tamura", "age" => 17]
```
