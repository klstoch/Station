<?php

namespace Station\PilotStation;

use Doctrine\ORM\EntityRepository;

class StationRepository extends EntityRepository
{
    public function save(Station $station): void
    {
        $this->getEntityManager()->persist($station);
        $this->getEntityManager()->flush();
    }

}