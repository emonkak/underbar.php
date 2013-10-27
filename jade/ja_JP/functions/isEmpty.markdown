与えられた配列か`Iterator`や`IteratorAggregate`などの`Traversable`なオブジェクトが空かどうかを返します。

```php
_::isEmpty([1, 2, 3]);
=> false
_::isEmpty(new EmptyIterator());
=> true
```
