<?php

namespace Davajlama\SchemaBuilder\Test\Driver\MySql;

use Davajlama\SchemaBuilder\Driver\MySql\Generator;
use Davajlama\SchemaBuilder\Patch;
use Davajlama\SchemaBuilder\PatchList;
use Davajlama\SchemaBuilder\Schema\Table;
use Davajlama\SchemaBuilder\Schema\Type\IntegerType;
use Davajlama\SchemaBuilder\Schema\Type\VarcharType;
use Davajlama\SchemaBuilder\Test\TestCase;

/**
 * Description of CreateTableTest
 *
 * @author David Bittner <david.bittner@seznam.cz>
 */
class CreateTableTest extends TestCase
{
 
    public function testCreateTableArticles()
    {
        $generator  = new Generator();
        
        $table = new Table('articles');
        $table->createColumn('id', new IntegerType())
                ->primary()
                ->autoincrement();
        
        $table->createColumn('name', new VarcharType(255));
        
        $sql = 'CREATE TABLE `articles` (';
        $sql .= '`id` int(11) NOT NULL AUTO_INCREMENT, ';
        $sql .= '`name` VARCHAR(255) DEFAULT NULL, ';
        $sql .= 'PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;';
        
        $patches = $generator->createTablePatches($table);
        
        $this->assertTrue($patches instanceof PatchList);
        $this->assertSame(1, $patches->count());
        $this->assertTrue($patches->first() instanceof Patch);
        
        $this->assertSame(Patch::NON_BREAKABLE, $patches->first()->getLevel());
        $this->assertSame($sql, $patches->first()->getQuery());
    }
    
}