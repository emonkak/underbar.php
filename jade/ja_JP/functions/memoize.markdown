`$xs`を一度計算した要素をキャッシュする`MemoizeIterator`でラップします。

```php
$fibs = _::chain(array(0, 1))
    ->iterate(function($pair) {
        return [$pair[1], $pair[0] + $pair[1]];
    })
    ->map(function($pair) { return $pair[0]; })
    ->memoize()
    ->value();
$fibs[10];
=> 55
```
