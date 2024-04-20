<?php

namespace Station\Domain\Station\Inventory;

use Station\Domain\Station\Employ\EmployInterface;
use Station\Domain\Tool\ToolEnum;
use Station\Domain\Tool\ToolInterface;

interface InventoryInterface
{
    public function addNew(ToolInterface $tool);

    /**
     * @throws ToolNotFoundException
     */
    public function get(EmployInterface $employ, ToolEnum $toolName): ToolInterface;

    public function put(EmployInterface $employ, ToolInterface $tool);
}