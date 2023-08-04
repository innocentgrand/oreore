# インストールと利用

 composerで導入できるようになるようにしたい

# src/app/から。

src/core にはMVC本体のコードが実装されています。
ユーザはsrc/appディレクトリを作ってアプリケーションを製造します。

## src/app/Controllerディレクトリ

src/app/Controller/ ディレクトリを作ります。
この配下にコントローラを配置していきます。

例えばTestCtrlとする場合
ファイル名はTestCtrl.phpとしてください。

実装は以下の様に

```PHP
namespace OApp\Controller;

use Ore\Controller\Ctrl;

class TestCtrl extends Ctrl
{
}
```
のようにしてください。


http://example.com/test/ とアクセスされた場合にこのTestCtrlが呼び出されます。
また、この場合エンジンは「Index」メソッドを探します。

```PHP
public function Index()
{
}
```

メソッドでIndexメソッドを定義しておきます。

URLアクセスがhttp://example.com/test/test/ である場合、エンジンは「Test」メソッドを探します。

```PHP
public function Test()
{
}
```
を定義しましょう。
また、先頭が大文字になることに注意してください。

## メソッドが呼ばれるまで

コントローラに定義されたメソッドを呼び出すルールは以下です。

1. アクセスURLが「/test」の場合に呼び出されるコントローラは「TestCtrl」です
	1. この場合、「Index」メソッドを実行しようとします。
	1. 「Index」メソッドに引数は追加で来ません
1. アクセスURLが「/test/test」の場合はコントローラは「TestCtrl」が呼び出され、「Test」メソッドを実行します
1. アクセスURLが「/test/test/a/1」の場合はコントローラは「TestCtrl」が呼び出され、「Test」メソッドに「$a = 1」に引数を与えて実行します。
	1. この時に引数の名称が「$a」である場合に、URLが「/a/1」では無く、「/b/1」とした場合名称違いでエラーとなります
	1. 引数定義では、型を宣言しておくことをおすすめします。
	1. 引数のデフォルト値（例：Test(string $a = "")）の場合、URLから「/a/xxx」のアクセスが無くても動作します。
