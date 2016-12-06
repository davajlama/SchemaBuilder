<?php

namespace Davajlama\SchemaBuilder\Schema\Value;

use Davajlama\SchemaBuilder\Schema\ValueInteraface;

/**
 * Description of NullValue
 *
 * @author David Bittner <david.bittner@seznam.cz>
 */
class NullValue implements ValueInteraface
{
    
    /**
     * @return null
     */
    public function getValue()
    {
        return null;
    }

}
