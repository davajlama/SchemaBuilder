<?php

namespace Davajlama\SchemaBuilder\Schema;

/**
 * Description of Value
 *
 * @author David Bittner <david.bittner@seznam.cz>
 */
class Value
{

    /**
     * @param string $value
     * @return \Davajlama\SchemaBuilder\Schema\Value\StringValue
     */
    public static function stringValue($value)
    {
        return new Value\StringValue($value);
    }
    
    /**
     * @param int|float|double $value
     * @return \Davajlama\SchemaBuilder\Schema\Value\NumberValue
     */
    public static function numberValue($value)
    {
        return new Value\NumberValue($value);
    }

    /**
     * @param string $value
     * @return \Davajlama\SchemaBuilder\Schema\Value\ExpressionValue
     */
    public static function expressionValue($value)
    {
        return new Value\ExpressionValue($value);
    }
    
    /**
     * @return \Davajlama\SchemaBuilder\Schema\Value\NullValue
     */
    public static function nullValue()
    {
        return new Value\NullValue();
    }
}
