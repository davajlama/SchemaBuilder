<?php

namespace Davajlama\SchemaBuilder\Test;

use Davajlama\SchemaBuilder\Adapter\AdapterInterface;
use Davajlama\SchemaBuilder\Bridge\PDOAdapter;
use PDO;

/**
 * Description of MySqlAdapterProviderTrait
 *
 * @author David Bittner <david.bittner@seznam.cz>
 */
trait MySqlAdapterProviderTrait
{
    /** @var AdapterInterface */
    private $adapter;
    
    /**
     * @return PDOAdapter
     */
    protected function getAdapter()
    {
        $host       = getenv('TESTHOST');
        $username   = getenv('TESTUSER');
        $schema     = getenv('TESTDB');

        if($this->adapter === null && $host && $username && $schema) {
            $dsn = "mysql:host=$host;dbname=$schema";
            
            $options = array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
            ); 

            $pdo = new PDO($dsn, $username, null, $options);
            $this->adapter = new PDOAdapter($pdo);
        }        
        
        return $this->adapter;
    }
    
}
