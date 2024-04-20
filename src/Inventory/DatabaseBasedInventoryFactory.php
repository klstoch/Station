<?php

namespace Station\Inventory;

use Station\Infrastructure\Cache\Redis;
use Station\Mutex\Mutex;
use Station\PilotStation\Station;
use Station\Tool\ToolRepository;

class DatabaseBasedInventoryFactory implements InventoryFactoryInterface
{
    public function __construct(
        private readonly Redis $redis,
        private readonly ToolRepository $toolRepository,
    ) {
    }

    public function create(Station $station): Inventory
    {
        $mutex = new Mutex(redis: $this->redis, owner: 'Inventory');
        return new DatabaseBasedInventory($station, $this->toolRepository, $mutex);
    }
}