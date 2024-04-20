<?php

namespace Station\Client;

use Doctrine\ORM\EntityRepository;

class ClientRepository extends EntityRepository
{
    public function save(Client $client): void
    {
        $this->getEntityManager()->persist($client);
        $this->getEntityManager()->flush();
    }
}