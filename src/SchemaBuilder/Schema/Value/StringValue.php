<?php

namespace Davajlama\SchemaBuilder\Schema\Value;

use Davajlama\SchemaBuilder\Schema\ValueInterface;

/**
 * Description of StringValue
 *
 * @author David Bittner <david.bittner@seznam.cz>
 */
class StringValue implements ValueInterface
{
    /** @var string */
    private $value;

    /**
     * @param string $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }
    
}
