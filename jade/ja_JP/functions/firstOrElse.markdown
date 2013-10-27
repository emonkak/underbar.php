`$xs`の最初の要素を返します。見付からなかった場合は`$default`を返します。

```php
_::firstOrElse([1, 2, 3], 10);
=> 1

_::firstOrElse([], 10);
=> 10
```
