<?php

namespace Station\BaseClient;

use Station\BaseClient\ClientBaseFactoryInterface;
use Station\PilotStation\Station;

class ClientBasedFactory implements ClientBaseFactoryInterface
{

    public function create(Station $station): ClientBase
    {
        // TODO: Implement create() method.
    }
}