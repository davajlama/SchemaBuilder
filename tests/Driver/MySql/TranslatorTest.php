<?php

namespace Davajlama\SchemaBuilder\Test\Driver\MySql;

use Davajlama\SchemaBuilder\Driver\MySql\Translator;
use Davajlama\SchemaBuilder\Schema\Index;
use Davajlama\SchemaBuilder\Schema\Type\BigIntType;
use Davajlama\SchemaBuilder\Schema\Type\BinaryType;
use Davajlama\SchemaBuilder\Schema\Type\CharType;
use Davajlama\SchemaBuilder\Schema\Type\DateTimeType;
use Davajlama\SchemaBuilder\Schema\Type\DateType;
use Davajlama\SchemaBuilder\Schema\Type\DecimalType;
use Davajlama\SchemaBuilder\Schema\Type\IntegerType;
use Davajlama\SchemaBuilder\Schema\Type\LongTextType;
use Davajlama\SchemaBuilder\Schema\Type\TextType;
use Davajlama\SchemaBuilder\Schema\Type\TimestampType;
use Davajlama\SchemaBuilder\Schema\Type\TinyIntType;
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
    
    public function testTransType()
    {
        $translator = new Translator();
        $this->assertSame('VARCHAR(128)', $translator->transType(new VarcharType(128)));
        $this->assertSame('VARCHAR(255)', $translator->transType(new VarcharType(255)));
        $this->assertSame('CHAR(2)', $translator->transType(new CharType(2)));
        $this->assertSame('BINARY(32)', $translator->transType(new BinaryType(32)));
        
        $this->assertSame('INT(11)', $translator->transType(new IntegerType()));
        $this->assertSame('TINYINT(4)', $translator->transType(new TinyIntType()));
        $this->assertSame('TINYINT(1)', $translator->transType(new TinyIntType(1)));
        $this->assertSame('BIGINT(11)', $translator->transType(new BigIntType()));
        $this->assertSame('BIGINT(16)', $translator->transType(new BigIntType(16)));
        $this->assertSame('DECIMAL(10,0)', $translator->transType(new DecimalType(10)));
        $this->assertSame('DECIMAL(10,2)', $translator->transType(new DecimalType(10, 2)));

        $this->assertSame('TEXT', $translator->transType(new TextType()));
        $this->assertSame('LONGTEXT', $translator->transType(new LongTextType()));
        $this->assertSame('DATE', $translator->transType(new DateType()));
        $this->assertSame('DATETIME', $translator->transType(new DateTimeType()));
        $this->assertSame('TIMESTAMP', $translator->transType(new TimestampType()));
    }

    /**
     * @expectedException Exception
     */
    public function transTypeException()
    {
        $translator = new Translator();
        $translator->transType(new FoobarType());
    }
    
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
    }

    /**
     * @expectedException Exception
     */
    public function transDefaultValueException()
    {
        $translator = new Translator();
        $translator->transDefaultValue(new FoobarValue());
    }

    public function testTransIndexName()
    {
        $translator = new Translator();

        $shortUniqueIndex = new Index(true);
        $shortUniqueIndex->addColumns(['id', 'product_id']);
        $this->assertSame('unique_id_asc_product_id_asc', $translator->transIndexName($shortUniqueIndex));


        $mediumUniqueIndex = new Index(true);
        $mediumUniqueIndex->addColumns(['collection_name', 'collection_slug', 'collection_type']);
        $this->assertSame('uCollNameCollSlugCollType', $translator->transIndexName($mediumUniqueIndex));


        $longUniqueIndex = new Index(true);
        $longUniqueIndex->addColumns($cols = ['id', 'name', 'description', 'note', 'time', 'age', 'contact', 'full', 'enabled']);
        $this->assertSame("uIdNameDescNoteTimeAgeContFullEnab", $translator->transIndexName($longUniqueIndex));
    }
    
}