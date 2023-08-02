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

```
namespace OApp\Controller;

use Ore\Controller\Ctrl;

class TestCtrl extends Ctrl
{
}
```
のようにしてください。


