<?php

namespace Station\Tool;

use Doctrine\ORM\EntityRepository;

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