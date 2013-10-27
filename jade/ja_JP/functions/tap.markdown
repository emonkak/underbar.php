`$value`を引数として`$interceptor`を呼び出して`$value`をそのまま返します。

```php
_::chain([1, 2, 3, 200])
  ->filter(function($num) { return $num % 2 == 0; })
  ->tap(function($num) { var_dump($num); })
  ->map(function($num) { return $num * $num; })
  ->value();
=> // [1 => 2, 3 => 200] (var_dump)
=> [1 => 4, 3 => 40000]
```
