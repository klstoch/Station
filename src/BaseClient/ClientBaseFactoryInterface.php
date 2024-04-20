<?php

namespace Station\BaseClient;

use Station\PilotStation\Station;

interface ClientBaseFactoryInterface
{
    public function create(Station $station): ClientBase;

}