Getting Started
---------------

Underbar.phpの動作には*バージョン5.4以上*のPHP処理系と、[Composer](http://getcomposer.org/)が必要です。
インストールが完了したらプロジェクトルートに以下のような内容のcomposer.jsonを作成して下さい。
詳しいcomposer.jsonの書き方については[こちら](http://getcomposer.org/doc/04-schema.md)を参照して下さい。

```javascript
{
    "require": {
        "emonkak/underbar.php": "dev-master"
    }
}
```

composer.jsonを作成したら同じディレクトリで

	$ php composer.phar install
	
を実行するとUnderbar.phpのインストールは完了です。
