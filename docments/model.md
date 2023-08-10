# ModelとMigrationについて

## 接続を管理する

DB接続のためのルールは
config/database.json に定義します。

以下は例。
```json
{
	"DB" : "PostgreSQL",
	"Connection" : {
		"DB"   : "example_db",
		"Host" : "localhost",
		"Port" : "5432",
		"User" : "example_user",
		"Pass" : "example_password"
	}
}
```

## Modelを作る

ModelはCtrlと同様 src/app/Model/ ディレクトリに作ります。
例えば以下の様に作ります。

src/app/Model/Test.php
```PHP
namespace OApp\Model;

use Ore\Model\Model;

class Test extends Model
{
	protected $table = "test_table";
}
```

最低限の定義として以上のように対象のテーブル名称を定義します。

## IDについて

テーブルにはプライマリーキーとしてID（id）などがあるとして、これが自動採番されるものであるとして実装されています。

もし、Modelで主キーとなるIDを変更する場合以下にします

```PHP
namespace OApp\Model;

use Ore\Model\Model;

class Test extends Model
{
	protected $table = "test_table";
	protected $id = "extra_id";
}
```

## 日付の更新

自動的に日付カラムを変更するように実装されています。

日付カラムは「作成日」として「created」

「更新日」として「updated」としています。

これらの日付カラム名称が異なる場合変更することも可能です。変更する場合以下のようにします。

```PHP
namespace OApp\Model;

use Ore\Model\Model;

class Test extends Model
{
	protected $table = "test_table";
	protected $autoCreateDate = "create_date";
	protected $autoUpdateDate = "update_date";
}

```

こうすることでModel内の登録/更新用のメソッドで自動的に日付も更新されます。

### 日付更新を停止したい

日付の更新を停止、そもそも日付のカラムなどがなく必要無い場合以下のようにします。

```PHP
namespace OApp\Model;

use Ore\Model\Model;

class Test extends Model
{
	protected $table = "test_table";
	protected $autoTime = false;
}
```

