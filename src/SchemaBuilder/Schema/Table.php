<?php

namespace Davajlama\SchemaBuilder\Schema;

use Davajlama\SchemaBuilder\Schema\Value\ExpressionValue;

/**
 * Description of Table
 *
 * @author David Bittner <david.bittner@seznam.cz>
 */
class Table
{
    /** @var string */
    private $name;
    
    /** @var string */
    private $engine = 'InnoDB';
    
    /** @var string */
    private $charset = 'utf8';
    
    /** @var Column[] */
    private $columns = [];
    
    /** @var Index[] */
    private $indexes = [];
    
    /**
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * @return string
     */
    public function getEngine()
    {
        return $this->engine;
    }

    /**
     * @return string
     */
    public function getCharset()
    {
        return $this->charset;
    }

    /**
     * @return Column[]
     */
    public function getColumns()
    {
        return $this->columns;
    }
        
    /**
     * @param Column $column
     * @return self
     */
    public function addColumn(Column $column)
    {
        $this->columns[$column->getName()] = $column;
        return $this;
    }
    
    /**
     * @param string $name
     * @return Column|null
     */
    public function getColumn($name)
    {
        return array_key_exists($name, $this->columns) ? $this->columns[$name] : null;
    }
    
    /**
     * @param string $name
     * @param TypeInterface $type
     * @return Column
     */
    public function createColumn($name, TypeInterface $type)
    {
        $this->addColumn($column = new Column($name, $type));
        return $column;
    }

    /**
     * @param string $name
     * @return Column
     */
    public function createId($name = 'id')
    {
        return $this->createColumn($name, new Type\IntegerType())
                    ->primary()
                    ->autoincrement();
    }

    /**
     * @param string $name
     * @return Column
     */
    public function createDateTimeColumn(string $name)
    {
        return $this->createColumn($name, Type::dateTimeType())
                    ->nullable(false)
                    ->setDefaultValue(new ExpressionValue('current_timestamp()'));

    }

    /**
     * @param string $name
     * @param int $length
     * @return Column
     */
    public function createVarcharColumn(string $name, int $length = 255)
    {
        return $this->createColumn($name, Type::varcharType($length));
    }

    /**
     * @param string $name
     * @return Column
     */
    public function createIntegerColumn(string $name)
    {
        return $this->createColumn($name, Type::integerType());
    }

    /**
     * @param string $name
     * @return Column
     */
    public function createTinyIntColumn(string $name)
    {
        return $this->createColumn($name, Type::tinyIntType());
    }

    /**
     * @param string $name
     * @return Column
     */
    public function createTextColumn(string $name)
    {
        return $this->createColumn($name, Type::textType());
    }

    /**
     * @param Index $index
     * @return self
     */
    public function addIndex(Index $index)
    {
        $this->indexes[] = $index;
        return $this;
    }

    /**
     * @param array $columns
     * @param bool $asc
     * @return Index
     */
    public function createIndex(array $columns = [], bool $asc = true)
    {
        $this->addIndex($index = new Index(false));
        return $index->addColumns($columns, $asc);
    }
    
    /**
     * @param string[] $columns
     * @return Index
     */
    public function createUniqueIndex(array $columns = [], bool $asc = true)
    {
        $this->addIndex($index = new Index(true));
        return $index->addColumns($columns, $asc);
    }
    
    /**
     * @return Index[]
     */
    public function getIndexes()
    {
        return $this->indexes;
    }
    
}
