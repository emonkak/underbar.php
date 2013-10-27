`$xs`の要素の中から`$keys`で指定されたキーの*以外*の値を取り出します。

```php
_::pick(['name' => 'moe', 'age' => 50, 'userid' => 'moe1'], 'userid');
=> ['name' => 'moe', 'age' => 50]
```
