`$xs`のキーと値を入れ替えます。
キーが重複した場合は前のものを上書きします。
組み込みの[`array_flip()`](http://www.php.net/manual/ja/function.array-flip.php)と等価な操作です。

```php
_::invert(["Moe" => "Moses", "Larry" => "Louis", "Curly" => "Jerome"]);
=> ["Moses" => "Moe", "Louis" => "Larry", 'Jerome' => "Curly"];
```
