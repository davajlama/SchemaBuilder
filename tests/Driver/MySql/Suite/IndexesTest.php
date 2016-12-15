<?php

namespace Davajlama\SchemaBuilder\Test\Driver\MySql\Suite;

use Davajlama\SchemaBuilder\Adapter\AdapterInterface;
use Davajlama\SchemaBuilder\Bridge\PDOAdapter;
use Davajlama\SchemaBuilder\Driver\MySqlDriver;
use Davajlama\SchemaBuilder\Schema;
use Davajlama\SchemaBuilder\Schema\Type;
use Davajlama\SchemaBuilder\SchemaBuilder;
use Davajlama\SchemaBuilder\SchemaCreator;
use Davajlama\SchemaBuilder\Test\TestCase;
use PDO;

/**
 * Description of IndexesTest
 *
 * @author David Bittner <david.bittner@seznam.cz>
 */
class IndexesTest extends TestCase
{
    /** @var AdapterInterface */
    private $adapter;
    
    public function testIndexes()
    {
        if($this->getAdapter()) {
            $this->createTest();
            $this->alterTest();
        } else {
            $this->markTestSkipped("Muset set ENV variables for DB connection");
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
    
    protected function getAdapter()
    {
        if($this->adapter === null) {
            $host       = getenv('TESTHOST');
            $username   = getenv('TESTUSER');
            $schema     = getenv('TESTDB');
            
            $dsn = "mysql:host=$host;dbname=$schema";
            
            $options = array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
            ); 

            $pdo = new PDO($dsn, $username, null, $options);
            $this->adapter = new PDOAdapter($pdo);
        }        
        
        return $this->adapter;
    }
    
}
