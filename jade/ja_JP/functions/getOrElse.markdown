`$xs`からキーが`$key`の値を取り出します。
見つからなかった時は`$default`を返します。

```php
_::getOrElse(['foo' => 'bar', 'hoge' => 'fuga'], 'foo', 'payo');
=> "bar"

_::getOrElse(['foo' => 'bar', 'hoge' => 'fuga'], 'piyo', 'payo');
=> "payo"
```
