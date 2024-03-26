<?php

namespace Station\BaseClient;

use Station\Client\Client;

interface ClientBase
{
    public function add(Client $client): void;

    public function get(): Client|null;

}