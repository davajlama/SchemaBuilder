<?php

namespace Davajlama\SchemaBuilder;

use Davajlama\SchemaBuilder\DriverInterface;
use Davajlama\SchemaBuilder\Schema\Table;

/**
 * Description of SchemaBuilder
 *
 * @author David Bittner <david.bittner@seznam.cz>
 */
class SchemaBuilder
{
    
    /** @var DriverInterface */
    private $driver;
    
    /**
     * @param DriverInterface $driver
     */
    public function __construct(DriverInterface $driver)
    {
        $this->driver = $driver;
    }

    /**
     * @param SchemaatchList
     */
    public function buildSchemaPatches(Schema $schema)
    {
        return $this->driver->buildSchemaPatches($schema);
    }

    /**
     * @param Table $table
     * @return PatchList
     */
    public function buildTablePatches(Table $table)
    {
        return $this->driver->buildTablePatches($table);
    }
    
}
