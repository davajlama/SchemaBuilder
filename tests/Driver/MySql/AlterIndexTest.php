<?php

namespace Davajlama\SchemaBuilder\Test\Driver\MySql;

use Davajlama\SchemaBuilder\Driver\MySql\Generator;
use Davajlama\SchemaBuilder\Patch;
use Davajlama\SchemaBuilder\Schema\Table;
use Davajlama\SchemaBuilder\Schema\Type;

/**
 * Description of AlterIndexTest
 *
 * @author David Bittner <david.bittner@seznam.cz>
 */
class AlterIndexTest extends \Davajlama\SchemaBuilder\Test\TestCase
{
    
    public function testAlterIndexes()
    {
        $table = new \Davajlama\SchemaBuilder\Schema\Table('users');
        $table->createId();
        $table->createColumn('username', \Davajlama\SchemaBuilder\Schema\Type::varcharType(64))->unique();
        $table->createColumn('password', \Davajlama\SchemaBuilder\Schema\Type::varcharType(64));
        $table->createColumn('email', \Davajlama\SchemaBuilder\Schema\Type::varcharType(64));
        $table->createColumn('group', \Davajlama\SchemaBuilder\Schema\Type::varcharType(64));
        $table->createColumn('role', \Davajlama\SchemaBuilder\Schema\Type::varcharType(64));
        $table->createColumn('created', \Davajlama\SchemaBuilder\Schema\Type::varcharType(64))->unique();
        
        $table->createIndex()
                    ->addColumn('group');
        
        $table->createIndex()
                    ->addColumn('email')
                    ->addColumn('created');
        
        $table->createUniqueIndex()
                    ->addColumn('username')
                    ->addColumn('password');
        
        $table->createIndex()
                    ->addColumn('group')
                    ->addColumn('role');
        
        // add created unique
        // change group unqiue to non-unique
        // add email, created no-unique
        // add username, password unique
        // remove password
        // remove email, group
        // change role non-unique to unique
        // change group, role unique to non-unique
        
        $rawIndexes = [
            ['Key_name' => 'PRIMARY',               'Non_unique' => 0, 'Column_name' => 'id'],
            ['Key_name' => 'unique_username_asc',   'Non_unique' => 0, 'Column_name' => 'username'],
            ['Key_name' => 'unique_group_asc',      'Non_unique' => 0, 'Column_name' => 'group'],
            ['Key_name' => 'unique_password_asc',   'Non_unique' => 0, 'Column_name' => 'password'],
            ['Key_name' => 'index_role_asc',        'Non_unique' => 1, 'Column_name' => 'role'],
            
            ['Key_name' => 'index_email_asc_group_asc',   'Non_unique' => 1, 'Column_name' => 'email'],
            ['Key_name' => 'index_email_asc_group_asc',   'Non_unique' => 1, 'Column_name' => 'group'],
            
            ['Key_name' => 'unique_group_asc_role_asc',   'Non_unique' => 0, 'Column_name' => 'group'],
            ['Key_name' => 'unique_group_asc_role_asc',   'Non_unique' => 0, 'Column_name' => 'role'],
        ];
        
        $generator  = new \Davajlama\SchemaBuilder\Driver\MySql\Generator();
        $patches    = $generator->alterIndexes($table, $rawIndexes);

        $this->assertTrue($patches instanceof \Davajlama\SchemaBuilder\PatchList);
        $this->assertSame(10, $patches->count());

        $patch = $patches->first();
        $sql = 'ALTER TABLE `users` DROP INDEX `unique_group_asc`;';
        $this->assertSame($sql, $patch->getQuery());
        $this->assertSame(\Davajlama\SchemaBuilder\Patch::NON_BREAKABLE, $patch->getLevel());

        $patch = $patches->next();
        $sql = 'ALTER TABLE `users` DROP INDEX `unique_password_asc`;';
        $this->assertSame($sql, $patch->getQuery());
        $this->assertSame(\Davajlama\SchemaBuilder\Patch::NON_BREAKABLE, $patch->getLevel());

        $patch = $patches->next();
        $sql = 'ALTER TABLE `users` DROP INDEX `index_role_asc`;';
        $this->assertSame($sql, $patch->getQuery());
        $this->assertSame(\Davajlama\SchemaBuilder\Patch::NON_BREAKABLE, $patch->getLevel());

        $patch = $patches->next();
        $sql = 'ALTER TABLE `users` DROP INDEX `index_email_asc_group_asc`;';
        $this->assertSame($sql, $patch->getQuery());
        $this->assertSame(\Davajlama\SchemaBuilder\Patch::NON_BREAKABLE, $patch->getLevel());

        $patch = $patches->next();
        $sql = 'ALTER TABLE `users` DROP INDEX `unique_group_asc_role_asc`;';
        $this->assertSame($sql, $patch->getQuery());
        $this->assertSame(\Davajlama\SchemaBuilder\Patch::NON_BREAKABLE, $patch->getLevel());
        
        $patch = $patches->next();
        $sql = 'ALTER TABLE `users` ADD UNIQUE INDEX `unique_created_asc` (`created` ASC);';
        $this->assertSame($sql, $patch->getQuery());
        $this->assertSame(\Davajlama\SchemaBuilder\Patch::NON_BREAKABLE, $patch->getLevel());
        
        $patch = $patches->next();
        $sql = 'ALTER TABLE `users` ADD INDEX `index_group_asc` (`group` ASC);';
        $this->assertSame($sql, $patch->getQuery());
        $this->assertSame(\Davajlama\SchemaBuilder\Patch::NON_BREAKABLE, $patch->getLevel());
        
        $patch = $patches->next();
        $sql = 'ALTER TABLE `users` ADD INDEX `index_email_asc_created_asc` (`email` ASC, `created` ASC);';
        $this->assertSame($sql, $patch->getQuery());
        $this->assertSame(\Davajlama\SchemaBuilder\Patch::NON_BREAKABLE, $patch->getLevel());
        
        $patch = $patches->next();
        $sql = 'ALTER TABLE `users` ADD UNIQUE INDEX `unique_username_asc_password_asc` (`username` ASC, `password` ASC);';
        $this->assertSame($sql, $patch->getQuery());
        $this->assertSame(\Davajlama\SchemaBuilder\Patch::NON_BREAKABLE, $patch->getLevel());
        
        $patch = $patches->next();
        $sql = 'ALTER TABLE `users` ADD INDEX `index_group_asc_role_asc` (`group` ASC, `role` ASC);';
        $this->assertSame($sql, $patch->getQuery());
        $this->assertSame(\Davajlama\SchemaBuilder\Patch::NON_BREAKABLE, $patch->getLevel());
    }

    public function testDropPrimaryIndex()
    {
        $table = new Table('users');
        $table->createColumn('id1', Type::integerType());
        $table->createColumn('id2', Type::integerType());
        $table->createUniqueIndex()->addColumns(['id1', 'id2']);

        $rawIndexes = [
            ['Key_name' => 'PRIMARY', 'Non_unique' => 0, 'Column_name' => 'id1'],
            ['Key_name' => 'PRIMARY', 'Non_unique' => 0, 'Column_name' => 'id2'],
        ];

        $generator = new Generator();
        $patches    = $generator->alterIndexes($table, $rawIndexes);

        $this->assertSame(2, $patches->count());

        $patch = $patches->first();
        $sql = 'ALTER TABLE `users` ADD UNIQUE INDEX `unique_id1_asc_id2_asc` (`id1` ASC, `id2` ASC);';
        $this->assertSame($sql, $patch->getQuery());
        $this->assertSame(Patch::NON_BREAKABLE, $patch->getLevel());

        $patch = $patches->next();
        $sql = 'ALTER TABLE `users` DROP PRIMARY KEY;';
        $this->assertSame($sql, $patch->getQuery());
        $this->assertSame(Patch::NON_BREAKABLE, $patch->getLevel());
    }

    public function testAddPrimaryIndex()
    {
        $table = new Table('users');
        $table->createColumn('id1', Type::integerType())->primary();
        $table->createColumn('id2', Type::integerType())->primary();

        $rawIndexes = [
            ['Key_name' => 'unique_id1_asc_id2_asc', 'Non_unique' => 0, 'Column_name' => 'id1'],
            ['Key_name' => 'unique_id1_asc_id2_asc', 'Non_unique' => 0, 'Column_name' => 'id2'],
        ];

        $generator  = new Generator();
        $patches    = $generator->alterIndexes($table, $rawIndexes);

        $this->assertSame(2, $patches->count());

        $patch = $patches->first();
        $sql = 'ALTER TABLE `users` DROP INDEX `unique_id1_asc_id2_asc`;';
        $this->assertSame($sql, $patch->getQuery());
        $this->assertSame(Patch::NON_BREAKABLE, $patch->getLevel());

        $patch = $patches->next();
        $sql = 'ALTER TABLE `users` ADD PRIMARY KEY (`id1`, `id2`);';
        $this->assertSame($sql, $patch->getQuery());
        $this->assertSame(Patch::NON_BREAKABLE, $patch->getLevel());
    }
    
}
