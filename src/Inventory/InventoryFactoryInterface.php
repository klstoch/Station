<?php

namespace Station\Inventory;

use Station\PilotStation\Station;
use Station\Queue\ClientQueue;

interface InventoryFactoryInterface
{
    public function create(Station $station): Inventory;
}