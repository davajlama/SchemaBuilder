<?php

namespace Davajlama\SchemaBuilder\Test\Driver\MySql\Suite;

use Davajlama\SchemaBuilder\Driver\MySqlDriver;
use Davajlama\SchemaBuilder\Schema;
use Davajlama\SchemaBuilder\Schema\Type;
use Davajlama\SchemaBuilder\SchemaBuilder;
use Davajlama\SchemaBuilder\SchemaCreator;
use Davajlama\SchemaBuilder\Test\TestCase;

/**
 * Description of IndexesTest
 *
 * @author David Bittner <david.bittner@seznam.cz>
 */
class IndexesTest extends TestCase
{
    
    use \Davajlama\SchemaBuilder\Test\MySqlAdapterProviderTrait;
    
    public function testIndexes()
    {
        if($this->getAdapter()) {
            $this->createTest();
            $this->alterTest();
        } else {
            $this->markTestSkipped("Must set ENV variables for DB connection");
        }
    }
    
    protected function createTest()
    {
        $driver     = new MySqlDriver($this->getAdapter());
        $builder    = new SchemaBuilder($driver);
        $creator    = new SchemaCreator($driver);
        
        $patches = $builder->buildSchemaPatches($this->getOriginalSchema());
        
        $this->assertSame(2, $patches->count());
        
        $creator->applyPatches($patches);
    }
    
    protected function getOriginalSchema()
    {
        $schema = new Schema();
        $table = $schema->createTable('indexes_suite_tests');
        $table->createColumn('username', Type::varcharType(64))->unique();
        $table->createColumn('password', Type::varcharType(64))->unique();
        $table->createColumn('email', Type::varcharType(64));
        $table->createColumn('created', Type::varcharType(64));
        
        $table->createIndex()
                ->addColumn('email')
                ->addColumn('created');
        
        return $schema;
    }
    
    protected function alterTest()
    {
        $driver     = new MySqlDriver($this->getAdapter());
        $builder    = new SchemaBuilder($driver);
        $creator    = new SchemaCreator($driver);
        
        $patches = $builder->buildSchemaPatches($this->getUpdatedSchema());
        
        $this->assertSame(4, $patches->count());
        
        $creator->applyPatches($patches);
    }
    
    protected function getUpdatedSchema()
    {
        $schema = new Schema();
        $table = $schema->createTable('indexes_suite_tests');
        $table->createColumn('username', Type::varcharType(64))->unique();
        $table->createColumn('password', Type::varcharType(64)); // delete index
        $table->createColumn('email', Type::varcharType(64));
        $table->createColumn('created', Type::varcharType(64));
        
        $table->createUniqueIndex()
                    ->addColumn('email')
                    ->addColumn('created'); // change non-uqnie to unique
        
        $table->createUniqueIndex()
                    ->addColumn('username')
                    ->addColumn('password'); // create unique index
        
        return $schema;
    }
    
}
