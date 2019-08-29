<?php
declare(strict_types=1);

use Monolog\Logger;
use DI\ContainerBuilder;
use Psr\Log\LoggerInterface;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Monolog\Handler\StreamHandler;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Slim\Exception\HttpInternalServerErrorException;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        
        //Monolog
        LoggerInterface::class => function (ContainerInterface $c) {
            $settings = $c->get('settings');

            $loggerSettings = $settings['logger'];
            $logger = new Logger($loggerSettings['name']);

            $processor = new UidProcessor();
            $logger->pushProcessor($processor);

            $handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
            $logger->pushHandler($handler);

            return $logger;
        },

        //Doctrine 2
        EntityManagerInterface::class => function (ContainerInterface $c) {
            $settings = $c->get('settings');

            $settings['database']['driver'] = $settings['doctrine']['driver'];

            $config = Setup::createXMLMetadataConfiguration([
                $settings['doctrine']['entity_path'],
            ], true);

            return EntityManager::create($settings['database'], $config);
        },

        //PDO
        'pdo-conn' => function (ContainerInterface $c) {
            $database = $c->get('settings')['database'];

            $dsn = $database['driver'] . ":host=" . $database['host'] . ";port=" . $database['port'] . ";dbname=" . $database['dbname'];

            try {
                $conn = new PDO($dsn, $database['user'], $database['password'], array(
                    'PDO::ATTR_ERRMODE'      => 'PDO::ERRMODE_EXCEPTION',
                    'PDO::ATTR_ORACLE_NULLS' => 'PDO::NULL_EMPTY_STRING'
                ));

            } catch (PDOException $e) {
                throw new InvalidArgumentException($e->getMessage(), 500);
            }

            return $conn;
        },
        
    ]);
};

/*
try {

                self::$instance = new PDO(self::$dsn, DB_USER, DB_PASSWORD, array(
                    'PDO::ATTR_ERRMODE' => 'PDO::ERRMODE_EXCEPTION',
                    'PDO::ATTR_ORACLE_NULLS' => 'PDO::NULL_EMPTY_STRING'
                ));

            } catch (PDOException $e) {
                $e->getMessage();
            }

        }

        return self::$instance;
*/
