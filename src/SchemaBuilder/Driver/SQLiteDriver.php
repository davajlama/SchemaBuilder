<?php
/**
 * Created by PhpStorm.
 * User: David
 * Date: 05.03.2019
 * Time: 17:18
 */

namespace Davajlama\SchemaBuilder\Driver;

use Davajlama\SchemaBuilder\Driver\MySqlDriver;
use Davajlama\SchemaBuilder\Driver\SQLite\Generator;
use Davajlama\SchemaBuilder\Driver\SQLite\Inspector;
use Davajlama\SchemaBuilder\Driver\SQLite\Translator;

class SQLiteDriver extends MySqlDriver
{
    /** @var Inspector */
    private $inspector;

    /** @var Generator */
    private $generator;

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
     * @return Generator
     */
    protected function getGenerator()
    {
        if($this->generator === null) {
            $this->generator = new Generator();
        }

        return $this->generator;
    }

}