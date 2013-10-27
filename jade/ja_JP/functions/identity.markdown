与えられた値をそのまま返します。

```php
$moe = ['name' => 'moe'];
$moe === _::identity($moe);
=> true
```
