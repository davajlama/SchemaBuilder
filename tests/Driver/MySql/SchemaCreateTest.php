<?php

namespace Davajlama\SchemaBuilder\Test\Driver\MySql;

use Davajlama\SchemaBuilder\Adapter\AdapterInterface;
use Davajlama\SchemaBuilder\Driver\MySqlDriver;
use Davajlama\SchemaBuilder\Patch;
use Davajlama\SchemaBuilder\PatchList;
use Davajlama\SchemaBuilder\Schema;
use Davajlama\SchemaBuilder\Schema\Type\IntegerType;
use Davajlama\SchemaBuilder\Schema\Type\TextType;
use Davajlama\SchemaBuilder\Schema\Type\VarcharType;
use Davajlama\SchemaBuilder\Schema\Value\StringValue;
use Davajlama\SchemaBuilder\SchemaBuilder;
use Davajlama\SchemaBuilder\Test\TestCase;

/**
 * Description of SchemaCreateTest
 *
 * @author David Bittner <david.bittner@seznam.cz>
 */
class SchemaCreateTest extends TestCase
{
    
    public function testCreateSchema()
    {
        $adapter = $this->createMock(AdapterInterface::class);
        $adapter->method('fetchAll')->with($this->multipleWith([
            "SHOW TABLES LIKE 'articles'",
            "DESCRIBE articles",
            "SHOW TABLES LIKE 'users'",
            "DESCRIBE users",
            "SHOW TABLES LIKE 'products'",
            "DESCRIBE products",
        ]))->will($this->multipleReturn()
                ->ret("SHOW TABLES LIKE 'articles'", [])
                ->ret("SHOW TABLES LIKE 'users'", [])
                ->ret("SHOW TABLES LIKE 'products'", [])
                ->ret("DESCRIBE articles", [])
                ->ret("DESCRIBE users", [])
                ->ret("DESCRIBE products", [])
                ->toCallback());
        
        $schema = new Schema();
        $articles = $schema->createTable('articles');
        $articles->createColumn('id', new IntegerType())
                    ->primary()
                    ->autoincrement();
        
        $articles->createColumn('title', new VarcharType(255));
        $articles->createColumn('content', new TextType());
        
        $users = $schema->createTable('users');
        $users->createColumn('id', new IntegerType())
                    ->primary()
                    ->autoincrement();
        
        $users->createColumn('username', new VarcharType(64))
                    ->nullable(false)
                    ->unique();
        
        $users->createColumn('password', new VarcharType(64))
                    ->nullable(false);
        
        $products = $schema->createTable('products');
        $products->createColumn('id', new IntegerType())
                    ->primary()
                    ->autoincrement();
        
        $products->createColumn('name', new VarcharType(255))
                    ->setDefaultValue(new StringValue('New product #1'));
        
        $products->createColumn('supplier', new VarcharType(64))
                    ->nullable(false)
                    ->setDefaultValue(new StringValue('Davajlama'));
                
        
        $builder = new SchemaBuilder(new MySqlDriver($adapter));
        $patches = $builder->buildSchemaPatches($schema);
        
        $this->assertTrue($patches instanceof PatchList);
        $this->assertSame(3, $patches->count());
        
        // table articles
        $sql = 'CREATE TABLE `articles` (';
        $sql .= '`id` INT(11) NOT NULL AUTO_INCREMENT, ';
        $sql .= '`title` VARCHAR(255) DEFAULT NULL, ';
        $sql .= '`content` TEXT DEFAULT NULL, ';
        $sql .= 'PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;';
        
        $patch = $patches->first();
        $this->assertSame(Patch::NON_BREAKABLE, $patch->getLevel());
        $this->assertSame($sql, $patch->getQuery());
        
        // table users
        $sql = 'CREATE TABLE `users` (';
        $sql .= '`id` INT(11) NOT NULL AUTO_INCREMENT, ';
        $sql .= '`username` VARCHAR(64) NOT NULL, ';
        $sql .= '`password` VARCHAR(64) NOT NULL, ';
        $sql .= 'PRIMARY KEY (`id`), ';
        $sql .= 'UNIQUE KEY `unique_username_asc` (`username`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;';
        
        $patch = $patches->next();
        $this->assertSame(Patch::NON_BREAKABLE, $patch->getLevel());
        $this->assertSame($sql, $patch->getQuery());
        
        // table products
        $sql = 'CREATE TABLE `products` (';
        $sql .= '`id` INT(11) NOT NULL AUTO_INCREMENT, ';
        $sql .= '`name` VARCHAR(255) DEFAULT \'New product #1\', ';
        $sql .= '`supplier` VARCHAR(64) NOT NULL DEFAULT \'Davajlama\', ';
        $sql .= 'PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;';
        
        $patch = $patches->next();
        $this->assertSame(Patch::NON_BREAKABLE, $patch->getLevel());
        $this->assertSame($sql, $patch->getQuery());
        
    }
    
}
