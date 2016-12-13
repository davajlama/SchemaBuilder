<?php

namespace Davajlama\SchemaBuilder\Driver;

use Davajlama\SchemaBuilder\Adapter\AdapterInterface;
use Davajlama\SchemaBuilder\Driver\MySql\Generator;
use Davajlama\SchemaBuilder\Driver\MySql\Inspector;
use Davajlama\SchemaBuilder\DriverInterface;
use Davajlama\SchemaBuilder\Patch;
use Davajlama\SchemaBuilder\PatchList;
use Davajlama\SchemaBuilder\Schema;
use Davajlama\SchemaBuilder\Schema\Table;

/**
 * Description of MySqlDriver
 *
 * @author David Bittner <david.bittner@seznam.cz>
 */
class MySqlDriver implements DriverInterface
{
    /** @var AdapterInterface */
    private $adapter;
    
    /** @var Generator */
    private $generator;
    
    /** @var Inspector */
    private $inspector;

    /**
     * @param AdapterInterface $adapter
     */
    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }
    
    /**
     * @param Schema $schema
     * @return PatchList
     */
    public function buildSchemaPatches(Schema $schema)
    {
        $patches = new PatchList();
        foreach($schema->getTables() as $table) {
            $patches->addPatches($this->buildTablePatches($table));
        }
        
        return $patches;
    }

    /**
     * @param Table $table
     * @return PatchList
     */
    public function buildTablePatches(Table $table)
    {
        $patches = null;
        if($this->getInspector()->existsTable($table->getName())) {
            $patches = $this->getGenerator()->alterTablePatches($table);
        } else {
            $patches = $this->getGenerator()->createTablePatches($table);
        }
        
        return $patches;
    }

    /**
     * @param Patch $patch
     */
    public function applyPatch(Patch $patch)
    {
        $this->getAdapter()->query($patch->getQuery());
    }
    
    /**
     * @return Generator
     */
    protected function getGenerator()
    {
        if($this->generator === null) {
            $this->generator = new Generator($this->getInspector());
        }
        
        return $this->generator;
    }
    
    /**
     * @return Inspector
     */
    protected function getInspector()
    {
        if($this->inspector === null) {
            $this->inspector = new Inspector($this->getAdapter());
        }
        
        return $this->inspector;
    }
    
    /**
     * @return AdapterInterface
     */
    protected function getAdapter()
    {
        return $this->adapter;
    }
    
}
