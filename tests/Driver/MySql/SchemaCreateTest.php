<?php

namespace Davajlama\SchemaBuilder\Test\Driver\MySql;

/**
 * Description of SchemaCreateTest
 *
 * @author David Bittner <david.bittner@seznam.cz>
 */
class SchemaCreateTest extends \Davajlama\SchemaBuilder\Test\TestCase
{
    
    public function testCreateSchema()
    {
        $adapter = $this->createMock(\Davajlama\SchemaBuilder\Adapter\AdapterInterface::class);
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
        
        $schema = new \Davajlama\SchemaBuilder\Schema();
        $articles = $schema->createTable('articles');
        $articles->createColumn('id', new \Davajlama\SchemaBuilder\Schema\Type\IntegerType())
                    ->primary()
                    ->autoincrement();
        
        $articles->createColumn('title', new \Davajlama\SchemaBuilder\Schema\Type\VarcharType(255));
        $articles->createColumn('content', new \Davajlama\SchemaBuilder\Schema\Type\TextType());
        
        $users = $schema->createTable('users');
        $users->createColumn('id', new \Davajlama\SchemaBuilder\Schema\Type\IntegerType())
                    ->primary()
                    ->autoincrement();
        
        $users->createColumn('username', new \Davajlama\SchemaBuilder\Schema\Type\VarcharType(64))
                    ->nullable(false)
                    ->unique();
        
        $users->createColumn('password', new \Davajlama\SchemaBuilder\Schema\Type\VarcharType(64))
                    ->nullable(false);
        
        $products = $schema->createTable('products');
        $products->createColumn('id', new \Davajlama\SchemaBuilder\Schema\Type\IntegerType())
                    ->primary()
                    ->autoincrement();
        
        $products->createColumn('name', new \Davajlama\SchemaBuilder\Schema\Type\VarcharType(255))
                    ->setDefault(new \Davajlama\SchemaBuilder\Schema\Value\StringValue('New product #1'));
        
        $products->createColumn('supplier', new \Davajlama\SchemaBuilder\Schema\Type\VarcharType(64))
                    ->nullable(false)
                    ->setDefault(new \Davajlama\SchemaBuilder\Schema\Value\StringValue('Davajlama'));
                
        
        $builder = new \Davajlama\SchemaBuilder\SchemaBuilder(new \Davajlama\SchemaBuilder\Driver\MySqlDriver($adapter));
        $patches = $builder->buildSchemaPatches($schema);
        
        $this->assertTrue($patches instanceof \Davajlama\SchemaBuilder\PatchList);
        $this->assertSame(3, $patches->count());
        
        // table articles
        $sql = 'CREATE TABLE `articles` (';
        $sql .= '`id` int(11) NOT NULL AUTO_INCREMENT, ';
        $sql .= '`title` VARCHAR(255) DEFAULT NULL, ';
        $sql .= '`content` TEXT() DEFAULT NULL, ';
        $sql .= 'PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;';
        
        $patch = $patches->first();
        $this->assertSame(\Davajlama\SchemaBuilder\Patch::NON_BREAKABLE, $patch->getLevel());
        $this->assertSame($sql, $patch->getQuery());
        
        // table users
        $sql = 'CREATE TABLE `users` (';
        $sql .= '`id` int(11) NOT NULL AUTO_INCREMENT, ';
        $sql .= '`username` VARCHAR(64) NOT NULL, ';
        $sql .= '`password` VARCHAR(64) NOT NULL, ';
        $sql .= 'PRIMARY KEY (`id`), ';
        $sql .= 'UNIQUE KEY `username_UNIQUE` (`username`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;';
        
        $patch = $patches->next();
        $this->assertSame(\Davajlama\SchemaBuilder\Patch::NON_BREAKABLE, $patch->getLevel());
        $this->assertSame($sql, $patch->getQuery());
        
        // table products
        $sql = 'CREATE TABLE `products` (';
        $sql .= '`id` int(11) NOT NULL AUTO_INCREMENT, ';
        $sql .= '`name` VARCHAR(255) DEFAULT \'New product #1\', ';
        $sql .= '`supplier` VARCHAR(64) NOT NULL DEFAULT \'Davajlama\', ';
        $sql .= 'PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;';
        
        $patch = $patches->next();
        $this->assertSame(\Davajlama\SchemaBuilder\Patch::NON_BREAKABLE, $patch->getLevel());
        $this->assertSame($sql, $patch->getQuery());
        
    }
    
}
