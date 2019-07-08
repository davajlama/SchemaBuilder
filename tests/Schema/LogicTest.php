<?php
/**
 * Created by PhpStorm.
 * User: David
 * Date: 08.07.2019
 * Time: 16:52
 */

namespace Davajlama\SchemaBuilder\Test\Schema;


use Davajlama\SchemaBuilder\Schema;
use Davajlama\SchemaBuilder\Test\TestCase;

class LogicTest extends TestCase
{

    public function testDuplicity()
    {
        $this->expectException(Schema\LogicException::class);

        $schema = new Schema();

        $table1 = new Schema\Table('users');
        $table2 = new Schema\Table('users');

        $schema->addTable($table1);
        $schema->addTable($table2);
    }

    public function testTinyIntMin()
    {
        $this->expectException(Schema\LogicException::class);

        $type = new Schema\Type\TinyIntType(0);
    }

}