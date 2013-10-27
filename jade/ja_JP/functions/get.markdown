`$xs`からキーが`$key`の値を取り出します。
見つからなかった時は`null`を返します。
`getOrElse($xs, $key, null)`と等価な操作です。

```php
_::get(['foo' => 'bar', 'hoge' => 'fuga'], 'foo');
=> "bar"

_::get(['foo' => 'bar', 'hoge' => 'fuga'], 'piyo');
=> null
```
