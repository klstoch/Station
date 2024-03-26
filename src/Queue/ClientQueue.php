<?php

namespace Station\Queue;


use Station\Client\Client;

interface ClientQueue
{
    public function add(Client $client): void;

    public function get(): Client|null;

    public function delete(Client $client):void;

    public function isEmptyClientQueue():bool;

    public function count(): int;

}