<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Doctrine\Migrations\Configuration\EntityManager\ExistingEntityManager;
use Doctrine\Migrations\Configuration\Migration\PhpFile;
use Doctrine\Migrations\DependencyFactory;
use Station\Infrastructure\Doctrine\EntityManagerFactory;

$migrationsConfig = new PhpFile(__DIR__ . '/migrations.php');

$entityManagerFactory = new EntityManagerFactory(
    require dirname(__DIR__) . '/config/doctrine.php',
    require dirname(__DIR__) . '/config/db.php',
);
$entityManager = $entityManagerFactory->create();

return DependencyFactory::fromEntityManager($migrationsConfig, new ExistingEntityManager($entityManager));