<?php

namespace Station\Domain\Station\ClientQueue;

use Station\Domain\Station\Station;

interface ClientQueueFactoryInterface
{
    public function create(Station $station): ClientQueueInterface;
}