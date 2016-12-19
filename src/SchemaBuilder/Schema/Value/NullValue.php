<?php

namespace Davajlama\SchemaBuilder\Schema\Value;

use Davajlama\SchemaBuilder\Schema\ValueInterface;

/**
 * Description of NullValue
 *
 * @author David Bittner <david.bittner@seznam.cz>
 */
class NullValue implements ValueInterface
{
    
    /**
     * @return null
     */
    public function getValue()
    {
        return null;
    }

}
