<?php


namespace Driver\MySql;


use Davajlama\SchemaBuilder\Driver\MySql\Generator;
use Davajlama\SchemaBuilder\Driver\MySql\Translator;
use Davajlama\SchemaBuilder\Patch;
use Davajlama\SchemaBuilder\Schema;
use Davajlama\SchemaBuilder\Test\TestCase;

class DropTableTest extends TestCase
{

    public function testDropTable()
    {
        $schema = new Schema();
        $schema->createTable('users');
        $schema->createTable('articles');
        $schema->createTable('products');

        $generator = new Generator();
        $translator = new Translator();

        $patches = $generator->dropTablePatches($schema, ['users', 'articles', 'products']);
        $this->assertSame(0, $patches->count());




        $patches = $generator->dropTablePatches($schema, ['categories']);
        $this->assertSame(1, $patches->count());

        $patch = $patches->first();
        $this->assertSame(Patch::BREAKABLE, $patch->getLevel());
        $this->assertSame($translator->transDropTable('categories'), $patch->getQuery());




        $patches = $generator->dropTablePatches($schema, ['categories', 'profiles']);
        $this->assertSame(2, $patches->count());

        $patch = $patches->first();
        $this->assertSame(Patch::BREAKABLE, $patch->getLevel());
        $this->assertSame($translator->transDropTable('categories'), $patch->getQuery());

        $patch = $patches->next();
        $this->assertSame(Patch::BREAKABLE, $patch->getLevel());
        $this->assertSame($translator->transDropTable('profiles'), $patch->getQuery());
    }

}