<?php

namespace Station\Domain\Station\Inventory;

use Station\Domain\Station\Station;

interface InventoryFactoryInterface
{
    public function create(Station $station): InventoryInterface;
}