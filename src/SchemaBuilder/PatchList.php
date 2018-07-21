<?php

namespace Davajlama\SchemaBuilder;

use ArrayIterator;
use Countable;
use IteratorAggregate;

/**
 * Description of PatchList
 *
 * @author David Bittner <david.bittner@seznam.cz>
 */
class PatchList implements IteratorAggregate, Countable
{
    /** @var Patch[] */
    private $list = [];
    
    /**
     * @param Patch $patch
     * @return self
     */
    public function addPatch(Patch $patch)
    {
        $this->list[] = $patch;
        return $this;
    }

    /**
     * @param string $query
     * @param int $level
     * @return Patch
     */
    public function createPatch($query, $level)
    {
        $this->addPatch($patch = new Patch($query, $level));
        return $patch;
    }
    
    /**
     * @param PatchList $patches
     * @return self
     */
    public function addPatches(PatchList $patches)
    {
        foreach($patches as $patch) {
            $this->addPatch($patch);
        }
        
        return $this;
    }
    
    /**
     * @return Patch|null
     */
    public function first()
    {
        return reset($this->list);
    }
    
    /**
     * @return Patch|null
     */
    public function next()
    {
        return next($this->list);
    }
    
    /**
     * @return Patch[]
     */
    public function toArray()
    {
        return $this->list;
    }
    
    /**
     * @return ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->toArray());
    }
    
    /**
     * @return int
     */
    public function count()
    {
        return count($this->list);
    }

    /**
     * @param callable $callback
     * @return PatchList
     */
    public function filter(callable $callback)
    {
        $list = new self();
        foreach($this->list as $patch) {
            if($callback($patch)) {
                $list->addPatch($patch);
            }
        }

        return $list;
    }

}
