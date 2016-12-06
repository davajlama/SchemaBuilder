<?php

namespace Davajlama\SchemaBuilder;

/**
 * Description of SchemaCreator
 *
 * @author David Bittner <david.bittner@seznam.cz>
 */
class SchemaCreator
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
     * @param PatchList $patches
     */
    public function applyPatches(PatchList $patches)
    {
        foreach($patches->toArray() as $patch) {
            $this->getDriver()->applyPatch($patch);
        }
    }
    
    /**
     * @return DriverInterface
     */
    protected function getDriver()
    {
        return $this->driver;
    }
}
