`$xs`の要素を`$separator`を区切文字として文字列に結合します。
PHP組み込みの[`implode`](http://php.net/manual/ja/function.implode.)と等価な処理です。

```php
_::join(['foo', 'bar', 'baz'], ',')
=> "foo,bar,baz"
```
