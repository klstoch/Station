<?php

namespace Station\Infrastructure\Doctrine;

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Station\Infrastructure\Exception\InfrastructureException;

class EntityManagerFactory
{
    private static ?EntityManager $entityManager = null;

    public function __construct(
        private readonly array $doctrineConfig,
        private readonly array $connectionConfig,
    ) {
    }

    public function create(): EntityManager
    {
        if (self::$entityManager === null) {
            $config = ORMSetup::createAttributeMetadataConfiguration(
                $this->doctrineConfig['paths'],
                $this->doctrineConfig['is_dev_mode'],
            );

            foreach ($this->doctrineConfig['types'] as $name => $class) {
                try {
                    Type::addType($name, $class);
                } catch (Exception $e) {
                    throw new InfrastructureException(sprintf('Can`t add doctrine type %s', $name), previous: $e);
                }
            }

            $connection = DriverManager::getConnection($this->connectionConfig, $config);

            self::$entityManager = new EntityManager($connection, $config);
        }
        return self::$entityManager;
    }
}
