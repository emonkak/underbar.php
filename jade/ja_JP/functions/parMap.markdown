[`map()`](#map)の並列実行版です。
`$xs`の各要素に対して関数`$f`を並列に適用した結果を返す`Parallel`オブジェクトを返します。
`Parallel`クラスは[`Iterator`](http://php.net/manual/ja/class.iterator.php)インターフェイスと[`Countable`](http://php.net/manual/ja/class.countable.php)インターフェイスを実装しています。

`$f`は引数`(element)`を1つだけ取る関数です。
`$n`は同時に起動するワーカープロセスの数です。
`$n`が2以上の時は複数のワーカープロセスによって処理されるため、返ってくる要素の順序は不定です。

`parMap()`は内部で`pcntl_fork()`を利用しているので処理系が`--enable-pcntl`を指定してコンパイルされている必要があります。

##### `Parallel`で利用可能なメソッド

| Method                  | Description
|:------------------------|:-----------
| void *fork*()           | ワーカープロセスを1つ起動させる
| void *terminate*()      | ワーカープロセスを1つ停止させる
| int *processes*()       | 現在起動中のワーカープロセスの数を取得する
| void *push*($value)     | 処理したい値を追加する
| void *pushAll*($values) | 処理したい値を複数追加する
| mixed *result*()        | 処理の結果を1つ取り出す
| int *count*()           | 未処理のタスクの数を返す

```php
$xs = _::parMap([1, 2, 3], function($x) {
    sleep(2);
    return $x * 3;
});
// 4並列で実行されるので約2秒ですべての計算が終わる
foreach ($xs as $x) {
    var_dump($x);  // 3, 6, 9が順不同に出力される
}
```
