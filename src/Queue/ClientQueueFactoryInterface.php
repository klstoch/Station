<?php

namespace Station\Queue;

use Station\PilotStation\Station;

interface ClientQueueFactoryInterface
{
    public function create(Station $station): ClientQueue;
}