<?php

namespace Davajlama\SchemaBuilder\Test\Driver\MySql;

use Davajlama\SchemaBuilder\Driver\MySqlDriver;
use Davajlama\SchemaBuilder\Patch;
use Davajlama\SchemaBuilder\Schema\Table;
use Davajlama\SchemaBuilder\Schema\Type;
use Davajlama\SchemaBuilder\Test\Fixture\NullAdapter;
use Davajlama\SchemaBuilder\Test\TestCase;

/**
 * Description of CreateIndexTest
 *
 * @author David Bittner <david.bittner@seznam.cz>
 */
class CreateIndexTest extends TestCase
{

    public function testCreateIndex()
    {
        $adapter    = new NullAdapter();
        $driver     = new MySqlDriver($adapter);
        
        $table = new Table('articles');
        $table->createColumn('username', Type::varcharType(64));
        $table->createColumn('password', Type::varcharType(64));
        $table->createColumn('firstname', Type::varcharType(64));
        $table->createColumn('lastname', Type::varcharType(64));
        $table->createColumn('group', Type::varcharType(64));
        $table->createColumn('role', Type::varcharType(64));
        $table->createColumn('active', Type::varcharType(64));
        $table->createColumn('email', Type::varcharType(64));

        $table->createIndex()->addColumn('username');
        $table->createIndex()
                    ->addColumn('username')
                    ->addColumn('password');
        
        $table->createIndex()
                    ->addColumn('username')
                    ->addColumn('active', false);
        
        $table->createIndex()
                    ->addColumn('firstname', false)
                    ->addColumn('lastname', false);
        
        $table->createIndex()
                    ->addColumn('group')
                    ->addColumn('role')
                    ->addColumn('active');
        
        $table->createUniqueIndex()
                    ->addColumn('username')
                    ->addColumn('password');
        
        
        $patches = $driver->buildTablePatches($table);
        
        $this->assertSame(7, $patches->count());
        
        $patches->first(); // CREATE TABLE STATEMENT
        
        $patch = $patches->next();
        $sql = 'ALTER TABLE `articles` ADD INDEX `index_username_asc` (`username` ASC);';
        $this->assertSame($sql, $patch->getQuery());
        $this->assertSame(Patch::NON_BREAKABLE, $patch->getLevel());
        
        $patch = $patches->next();
        $sql = 'ALTER TABLE `articles` ADD INDEX `index_username_asc_password_asc` (`username` ASC, `password` ASC);';
        $this->assertSame($sql, $patch->getQuery());
        $this->assertSame(Patch::NON_BREAKABLE, $patch->getLevel());
        
        $patch = $patches->next();
        $sql = 'ALTER TABLE `articles` ADD INDEX `index_username_asc_active_desc` (`username` ASC, `active` DESC);';
        $this->assertSame($sql, $patch->getQuery());
        $this->assertSame(Patch::NON_BREAKABLE, $patch->getLevel());
        
        $patch = $patches->next();
        $sql = 'ALTER TABLE `articles` ADD INDEX `index_firstname_desc_lastname_desc` (`firstname` DESC, `lastname` DESC);';
        $this->assertSame($sql, $patch->getQuery());
        $this->assertSame(Patch::NON_BREAKABLE, $patch->getLevel());
        
        $patch = $patches->next();
        $sql = 'ALTER TABLE `articles` ADD INDEX `index_group_asc_role_asc_active_asc` (`group` ASC, `role` ASC, `active` ASC);';
        $this->assertSame($sql, $patch->getQuery());
        $this->assertSame(Patch::NON_BREAKABLE, $patch->getLevel());
        
        $patch = $patches->next();
        $sql = 'ALTER TABLE `articles` ADD UNIQUE INDEX `unique_username_asc_password_asc` (`username` ASC, `password` ASC);';
        $this->assertSame($sql, $patch->getQuery());
        $this->assertSame(Patch::NON_BREAKABLE, $patch->getLevel());
    }
    
}