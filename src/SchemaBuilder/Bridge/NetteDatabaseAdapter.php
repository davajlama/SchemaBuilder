<?php

namespace Davajlama\SchemaBuilder\Bridge;

use Davajlama\SchemaBuilder\Adapter\AdapterInterface;
use Davajlama\SchemaBuilder\Bridge\PDOAdapter;
use Nette\Database\Connection;

/**
 * Description of NetteDatabaseAdapter
 *
 * @author David Bittner <david.bittner@seznam.cz>
 */
class NetteDatabaseAdapter implements AdapterInterface
{
    /** @var PDOAdapter */
    private $adapter;
    
    public function __construct(Connection $connection)
    {
        $this->adapter = new PDOAdapter($connection->getPdo());
    }
    
    public function query($sql)
    {
        return $this->adapter->query($sql);
    }

    public function fetchAll($sql)
    {
        return $this->adapter->fetchAll($sql);
    }
    
}
