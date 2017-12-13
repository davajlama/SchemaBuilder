<?php

namespace Davajlama\SchemaBuilder\Schema\Type;

use Davajlama\SchemaBuilder\Schema\TypeInterface;

/**
 * Description of TinyInt
 *
 * @author David Bittner <david.bittner@seznam.cz>
 */
class TinyIntType implements TypeInterface
{
    /** @var int */
    private $length;

    /**
     * TinyIntType constructor.
     * @param int $length
     */
    public function __construct($length = 4)
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
