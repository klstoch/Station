<?php

declare(strict_types=1);

namespace Station\Domain\Client;

use Station\Domain\RepositoryInterface;
use Station\Domain\Station\Station;

interface ClientRepositoryInterface extends RepositoryInterface
{
    /**
     * @return array<Client>
     */
    public function getAllByStation(Station $station): array;
}
