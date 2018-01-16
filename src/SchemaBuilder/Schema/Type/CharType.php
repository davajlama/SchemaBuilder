<?php
/**
 * Created by PhpStorm.
 * User: David
 * Date: 16.01.2018
 * Time: 17:34
 */

namespace Davajlama\SchemaBuilder\Schema\Type;


use Davajlama\SchemaBuilder\Schema\TypeInterface;

class CharType implements TypeInterface
{

    /** @var int */
    private $length;

    /**
     * CharType constructor.
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