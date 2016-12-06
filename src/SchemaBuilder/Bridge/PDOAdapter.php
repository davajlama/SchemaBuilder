<?php

namespace Davajlama\SchemaBuilder\Bridge;

use Davajlama\SchemaBuilder\Adapter\AdapterInterface;

/**
 * Description of PDOAdapter
 *
 * @author David Bittner <david.bittner@seznam.cz>
 */
class PDOAdapter implements AdapterInterface
{
    /** @var \PDO */
    private $connection;
    
    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    public function query($sql)
    {
        return $this->connection->query($sql);
    }
    
    public function fetchAll($sql)
    {
        return $this->connection->query($sql)->fetchAll();
    }
    
}
