<?php

/**
 * Description of TranslatorTest
 *
 * @author David Bittner <david.bittner@seznam.cz>
 */
class TranslatorTest extends PHPUnit_Framework_TestCase
{

    public function testTransAlterTableHeader()
    {
        $translator = new \Davajlama\SchemaBuilder\Driver\MySql\Translator();
        $this->assertSame('ALTER TABLE `users`', $translator->transAlterTableHeader('users'));
        $this->assertSame('ALTER TABLE `articles`', $translator->transAlterTableHeader('articles'));
    }
    
    public function testTransDropColumn()
    {
        $translator = new \Davajlama\SchemaBuilder\Driver\MySql\Translator();
        $this->assertSame('ALTER TABLE `users` DROP COLUMN `username`;', $translator->transDropColumn('users', 'username'));
        $this->assertSame('ALTER TABLE `users` DROP COLUMN `password`;', $translator->transDropColumn('users', 'password'));
        $this->assertSame('ALTER TABLE `articles` DROP COLUMN `name`;', $translator->transDropColumn('articles', 'name'));
        $this->assertSame('ALTER TABLE `articles` DROP COLUMN `note`;', $translator->transDropColumn('articles', 'note'));
    }
    
    public function testTransDropTable()
    {
        $translator = new \Davajlama\SchemaBuilder\Driver\MySql\Translator();
        $this->assertSame('DROP TABLE `users`;', $translator->transDropTable('users'));
        $this->assertSame('DROP TABLE `articles`;', $translator->transDropTable('articles'));
    }
    
    /**
     * @expectedException \Exception
     */
    public function testTransType()
    {
        $translator = new \Davajlama\SchemaBuilder\Driver\MySql\Translator();
        $this->assertSame('VARCHAR(128)', $translator->transType(new Davajlama\SchemaBuilder\Schema\Type\VarcharType(128)));
        $this->assertSame('VARCHAR(255)', $translator->transType(new Davajlama\SchemaBuilder\Schema\Type\VarcharType(255)));
        
        $this->assertSame('int(11)', $translator->transType(new \Davajlama\SchemaBuilder\Schema\Type\IntegerType()));
        
        $translator->transType(new FoobarType());
    }
    
    /**
     * @expectedException \Exception
     */
    public function testTransDefaultValue()
    {
        $translator = new \Davajlama\SchemaBuilder\Driver\MySql\Translator();
        $this->assertSame("DEFAULT 'one'", $translator->transDefaultValue(new Davajlama\SchemaBuilder\Schema\Value\StringValue('one')));
        $this->assertSame("DEFAULT 'two'", $translator->transDefaultValue(new Davajlama\SchemaBuilder\Schema\Value\StringValue('two')));
        
        $this->assertSame('DEFAULT 255', $translator->transDefaultValue(new Davajlama\SchemaBuilder\Schema\Value\NumberValue(255)));
        $this->assertSame('DEFAULT 255', $translator->transDefaultValue(new Davajlama\SchemaBuilder\Schema\Value\NumberValue("255")));
        $this->assertSame('DEFAULT 3.14', $translator->transDefaultValue(new Davajlama\SchemaBuilder\Schema\Value\NumberValue(3.14)));
        $this->assertSame('DEFAULT 3.14', $translator->transDefaultValue(new Davajlama\SchemaBuilder\Schema\Value\NumberValue("3.14")));
        
        $this->assertSame('DEFAULT CURRENT_TIMESTAMP()', $translator->transDefaultValue(new Davajlama\SchemaBuilder\Schema\Value\ExpressionValue('CURRENT_TIMESTAMP()')));
        
        $this->assertSame('DEFAULT NULL', $translator->transDefaultValue(new Davajlama\SchemaBuilder\Schema\Value\NullValue()));
        
        $translator->transDefaultValue(new FoobarValue());
    }
}

class FoobarType implements Davajlama\SchemaBuilder\Schema\TypeInterface
{
    
}

class FoobarValue implements \Davajlama\SchemaBuilder\Schema\ValueInteraface
{
    public function getValue()
    {
        
    }

}