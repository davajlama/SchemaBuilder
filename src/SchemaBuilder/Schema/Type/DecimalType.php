<?php
/**
 * Created by PhpStorm.
 * User: David
 * Date: 30.04.2018
 * Time: 13:23
 */

namespace Davajlama\SchemaBuilder\Schema\Type;


use Davajlama\SchemaBuilder\Schema\TypeInterface;

class DecimalType implements TypeInterface
{
    /** @var int */
    private $maxDigits;

    /** @var int */
    private $digits;

    /**
     * DecimalType constructor.
     * @param int $maxDigits
     * @param int $digits
     */
    public function __construct($maxDigits, $digits = 0)
    {
        $this->maxDigits    = $maxDigits;
        $this->digits       = $digits;
    }

    /**
     * @return int
     */
    public function getMaxDigits()
    {
        return $this->maxDigits;
    }

    /**
     * @return int
     */
    public function getDigits()
    {
        return $this->digits;
    }

}