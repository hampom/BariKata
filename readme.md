# BariKata(バリ型)

🍜 型安全・配列禁止を徹底した PHP コレクションライブラリ
「BariKata」は **TypedCollection** を中心に設計され、プラグイン拡張やファクトリーによる標準セット化が可能です。

## 特徴

- **型安全なコレクション**  
  指定したクラス以外は格納できません。 ArrayAccess で自然に操作可能。
- **プラグイン拡張**  
  map, filter, orderBy などの操作を簡単に追加できます。
- **Factory**  
  あらかじめ定義したプラグインセットを有効化した状態でコレクションを生成可能。

## インストール

```bash
composer require hampom/bari-kata
```

## 使い方

### TypedCollection の生成

```php
use Hampom\BariKata\TypedCollection;

$ids = new TypedCollection('int');
$ids[] = 1;
$ids[] = 2;

// -> InvalidArgumentException: Value must be of type int, string given.
// $ids[] = 'a';
```

### プラグインの追加

```php
use Hampom\BariKata\TypedCollection;
use Hampom\BariKata\Plugins\toArray;

$ids = new TypedCollection('int', [0], [new toArray]);
$ids[] = 1;

// -> array(0 => 0, 1 => 1)
var_export($ids->toArray());
```

### ファクトリーの利用

```php
final class IntCollectionFactory extends TypedCollectionFactory
{
    protected static string $type = 'int';

    protected static function plugins(): array
    {
        return [
            ["class" => toArray::class]
        ];
    }
}

$ids = (new IntCollectionFactory)->factory([0]);
$ids[] = 1;

// -> array(0 => 0, 1 => 1)
var_export($ids->toArray());
```