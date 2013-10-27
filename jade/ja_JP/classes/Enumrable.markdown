Underbar.phpの手続きをmixinするためのトレイトです。
このトレイトのソースはスクリプトによって自動生成されています。

利用する際は対象のクラスに以下の抽象メソッドを実装する必要があります。

| Method                    | Description
|:--------------------------|:-----------
| string *getUndebarImpl*() | Underbar.phpの実装クラスを取得する
| value *value*()           | Underbar.phpの手続きに与える値を返す

Version 0.2.0から`chain()`がこのトレイトに依存するようになりました。

```php
class Collection implements IteratorAggregate
{
    use Underbar\Enumerable;

    protected $array;

    public function __construct()
    {
        $this->array = func_get_args();
    }

    public function getIterator()
    {
        return new ArrayIterator($this->array);
    }

    public function getUndebarImpl()
    {
        return 'Underbar\\IteratorImpl';
    }

    public function value()
    {
        return $this->array;
    }
}

$collection = new Collection(1, 2, 3, 4, 5);
$collection
    ->filter(function($n) { return $n % 2 === 0; })
    ->map(function($n) { return $n * 2; })
    ->toList();
=> [4, 8]

$collection = new Collection(1, 2, 3);
$collection
    ->cycle()
    ->map(function($n) { return $n * 2; })
    ->take(6)
    ->join(', ');
=> '2, 4, 6, 2, 4, 6'
```
