`$xs`の要素の中から`$keys`で指定されたキーの値を取り出します。

```php
_::pick(['name' => 'moe', 'age' => 50, 'userid' => 'moe1'], 'name', 'age');
=> ['name' => 'moe', 'age' => 50]
```
