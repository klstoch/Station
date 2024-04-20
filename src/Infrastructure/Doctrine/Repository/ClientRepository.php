<?php

namespace Station\Infrastructure\Doctrine\Repository;

use Doctrine\ORM\EntityRepository;
use Station\Domain\Client\Client;

class ClientRepository extends EntityRepository
{
    public function save(Client $client): void
    {
        $this->getEntityManager()->persist($client);
        $this->getEntityManager()->flush();
    }
}