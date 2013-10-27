`$value`をメソッドチェインで処理するための`Wrapper`で包んで返します。
`Wrapper`は[`Enumerable`](#Enumerable)をmix-inしたメソッドチェイン用のクラスです。

チェイン中に[`toArray()`](#toArray)か[`toList()`](#toList)以外の`array`か`Iterator`を返す関数を呼び出した場合は、返り値を新たな`Wrapper`のインスタンスで包んで返します。
それ以外の値を返す関数を呼び出した時は値をそのまま返します。

```php
$stooges = [
    ['name' => 'curly', 'age' => 25],
    ['name' => 'moe', 'age' => 21],
    ['name' => 'larry', 'age' => 23]
];
$youngest = _::chain($stooges)
  ->sortBy(function($stooge){ return $stooge['age']; })
  ->map(function($stooge){ return $stooge['name'] . ' is ' . $stooge['age']; })
  ->first();
=> "moe is 21"
```
