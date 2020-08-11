<?php
/**
 * Created by PhpStorm.
 * User: David
 * Date: 30.09.2019
 * Time: 18:10
 */

namespace Davajlama\SchemaBuilder\Bridge;


use Davajlama\SchemaBuilder\Adapter\AdapterInterface;
use Dibi\Connection;

class DibiAdapter implements AdapterInterface
{
    /** @var Connection */
    private $connection;

    /**
     * DibiAdapter constructor.
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function query($sql)
    {
        $this->connection->nativeQuery($sql);
    }

    public function fetchAll($sql)
    {
        return $this->connection->nativeQuery($sql)
                ->fetchAll();
    }

}