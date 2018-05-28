<?php
/**
 * Created by PhpStorm.
 * User: David
 * Date: 28.05.2018
 * Time: 17:23
 */

namespace Davajlama\SchemaBuilder\Schema\Type;


use Davajlama\SchemaBuilder\Schema\TypeInterface;

class BigIntType implements TypeInterface
{
    /** @var int */
    private $length;

    /**
     * BigIntType constructor.
     * @param int $length
     */
    public function __construct($length = 11)
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