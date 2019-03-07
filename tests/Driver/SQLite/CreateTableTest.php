<?php
/**
 * Created by PhpStorm.
 * User: David
 * Date: 07.03.2019
 * Time: 10:48
 */

namespace Davajlama\SchemaBuilder\Test\Driver\SQLite;

use Davajlama\SchemaBuilder\Driver\SQLite\Generator;
use Davajlama\SchemaBuilder\Patch;
use Davajlama\SchemaBuilder\PatchList;
use Davajlama\SchemaBuilder\Schema\Table;
use Davajlama\SchemaBuilder\Schema\Type;
use Davajlama\SchemaBuilder\Test\TestCase;

class CreateTableTest extends TestCase
{

    public function testCreateSimpleTableArticles()
    {
        $generator = new Generator();

        $table = new Table('articles');
        $table->createId('id');
        $table->createColumn('name', Type::varcharType(255));

        $sql = 'CREATE TABLE `articles` (';
        $sql .= '`id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT, ';
        $sql .= '`name` VARCHAR(255) DEFAULT NULL';
        $sql .= ')';

        $patches = $generator->createTablePatches($table);

        $this->assertTrue($patches instanceof PatchList);
        $this->assertSame(1, $patches->count());
        $this->assertTrue($patches->first() instanceof Patch);

        $this->assertSame(Patch::NON_BREAKABLE, $patches->first()->getLevel());
        $this->assertSame($sql, $patches->first()->getQuery());
    }

}