<?php

require_once __DIR__ . '/../vendor/autoload.php';

if(file_exists(__DIR__ . '/env.php')) {
    require_once __DIR__ . '/env.php';
}

$loader = new Composer\Autoload\ClassLoader();
$loader->addPsr4('Davajlama\\SchemaBuilder\\Test\\', [__DIR__]);
$loader->register();