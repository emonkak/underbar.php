`$xs`に存在しないキーの値を`$defaults`から補完します。
`isset()`でキーの存在を確認しているので`null`は存在しないものとして扱われます。

```php
$iceCream = ['flavor' => 'chocolate'];
_::defaults($iceCream, ['flavor' => 'vanilla', 'sprinkles' => 'lots']);
=> ["flavor" => "chocolate", "sprinkles" => "lots"]
```
