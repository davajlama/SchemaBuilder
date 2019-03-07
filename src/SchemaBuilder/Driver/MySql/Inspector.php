<?php

namespace Davajlama\SchemaBuilder\Driver\MySql;

use Davajlama\SchemaBuilder\Adapter\AdapterInterface;

/**
 * Description of Inspector
 *
 * @author David Bittner <david.bittner@seznam.cz>
 */
class Inspector
{
    /** @var AdapterInterface */
    private $adapter;
    
    /**
     * @param AdapterInterface $adapter
     */
    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @param string $name
     * @return array
     */
    public function describeTable($name)
    {
        return $this->adapter->fetchAll("DESCRIBE $name");
    }

    /**
     * @param string $name
     * @return bool
     */
    public function existsTable($name)
    {
        return (bool)$this->adapter->fetchAll("SHOW TABLES LIKE '$name'");
    }
    
    /**
     * @param string $table
     * @return array
     */
    public function showIndexes($table)
    {
        return $this->adapter->fetchAll("SHOW INDEX FROM `$table`");
    }

    protected function getAdapter()
    {
        return $this->adapter;
    }
}
