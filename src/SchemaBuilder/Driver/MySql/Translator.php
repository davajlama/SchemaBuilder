<?php

namespace Davajlama\SchemaBuilder\Driver\MySql;

/**
 * Description of Translator
 *
 * @author David Bittner <david.bittner@seznam.cz>
 */
class Translator
{
    
    public function getColumn(\Davajlama\SchemaBuilder\Schema\Column $column)
    {
        $parts = [];
        $parts[] = "`{$column->getName()}`";
        $parts[] = $this->getType($column->getType());
        $parts[] = $column->isNullable() && !$column->isPrimary() ? $this->getDefault($column->getDefault()) : 'NOT NULL';
        $parts[] = $column->isAutoincrement() ? 'AUTO_INCREMENT' : null;

        return implode(' ', array_filter($parts));
    }
    
    public function getDefault(\Davajlama\SchemaBuilder\Schema\ValueInteraface $value)
    {
        switch($class = get_class($value)) {
            case \Davajlama\SchemaBuilder\Schema\Value\StringValue::class : 
                $value = "'{$value->getValue()}'";
                break;
            case \Davajlama\SchemaBuilder\Schema\Value\NumberValue::class :
                $value = $value->getValue();
                break;
            case \Davajlama\SchemaBuilder\Schema\Value\ExpressionValue::class :
                $value = $value->getValue();
                break;
            case \Davajlama\SchemaBuilder\Schema\Value\NullValue::class :
                $value = 'NULL';
                break;
            
            default:
                throw new \Exception("Unknown column default value [$class]");
        }
        
        return "DEFAULT $value";
    }
    
    public function getType(\Davajlama\SchemaBuilder\Schema\TypeInterface $type)
    {
        switch($class = get_class($type)) {
            case \Davajlama\SchemaBuilder\Schema\Type\IntegerType::class : 
                return 'int(11)';
            case \Davajlama\SchemaBuilder\Schema\Type\VarcharType::class : 
                return "VARCHAR({$type->getLength()})";
            default:
                throw new \Exception("Unknown column type [$class]");
        }
    }
    
}