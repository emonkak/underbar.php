`$xs`の要素をキーとして`$values`の要素を値として連想配列を作成します。
`$values`が与えらなかった場合は`$xs`にキーと値のペアが格納されていることを期待します。

```php
_::object(['moe', 'larry', 'curly'], [30, 40, 50]);
=> ["moe" => 30, "larry" => 40, "curly" => 50]

_::object([['moe', 30], ['larry', 40], ['curly', 50]]);
=> ["moe" => 30, "larry" => 40, "curly" => 50]
```
