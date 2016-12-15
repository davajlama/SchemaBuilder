<?php

namespace Davajlama\SchemaBuilder\Test\Driver\MySql;

use Davajlama\SchemaBuilder\Driver\MySql\Generator;
use Davajlama\SchemaBuilder\Patch;
use Davajlama\SchemaBuilder\PatchList;
use Davajlama\SchemaBuilder\Schema\Table;
use Davajlama\SchemaBuilder\Schema\Type\IntegerType;
use Davajlama\SchemaBuilder\Schema\Type\TextType;
use Davajlama\SchemaBuilder\Schema\Type\VarcharType;
use Davajlama\SchemaBuilder\Test\TestCase;

/**
 * Description of AlterTableTest
 *
 * @author David Bittner <david.bittner@seznam.cz>
 */
class AlterTableTest extends TestCase
{
    
    public function testAlterTableArticles()
    {
        $table = new Table('articles');
        $table->createColumn('id', new IntegerType())
                    ->primary()
                    ->autoincrement();
        
        $table->createColumn('content', new TextType());
        $table->createColumn('firstname', new VarcharType(255));
        $table->createColumn('lastname', new VarcharType(255));
        
        $rawColumns = [
            ['Field' => 'id',       'Type' => 'int(11)',        'Null' => 'NO',     'Key' => 'PRI', 'Default' => null, 'Extra' => 'auto_increment'],
            ['Field' => 'content',  'Type' => 'text',           'Null' => 'YES',    'Key' => '',    'Default' => null, 'Extra' => ''],
            ['Field' => 'name',     'Type' => 'varchar(255)',   'Null' => 'YES',    'Key' => '',    'Default' => null, 'Extra' => ''],
        ];
        
        $generator = new Generator();
        $patches = $generator->alterTablePatches($table, $rawColumns);
        
        $this->assertTrue($patches instanceof PatchList);
        $this->assertSame(3, $patches->count());
        
        $patch = $patches->first();
        $this->assertSame(Patch::NON_BREAKABLE, $patch->getLevel());
        $this->assertSame("ALTER TABLE `articles` ADD COLUMN `firstname` VARCHAR(255) DEFAULT NULL AFTER `content`;", $patch->getQuery());
        
        $patch = $patches->next();
        $this->assertSame(Patch::NON_BREAKABLE, $patch->getLevel());
        $this->assertSame("ALTER TABLE `articles` ADD COLUMN `lastname` VARCHAR(255) DEFAULT NULL AFTER `firstname`;", $patch->getQuery());
        
        $patch = $patches->next();
        $this->assertSame(Patch::BREAKABLE, $patch->getLevel());
        $this->assertSame("ALTER TABLE `articles` DROP COLUMN `name`;", $patch->getQuery());
    }
    
}
