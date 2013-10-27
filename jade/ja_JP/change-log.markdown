Change Log
----------

### 0.3.0 - Sep 7, 2013

#### Removal

- `lazy()`を廃止
- [`map()`](#map)の第3引数のキーの選択関数を廃止。代用として[`indexBy()`](#indexBy)が使用できる
- [`invert()`](#invert)が常に配列を返すように変更(`Iterator`を返すことはなくなった)

#### Feature

- 指定した関数の返り値をキーとした配列を生成する[`indexBy()`](#indexBy)を追加
- 配列からランダムに値を選択する[`sample()`](#sample)を追加
- 配列か`Traversable`なオブジェクトが空かどうかを返す[`isEmpty()`](#isEmpty)を追加

#### Fix

- [`memoize()`]が返す`MemoizeIterator`が正しいキーを返さなかったのを修正

### 0.2.3 - Aug 7, 2013

- `lazy()`の追加
- [`map()`](#map)の第3引数にキーの選択関数を指定できるようにした
- [`invert()`](#invert)が`Iterator`を返せるようにした
- [`parMap()`](#parMap)で利用される`Parallel`クラスのデストラクタでワーカープロセスの終了を待つように修正

### 0.2.2 - Aug 4, 2013

- [`IteratorImpl`](#IteratorImpl)の内部実装の一部にSPLの`Iterator`を使うようにしてパフォーマンスを改善
- [`firstOrElse()`](#firstOrElse)と[`lastOrElse()`](#firstOrElse)の追加
- [`get()`](#get)と[`getOrElse()`](#getOrElse)の追加

### 0.2.1 - Aug 3, 2013

- [`uniq()`](#uniq)の`$isSorted`引数を削除
- [`intersection()`](#intersection)の遅延版を実装
- [`uniq()`](#uniq)と[`intersection()`](#intersection)の実装に`Underbar\Internal\Set`を使うようにした
- [`average()`](#average)を追加

### 0.2.0 - Jul 30, 2013

- クラス構造を全面的に刷新
- 各`Iterator`の実装を改善
- [`Enumerable`](#Enumerable)をメソッドチェイン可能にした
- [`chain()`](#chain)で利用している`Wrapper`クラスを[`Enumerable`](#Enumerable)に依存した実装に変更
- [`chain()`](#chain)で集約関数呼び出した時などはメソッドチェインを自動的に中断するようにした
- `isArray()` `isTraversable()` `pop()` `scanl()` `scanr()` `shift()` `slice()` `span()` `unshift()`を削除
- `groupBy()` `countBy()`の`$isSorted`引数を削除

### 0.1.2 - Jul 27, 2013

- [`Enumerable`](#Enumerable)の可変長引数のメソッドが正しく動作していなかったのを修正

### 0.1.1 - Jul 25, 2013

- [`Enumerable`](#Enumerable)のデフォルトクラスを[`ArrayImpl`](#ArrayImpl)に変更して`lazy()`メソッドを実装

### 0.1.0 - Jul 24, 2013

- Initial release
