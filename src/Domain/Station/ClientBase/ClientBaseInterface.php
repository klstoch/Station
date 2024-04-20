<?php

namespace Station\Domain\Station\ClientBase;

use Station\Domain\Client\Client;

interface ClientBaseInterface
{
    public function add(Client $client): void;

    public function get(): ?Client;

}