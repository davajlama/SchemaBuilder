<?php

namespace Davajlama\SchemaBuilder\Test\Driver\MySql;

use Davajlama\SchemaBuilder\Driver\MySql\Translator;
use Davajlama\SchemaBuilder\Schema\Type\DateTimeType;
use Davajlama\SchemaBuilder\Schema\Type\IntegerType;
use Davajlama\SchemaBuilder\Schema\Type\TextType;
use Davajlama\SchemaBuilder\Schema\Type\VarcharType;
use Davajlama\SchemaBuilder\Schema\Value\ExpressionValue;
use Davajlama\SchemaBuilder\Schema\Value\NullValue;
use Davajlama\SchemaBuilder\Schema\Value\NumberValue;
use Davajlama\SchemaBuilder\Schema\Value\StringValue;
use Davajlama\SchemaBuilder\Test\Fixture\FoobarType;
use Davajlama\SchemaBuilder\Test\Fixture\FoobarValue;
use Davajlama\SchemaBuilder\Test\TestCase;

/**
 * Description of TranslatorTest
 *
 * @author David Bittner <david.bittner@seznam.cz>
 */
class TranslatorTest extends TestCase
{

    public function testTransAlterTableHeader()
    {
        $translator = new Translator();
        $this->assertSame('ALTER TABLE `users`', $translator->transAlterTableHeader('users'));
        $this->assertSame('ALTER TABLE `articles`', $translator->transAlterTableHeader('articles'));
    }
    
    public function testTransDropColumn()
    {
        $translator = new Translator();
        $this->assertSame('ALTER TABLE `users` DROP COLUMN `username`;', $translator->transDropColumn('users', 'username'));
        $this->assertSame('ALTER TABLE `users` DROP COLUMN `password`;', $translator->transDropColumn('users', 'password'));
        $this->assertSame('ALTER TABLE `articles` DROP COLUMN `name`;', $translator->transDropColumn('articles', 'name'));
        $this->assertSame('ALTER TABLE `articles` DROP COLUMN `note`;', $translator->transDropColumn('articles', 'note'));
    }
    
    public function testTransDropTable()
    {
        $translator = new Translator();
        $this->assertSame('DROP TABLE `users`;', $translator->transDropTable('users'));
        $this->assertSame('DROP TABLE `articles`;', $translator->transDropTable('articles'));
    }
    
    public function testTransUniqueKey()
    {
        $translator = new Translator();
        $this->assertSame('UNIQUE KEY `unique_login_asc` (`login`)', $translator->transUniqueKey('unique_login_asc', 'login'));
        $this->assertSame('UNIQUE KEY `unique_remote_id_asc` (`remote_id`)', $translator->transUniqueKey('unique_remote_id_asc', 'remote_id'));
    }
    
    public function testTransPrimaryKey()
    {
        $translator = new Translator();
        $this->assertSame('PRIMARY KEY (`id`)', $translator->transPrimaryKey(['id']));
        $this->assertSame('PRIMARY KEY (`id`, `color_id`)', $translator->transPrimaryKey(['id', 'color_id']));
        $this->assertSame('PRIMARY KEY (`id`, `color_id`, `size_id`)', $translator->transPrimaryKey(['id', 'color_id', 'size_id']));
    }
    
    public function testTransCreateTableHeader()
    {
        $translator = new Translator();
        $this->assertSame('CREATE TABLE `users` (', $translator->transCreateTableHeader('users'));
        $this->assertSame('CREATE TABLE `articles` (', $translator->transCreateTableHeader('articles'));
    }
    
    public function testTransCreateTableFooter()
    {
        $translator = new Translator();
        $this->assertSame(') ENGINE=InnoDB DEFAULT CHARSET=utf8;', $translator->transCreateTableFooter('InnoDB', 'utf8'));
        $this->assertSame(') ENGINE=MyISAM DEFAULT CHARSET=utf8-bin;', $translator->transCreateTableFooter('MyISAM', 'utf8-bin'));
    }
    
    /**
     * @expectedException Exception
     */
    public function testTransType()
    {
        $translator = new Translator();
        $this->assertSame('VARCHAR(128)', $translator->transType(new VarcharType(128)));
        $this->assertSame('VARCHAR(255)', $translator->transType(new VarcharType(255)));
        
        $this->assertSame('int(11)', $translator->transType(new IntegerType()));
        
        $this->assertSame('TEXT', $translator->transType(new TextType()));
        $this->assertSame('DATETIME', $translator->transType(new DateTimeType()));
        
        $translator->transType(new FoobarType());
    }
    
    /**
     * @expectedException Exception
     */
    public function testTransDefaultValue()
    {
        $translator = new Translator();
        $this->assertSame("DEFAULT 'one'", $translator->transDefaultValue(new StringValue('one')));
        $this->assertSame("DEFAULT 'two'", $translator->transDefaultValue(new StringValue('two')));
        
        $this->assertSame('DEFAULT 255', $translator->transDefaultValue(new NumberValue(255)));
        $this->assertSame('DEFAULT 255', $translator->transDefaultValue(new NumberValue("255")));
        $this->assertSame('DEFAULT 3.14', $translator->transDefaultValue(new NumberValue(3.14)));
        $this->assertSame('DEFAULT 3.14', $translator->transDefaultValue(new NumberValue("3.14")));
        
        $this->assertSame('DEFAULT CURRENT_TIMESTAMP()', $translator->transDefaultValue(new ExpressionValue('CURRENT_TIMESTAMP()')));
        
        $this->assertSame('DEFAULT NULL', $translator->transDefaultValue(new NullValue()));
        
        $translator->transDefaultValue(new FoobarValue());
    }
}