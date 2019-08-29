<?php

use DI\ContainerBuilder;

//Instiante PHP-DI Container Builder 
$containerBuilder = new ContainerBuilder();

//Set up settings
$settings = require __DIR__ . '/app/settings.php';
$settings($containerBuilder);

//Build PHP-DI Container instance
$container = $containerBuilder->build();

$database = $container->get('settings')['database'];
$phinx = $container->get('settings')['phinx'];

return [
    'paths'        => [
        'migrations' => $phinx['migration'],
        'seeds'      => $phinx['seed'],
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_database'        => 'development',
        'development'             => [
            'adapter' => $database['driver'],
            'host'    => $database['host'],
            'name'    => $database['dbname'],
            'user'    => $database['user'],
            'pass'    => $database['password'],
            'port'    => $database['port'],
            'charset' => 'utf8',
        ],
    ],
];

?>