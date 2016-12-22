SchemaBuilder
=============

**WARNING**: THIS IS STILL DEVELOPMENT VERSION

MySQL Support:
- create tables
- alter tables
- create indexes
- alter indexes

Demo
----

```php
use Davajlama\SchemaBuilder\Bridge\PDOAdapter;
use Davajlama\SchemaBuilder\Driver\MySqlDriver;
use Davajlama\SchemaBuilder\Schema;
use Davajlama\SchemaBuilder\Schema\Type;
use Davajlama\SchemaBuilder\SchemaBuilder;
use Davajlama\SchemaBuilder\SchemaCreator;

$adapter    = new PDOAdapter(PDO($dsn, $username));
$driver     = new MySqlDriver($adapter);
$builder    = new SchemaBuilder($driver);

$schema = new Schema();
$articlesTable = $schema->createTable('articles');
$articlesTable->createId();
$articlesTable->createColumn('title', Type::varcharType(255));
$articlesTable->createColumn('content', Type::textType());
$articlesTable->createColumn('created', Type::dateTimeType());
$articlesTable->createIndex()
                    ->addColumn('created');

$patches = $builder->buildSchemaPatches($schema);

// print queries
foreach($patches as $patch) {
    echo $patch->getQuery() . PHP_EOL;
}

// apply patches
$creator = new SchemaCreator($driver);
$creator->applyPatches($patches);
```

for more examples look at tests