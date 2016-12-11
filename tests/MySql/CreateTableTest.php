<?php

/**
 * Description of CreateTableTest
 *
 * @author David Bittner <david.bittner@seznam.cz>
 */
class CreateTableTest extends PHPUnit_Framework_TestCase
{
 
    public function testCreateTableArticles()
    {
        $adapter = new FakeAdapter();
        $inspector = new Davajlama\SchemaBuilder\Driver\MySql\Inspector($adapter);
        $generator = new Davajlama\SchemaBuilder\Driver\MySql\Generator($inspector);
        
        $table = new \Davajlama\SchemaBuilder\Schema\Table('articles');
        $table->createColumn('id', new \Davajlama\SchemaBuilder\Schema\Type\IntegerType())
                ->primary()
                ->autoincrement();
        
        $table->createColumn('name', new Davajlama\SchemaBuilder\Schema\Type\VarcharType(255));
        
        $sql = 'CREATE TABLE `articles` (';
        $sql .= '`id` int(11) NOT NULL AUTO_INCREMENT, ';
        $sql .= '`name` VARCHAR(255) DEFAULT NULL, ';
        $sql .= 'PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;';
        
        $patches = $generator->createTablePatches($table);
        
        $this->assertTrue($patches instanceof \Davajlama\SchemaBuilder\PatchList);
        $this->assertTrue($patches->count() === 1);
        $this->assertTrue($patches->first() instanceof Davajlama\SchemaBuilder\Patch);
        
        $this->assertSame(Davajlama\SchemaBuilder\Patch::NON_BREAKABLE, $patches->first()->getLevel());
        $this->assertSame($sql, $patches->first()->getQuery());
    }
    
}

class FakeAdapter implements Davajlama\SchemaBuilder\Adapter\AdapterInterface
{
    
    public function fetchAll($sql)
    {
        
    }

    public function query($sql)
    {
        
    }

}