<?php

namespace Station\Infrastructure\Database\Inventory;

use Station\Domain\Station\Inventory\InventoryFactoryInterface;
use Station\Infrastructure\Cache\Redis\Redis;
use Station\Infrastructure\Mutex\Mutex;
use Station\Domain\Station\Station;
use Station\Infrastructure\Doctrine\Repository\ToolRepository;

class InventoryFactory implements InventoryFactoryInterface
{
    public function __construct(
        private readonly Redis $redis,
        private readonly ToolRepository $toolRepository,
    ) {
    }

    public function create(Station $station): Inventory
    {
        $mutex = new Mutex(redis: $this->redis, owner: 'Inventory');
        return new Inventory($station, $this->toolRepository, $mutex);
    }
}