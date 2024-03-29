<?php

declare(strict_types=1);

namespace Station\Inventory;

use Station\Employ\EmployInterface;
use Station\Logger\LoggerInterface;
use Station\Mutex\Mutex;
use Station\Tool\ToolEnum;
use Station\Tool\ToolInterface;
use Station\ToolNotFoundException;

final readonly class RedisBasedInventory implements Inventory
{
    private const PROCESS_INVENTORY = 'RedisBasedInventory';

    public function __construct(
        private LoggerInterface $logger,
        private \Redis          $redis,
        private Mutex           $mutex,
    )
    {
    }


    public function addNew(ToolInterface $tool): void
    {
        $this->mutex->waitAndLock(self::PROCESS_INVENTORY);
        echo ' начали ';
        sleep(5);
        $this->logger->log('В инвентаре появился новый ' . $tool::name()->value);
        $tools = $this->getTools();
        $tools[$tool::name()->name][$tool->getId()] = $tool;
        $this->saveTools($tools);
        echo ' закончили ';
        $this->mutex->unlock(self::PROCESS_INVENTORY);
    }

    /**
     * @throws ToolNotFoundException
     */
    public function get(EmployInterface $employ, ToolEnum $toolName): ToolInterface
    {
        $this->mutex->unlock(self::PROCESS_INVENTORY);
        $tools = $this->getTools();
        foreach ($tools[$toolName->name] as $tool) {
            if (!$tool->isBusy()) {
                $tool->take($employ);
                $this->saveTools($tools);
                return $tool;
            }
        }
        $this->mutex->unlock(self::PROCESS_INVENTORY);
        throw new ToolNotFoundException();
    }

    public function put(EmployInterface $employ, ToolInterface $tool): void
    {
        $this->mutex->waitAndLock(self::PROCESS_INVENTORY);
        $tools = $this->getTools();
        if (isset($tools[$tool::name()->name][$tool->getId()])) {
            $tool->put($employ);
            $this->saveTools($tools);
        }
        $this->mutex->unlock(self::PROCESS_INVENTORY);
    }

    private function getTools(): array
    {
        $serializedTools = $this->redis->get('inventory');
        return $serializedTools ? unserialize($serializedTools, ['allowed_classes' => true]) : [];
    }

    private function saveTools(array $tools): void
    {
        $serializedTools = serialize($tools);
        $this->redis->set('inventory', $serializedTools);
    }
}
