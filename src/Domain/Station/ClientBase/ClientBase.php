<?php

namespace Station\Domain\Station\ClientBase;

use Station\Domain\Client\Client;
use Station\Domain\Client\ClientRepositoryInterface;
use Station\Domain\Station\Station;

final readonly class  ClientBase implements ClientBaseInterface
{
    public function __construct(
        private ClientRepositoryInterface $clientRepository,
        private Station $station,
    ) {
    }

    public function add(Client $client): void
    {
        $client->assignToStation($this->station);

        $this->clientRepository->save($client);
    }

    public function get(): ?Client
    {
        $clients = $this->clientRepository->getAllByStation($this->station);
        if (empty($clients)) {
            return null;
        }

        $randomClient = $clients[random_int(0, count($clients) - 1)];
        if (!$randomClient instanceof Client) {
            return null;
        }

        return $randomClient;
    }
}