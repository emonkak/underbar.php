`$destination`を与えられた`$sources`で拡張します。
同名のキーが存在した場合は前のものを上書きします。
組み込みの[`array_merge()`](http://php.net/manual/ja/function.array-merge.php)と等価な操作です。

```php
_::extend(['name' => 'moe'], ['age' => 50]);
=> ['name' => 'moe', 'age' => 50]
```
