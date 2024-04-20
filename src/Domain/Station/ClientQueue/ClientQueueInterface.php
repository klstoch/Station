<?php

namespace Station\Domain\Station\ClientQueue;

use Station\Domain\Client\Client;

interface ClientQueueInterface
{
    public function add(Client $client): void;

    public function get(): Client|null;

    public function delete(Client $client):void;

    public function isEmpty():bool;

    public function count(): int;

}