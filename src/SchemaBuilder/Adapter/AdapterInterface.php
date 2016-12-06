<?php

namespace Davajlama\SchemaBuilder\Adapter;

/**
 * Description of AdapterInterface
 *
 * @author David Bittner <david.bittner@seznam.cz>
 */
interface AdapterInterface
{
    
    public function query($sql);
    public function fetchAll($sql);
}
