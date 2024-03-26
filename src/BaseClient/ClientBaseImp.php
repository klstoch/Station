<?php

namespace Station\BaseClient;

use Station\Client\Client;

readonly class  ClientBaseImp implements ClientBase
{
    public function __construct(
        private ClientBaseRepository $clientBaseRepository,
    )
    {

    }

    public function add(Client $client): void
    {
        $this->clientBaseRepository->save($client);
    }

    public function get(): Client|null
    {

        $clientBase = $this->clientBaseRepository->getAll();
        return $clientBase[random_int(0, count($clientBase) - 1)];

    }
}