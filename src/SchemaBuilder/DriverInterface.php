<?php

namespace Davajlama\SchemaBuilder;

use Davajlama\SchemaBuilder\Schema\Table;

/**
 * Description of DriverInterface
 *
 * @author David Bittner <david.bittner@seznam.cz>
 */
interface DriverInterface
{

    /**
     * @param Schema $schema
     * @return PatchList
     */
    public function buildSchemaPatches(Schema $schema);

    /**
     * @param Table $table
     * @return PatchList
     */
    public function buildTablePatches(Table $table);
    
    
    /**
     * @param Patch $patch
     */
    public function applyPatch(Patch $patch);
}
