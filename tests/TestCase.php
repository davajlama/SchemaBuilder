<?php

namespace Davajlama\SchemaBuilder\Test;

/**
 * Description of TestCase
 *
 * @author David Bittner <david.bittner@seznam.cz>
 */
class TestCase extends \PHPUnit\Framework\TestCase
{
    
    public function multipleWith(Array $withs)
    {
        $list = [];
        foreach($withs as $with) {
            $list[] = $this->equalTo($with);
        }
        
        return $this->logicalOr(...$list);
    }
    
    public function multipleReturn()
    {
        return new MultipleReturn($this);
    }
    
}

class MultipleReturn
{
    
    private $list = [];
    
    /** @var \PHPUnit\Framework\TestCase */
    private $test;
    
    public function __construct(\PHPUnit\Framework\TestCase $test)
    {
        $this->test = $test;
    }
    
    public function ret($input, $return)
    {
        $this->list[$input] = $return;
        return $this;
    }
    
    public function toCallback()
    {
        return $this->test->returnCallback($this);
    }
    
    public function __invoke($input)
    {
        if(array_key_exists($input, $this->list)) {
            return $this->list[$input];
        }
        
        throw new \Exception("Unexpected input!");
    }
    
}
