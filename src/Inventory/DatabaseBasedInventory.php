<?php

namespace Station\Inventory;

use Station\Employ\EmployInterface;
use Station\Exception\ToolNotFoundException;
use Station\Mutex\Mutex;
use Station\PilotStation\Station;
use Station\Tool\ToolEnum;
use Station\Tool\ToolInterface;
use Station\Tool\ToolRepository;

class DatabaseBasedInventory implements Inventory
{
    private const TOOL_LOCK_PREFIX = 'inventory_tool_';
    private const STATION_LOCK_PREFIX = 'inventory_station_';

    public function __construct(
        private readonly Station $station,
        private readonly ToolRepository $toolRepository,
        private readonly Mutex $mutex,
    ) {
    }

    public function addNew(ToolInterface $tool): void
    {
        $lockName = $this->getToolLockName($tool);
        $this->mutex->waitAndLock($lockName);

        $tool->assignToStation($this->station);
        $this->toolRepository->save($tool);

        $this->mutex->unlock($lockName);
    }

    public function get(EmployInterface $employ, ToolEnum $toolName): ToolInterface
    {
        $lockName = $this->getStationLockName();
        $this->mutex->waitAndLock($lockName);
        try {
            $tools = $this->toolRepository->findBy([
                'station' => $this->station,
                'name' => $toolName->value,
            ]);

            foreach ($tools as $tool) {
                if (!$tool->isBusy()) {
                    $tool->take($employ);
                    $this->toolRepository->save($tool);
                    return $tool;
                }
            }
        } finally {
            $this->mutex->unlock($lockName);
        }
        throw new ToolNotFoundException();
    }

    public function put(EmployInterface $employ, ToolInterface $tool): void
    {
        $lockName = $this->getStationLockName();
        $this->mutex->waitAndLock($lockName);

        $tool->put($employ);
        $this->toolRepository->save($tool);

        $this->mutex->unlock($lockName);
    }

    private function getToolLockName(ToolInterface $tool): string
    {
        return self::TOOL_LOCK_PREFIX . $tool->getId();
    }

    private function getStationLockName(): string
    {
        return self::STATION_LOCK_PREFIX . $this->station->getId();
    }
}