<?php

namespace Davajlama\SchemaBuilder\Schema;

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
        $this->columns[] = $column;
        return $this;
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

}

