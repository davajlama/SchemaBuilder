<?php

namespace Davajlama\SchemaBuilder\Test\Fixture;

/**
 * Description of NullAdapter
 *
 * @author David Bittner <david.bittner@seznam.cz>
 */
class NullAdapter implements \Davajlama\SchemaBuilder\Adapter\AdapterInterface
{
    
    public function fetchAll($sql)
    {
        
    }

    public function query($sql)
    {
        
    }
    
}
