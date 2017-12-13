<?php

namespace Davajlama\SchemaBuilder\Schema;

use Davajlama\SchemaBuilder\Schema\Type\DateTimeType;
use Davajlama\SchemaBuilder\Schema\Type\IntegerType;
use Davajlama\SchemaBuilder\Schema\Type\LongTextType;
use Davajlama\SchemaBuilder\Schema\Type\TextType;
use Davajlama\SchemaBuilder\Schema\Type\TinyIntType;
use Davajlama\SchemaBuilder\Schema\Type\VarcharType;

/**
 * Description of Type
 *
 * @author David Bittner <david.bittner@seznam.cz>
 */
class Type
{
    /**
     * @param int $length
     * @return VarcharType
     */
    public static function varcharType($length)
    {
        return new VarcharType($length);
    }

    /**
     * @return TextType
     */
    public static function textType()
    {
        return new TextType();
    }

    /**
     * @return LongTextType
     */
    public function longTextType()
    {
        return new LongTextType();
    }

    /**
     * @return IntegerType
     */
    public static function integerType()
    {
        return new IntegerType();
    }
    
    /**
     * @return DateTimeType
     */
    public static function dateTimeType()
    {
        return new DateTimeType();
    }

    /**
     * @param int $length
     * @return TinyIntType
     */
    public static function tinyIntType($length = 4)
    {
        return new TinyIntType($length);
    }
    
}
