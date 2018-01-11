<?php
/**
 * Created by PhpStorm.
 * User: David
 * Date: 11.01.2018
 * Time: 13:16
 */

namespace Davajlama\SchemaBuilder\Schema\Type;

use Davajlama\SchemaBuilder\Schema\TypeInterface;

class BinaryType implements TypeInterface
{

    /** @var int */
    private $length;

    /**
     * BinaryType constructor.
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