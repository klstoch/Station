<?php

namespace Station\Infrastructure\Doctrine\Repository;

use Doctrine\ORM\EntityRepository;
use Station\Domain\Tool\AbstractTool;
use Station\Domain\Tool\ToolInterface;

/**
 * @method array<AbstractTool> findBy(array $criteria, array|null $orderBy = null, int|null $limit = null, int|null $offset = null)
 */
class ToolRepository extends EntityRepository
{
    public function save(ToolInterface $tool): void
    {
        $this->getEntityManager()->persist($tool);
        $this->getEntityManager()->flush();
    }
}