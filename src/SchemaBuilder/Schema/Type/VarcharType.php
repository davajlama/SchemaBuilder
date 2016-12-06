<?php

namespace Davajlama\SchemaBuilder\Schema\Type;

use Davajlama\SchemaBuilder\Schema\TypeInterface;

/**
 * Description of VarcharType
 *
 * @author David Bittner <david.bittner@seznam.cz>
 */
class VarcharType implements TypeInterface
{
    /** @var int */
    private $length;
    
    /**
     * @param int $length
     */
    public function __construct($length)
    {
        $this->length = $length;
    }

    /**
     * @return int
     */
    public function getLength()
    {
        return $this->length;
    }

}
