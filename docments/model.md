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
