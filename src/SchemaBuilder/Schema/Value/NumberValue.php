<?php

namespace Davajlama\SchemaBuilder\Schema\Value;

use Davajlama\SchemaBuilder\Schema\ValueInterface;

/**
 * Description of NumberValue
 *
 * @author David Bittner <david.bittner@seznam.cz>
 */
class NumberValue implements ValueInterface
{
    /** @var int|float|double */
    private $value;
    
    /**
     * @param int|float|double $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * @return int|float|double
     */
    public function getValue()
    {
        return $this->value;
    }

}
