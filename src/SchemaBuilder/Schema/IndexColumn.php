<?php

namespace Davajlama\SchemaBuilder\Schema;

/**
 * Description of IndexColumn
 *
 * @author David Bittner <david.bittner@seznam.cz>
 */
class IndexColumn
{
    /** @var string */
    private $name;
    
    /** @var bool */
    private $asc;
    
    public function __construct($name, $asc = true)
    {
        $this->name = $name;
        $this->asc = (bool)$asc;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * @return bool
     */
    public function isASC()
    {
        return $this->asc;
    }
    
}
