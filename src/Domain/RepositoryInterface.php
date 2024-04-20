<?php

declare(strict_types=1);

namespace Station\Domain;

interface RepositoryInterface
{
    public function save(AbstractEntity $entity): void;
}
