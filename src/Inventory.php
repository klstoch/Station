<?php

declare(strict_types=1);

namespace Station;

use Station\Employ\EmployInterface;
use Station\Logger\LoggerInterface;
use Station\Tool\ToolEnum;
use Station\Tool\ToolInterface;

final class Inventory
{
    /** @var $tools array<string, array<ToolInterface>> */
    private array $tools = [];

    public function __construct(
        private readonly LoggerInterface $logger,
        array $tools = []
    ) {
        foreach ($tools as $tool) {
            $this->addNew($tool);
        }
    }

    /**
     * @throws ToolNotFoundException
     */
    public function get(EmployInterface $employ, ToolEnum $toolName): ToolInterface
    {
        foreach ($this->tools[$toolName->name] as $tool) {
            if (!$tool->isBusy()) {
                $tool->take($employ);
                return $tool;
            }
        }
        throw new ToolNotFoundException();
    }

    public function put(EmployInterface $employ, ToolInterface $tool): void
    {
        $tool->put($employ);
    }

    public function addNew(ToolInterface $tool): void
    {
        $this->logger->log('В инвентаре появился новый ' . $tool::name()->value);
        $this->tools[$tool::name()->name][] = $tool;
    }
}
