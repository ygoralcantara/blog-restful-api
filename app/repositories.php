<?php
declare(strict_types=1);

use App\Domain\Post\PostRepository;
use App\Domain\User\UserRepository;
use App\Infrastructure\Persistence\Doctrine\DoctrineUserRepository;
use App\Infrastructure\Persistence\PDO\PDOPostRepository;
use App\Infrastructure\Persistence\PDO\PDOUserRepository;
use DI\ContainerBuilder;

return function (ContainerBuilder $containerBuilder) {
    // Here we map our UserRepository interface to its in memory implementation
    $containerBuilder->addDefinitions([
        //UserRepository::class => \DI\autowire(DoctrineUserRepository::class),
        UserRepository::class => \DI\autowire(PDOUserRepository::class),
        PostRepository::class => \DI\autowire(PDOPostRepository::class),
    ]);
};
