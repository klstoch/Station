<?php

namespace Station\Infrastructure\Doctrine\Repository;

use Doctrine\ORM\EntityRepository;
use Station\Domain\Station\Station;
use Station\Domain\Station\StationRepositoryInterface;

class StationRepository extends EntityRepository implements StationRepositoryInterface
{
    public function save(Station $station): void
    {
        $this->getEntityManager()->persist($station);
        $this->getEntityManager()->flush();
    }

}