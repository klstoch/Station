<?php

namespace Station\Domain\Station\ClientBase;

use Station\Domain\Client\ClientRepositoryInterface;
use Station\Domain\Station\Station;

final readonly class ClientBaseFactory implements ClientBaseFactoryInterface
{
    public function __construct(
        private ClientRepositoryInterface $clientRepository,
    ) {
    }

    public function create(Station $station): ClientBaseInterface
    {
        return new ClientBase($this->clientRepository, $station);
    }
}