<?php

namespace Davajlama\SchemaBuilder\Schema;

use Davajlama\SchemaBuilder\Schema\TypeInterface;
use Davajlama\SchemaBuilder\Schema\Value\NullValue;
use Davajlama\SchemaBuilder\Schema\ValueInteraface;

/**
 * Description of Column
 *
 * @author David Bittner <david.bittner@seznam.cz>
 */
class Column
{
    /** @var string */
    private $name;
    
    /** @var TypeInterface */
    private $type;
    
    /** @var bool */
    private $primary = false;
    
    /** @var bool */
    private $unique = false;
    
    /** @var bool */
    private $nullable = true;
    
    /** @var bool */
    private $autoincrement = false;
    
    /** @var ValueInteraface */
    private $default;
    
    /** @var string */
    private $comment;
    
    public function __construct($name, TypeInterface $type)
    {
        $this->name = $name;
        $this->type = $type;
        $this->default = new NullValue();
    }

    public function getName()
    {
        return $this->name;
    }

    public function getType()
    {
        return $this->type;
    }
        
    public function primary($bool = true)
    {
        $this->primary = (bool)$bool;
        return $this;
    }
    
    public function unique($bool = true)
    {
        $this->unique = (bool)$bool;
        return $this;
    }
    
    public function nullable($bool = true)
    {
        $this->nullable = (bool)$bool;
        return $this;
    }
    
    public function autoincrement($bool = true)
    {
        $this->autoincrement = (bool)$bool;
        return $this;
    }

    public function isPrimary()
    {
        return $this->primary;
    }
    
    public function isUnique()
    {
        return $this->unique;
    }
    
    public function isNullable()
    {
        return $this->nullable;
    }
    
    public function isAutoincrement()
    {
        return $this->autoincrement;
    }
    
    public function getDefault()
    {
        return $this->default;
    }

    public function setDefault(ValueInteraface $default)
    {
        $this->default = $default;
        return $this;
    }
        
    public function getComment()
    {
        return $this->comment;
    }

    public function setComment($comment)
    {
        $this->comment = $comment;
        return $this;
    }

}

