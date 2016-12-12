<?php

namespace Davajlama\SchemaBuilder\Driver\MySql;

use Davajlama\SchemaBuilder\Schema\Column;
use Davajlama\SchemaBuilder\Schema\Type\IntegerType;
use Davajlama\SchemaBuilder\Schema\Type\TextType;
use Davajlama\SchemaBuilder\Schema\Type\VarcharType;
use Davajlama\SchemaBuilder\Schema\TypeInterface;
use Davajlama\SchemaBuilder\Schema\Value\ExpressionValue;
use Davajlama\SchemaBuilder\Schema\Value\NullValue;
use Davajlama\SchemaBuilder\Schema\Value\NumberValue;
use Davajlama\SchemaBuilder\Schema\Value\StringValue;
use Davajlama\SchemaBuilder\Schema\ValueInteraface;
use Exception;

/**
 * Description of Translator
 *
 * @author David Bittner <david.bittner@seznam.cz>
 */
class Translator
{
    
    /**
     * @param string $table
     * @return string
     */
    public function transAlterTableHeader($table)
    {
        return "ALTER TABLE `$table`";
    }

    /**
     * @param string $table
     * @param string $column
     * @return string
     */
    public function transDropColumn($table, $column)
    {
        $header = $this->transAlterTableHeader($table);
        return "$header DROP COLUMN `$column`;";
    }
    
    /**
     * @param string $table
     * @return string
     */
    public function transDropTable($table)
    {
        return "DROP TABLE `$table`;";
    }
    
    /**
     * @param string $column
     * @return string
     */
    public function transUniqueKey($column)
    {
        return "UNIQUE KEY `{$column}_UNIQUE` (`$column`)";
    }
    
    /**
     * @param string[] $columns
     * @return string
     */
    public function transPrimaryKey(Array $columns)
    {
        return 'PRIMARY KEY (`' . implode('`, `', $columns) . '`)';
    }
    
    /**
     * @param string $table
     * @return string
     */
    public function transCreateTableHeader($table)
    {
        return "CREATE TABLE `$table` (";
    }

    /**
     * @param string $engine
     * @param string $charset
     * @return string
     */
    public function transCreateTableFooter($engine, $charset)
    {
        return ") ENGINE=$engine DEFAULT CHARSET=$charset;";
    }
    
    /**
     * @param TypeInterface $type
     * @return string
     * @throws Exception
     */
    public function transType(TypeInterface $type)
    {
        switch($class = get_class($type)) {
            case IntegerType::class : 
                return 'int(11)';
            case VarcharType::class : 
                return "VARCHAR({$type->getLength()})";
            case TextType::class :
                return 'TEXT()';
            default:
                throw new Exception("Unknown column type [$class]");
        }
    }
    
    /**
     * @param ValueInteraface $value
     * @return string
     * @throws Exception
     */
    public function transDefaultValue(ValueInteraface $value)
    {
        switch($class = get_class($value)) {
            case StringValue::class : 
                $expr = "'{$value->getValue()}'"; break;
            case NumberValue::class :
                $expr = $value->getValue(); break;
            case ExpressionValue::class :
                $expr = $value->getValue(); break;
            case NullValue::class :
                $expr = 'NULL'; break;
            default:
                throw new Exception("Unknown column default value [$class]");
        }
        
        return "DEFAULT $expr";
    }
    
    /**
     * @param Column $column
     * @return string
     */
    public function transColumn(Column $column)
    {
        $parts = [];
        $parts[] = "`{$column->getName()}`";
        $parts[] = $this->transType($column->getType());
        $parts[] = $column->isPrimary() || !$column->isNullable() ? 'NOT NULL' : null;
        
        if(!$column->isPrimary() && !($column->getDefault() instanceof NullValue && !$column->isNullable())) {
            $parts[] = $this->transDefaultValue($column->getDefault());
        }
        
        $parts[] = $column->isAutoincrement() ? 'AUTO_INCREMENT' : null;

        return implode(' ', array_filter($parts));
    }
    
}