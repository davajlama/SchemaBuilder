<?php

namespace Davajlama\SchemaBuilder;

use Davajlama\SchemaBuilder\Schema\LogicException;
use Davajlama\SchemaBuilder\Schema\Table;

/**
 * Description of Schema
 *
 * @author David Bittner <david.bittner@seznam.cz>
 */
class Schema
{
    /** @var Table[] */
    private $tables = [];
    
    /**
     * @param Table $table
     * @return self
     */
    public function addTable(Table $table)
    {
        if(array_key_exists($table->getName(), $this->tables)) {
            throw new LogicException("Table with name [{$table->getName()}] already exits");
        }

        $this->tables[$table->getName()] = $table;
        return $this;
    }
    
    /**
     * @param string $name
     * @return Table
     */
    public function createTable($name)
    {
        $this->addTable($table = new Table($name));
        return $table;
    }
    
    /**
     * @return Table[]
     */
    public function getTables()
    {
        return $this->tables;
    }
    
}
