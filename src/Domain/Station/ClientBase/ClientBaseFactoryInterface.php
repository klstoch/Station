<?php

namespace Station\Domain\Station\ClientBase;

use Station\Domain\Station\Station;

interface ClientBaseFactoryInterface
{
    public function create(Station $station): ClientBaseInterface;
}