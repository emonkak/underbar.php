動作自体は[`zip()`](#zip)と同じですが、引数に配列の配列を受けとります。

```php
_::unzip([['moe', 'larry', 'curly'], [30, 40, 50], [true, false, false]]);
=> [["moe", 30, true], ["larry", 40, false], ["curly", 50, false]]
```
