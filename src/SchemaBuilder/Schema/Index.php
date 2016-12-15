<?php

namespace Davajlama\SchemaBuilder\Schema;

/**
 * Description of Index
 *
 * @author David Bittner <david.bittner@seznam.cz>
 */
class Index
{
    /** @var IndexColumn[] */
    private $columns;
    
    /** @var bool */
    private $unique;
    
    /** @var string */
    private $name;
    
    /**
     * @param bool $unique
     */
    public function __construct($unique = false)
    {
        $this->unique = (bool)$unique;
    }
    
    public function addColumn($name, $asc = true)
    {
        $this->columns[] = new IndexColumn($name, $asc);
        return $this;
    }
    
    /**
     * @return IndexColumn[]
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * @return bool
     */
    public function isUnique()
    {
        return $this->unique;
    }

    /**
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

}
