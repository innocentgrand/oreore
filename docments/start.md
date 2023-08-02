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
