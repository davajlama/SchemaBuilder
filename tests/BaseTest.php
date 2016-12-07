<?php

/**
 * Description of BaseTestCase
 *
 * @author David Bittner <david.bittner@seznam.cz>
 */
class BaseTest extends PHPUnit_Framework_TestCase
{

    public function testBase()
    {
        $adapter    = new FakeAdapter();
        $driver     = new Davajlama\SchemaBuilder\Driver\MySqlDriver($adapter);
        $builder    = new Davajlama\SchemaBuilder\SchemaBuilder($driver);

        $this->assertEquals(true, true);
        $this->assertEquals(false, true);
    }

}

class FakeAdapter implements \Davajlama\SchemaBuilder\Adapter\AdapterInterface
{
    public function fetchAll($sql)
    {

    }

    public function query($sql)
    {

    }

}
