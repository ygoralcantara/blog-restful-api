<?php
declare(strict_types=1);

use DI\ContainerBuilder;
use Monolog\Logger;

return function (ContainerBuilder $containerBuilder) {
    // Global Settings Object
    $containerBuilder->addDefinitions([
        'settings' => [
            'displayErrorDetails' => true, // Should be set to false in production
            'logger' => [
                'name' => 'slim-app',
                'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
                'level' => Logger::DEBUG,
            ],
            'database' => [
                'driver'    => 'pdo_pgsql',
                'host'      => 'localhost',
                'dbname'    => 'blog_php',
                'user'      => 'root',
                'password'  => 'admin',
                'port'      => '5432',
            ],
            'phinx' => [
                'migration' => __DIR__ . '/../db/migrations',
                'seed'      => __DIR__ . '/../db/seeds',
                'driver'    => 'pgsql',
            ],
            'doctrine' => [
                'entity_path' => __DIR__ . '/../src/Infrastructure/Persistence/Doctrine/Mapping',
            ],
        ],
    ]);
};
