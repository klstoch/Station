<?php

namespace Station\Inventory;

use Station\Employ\EmployInterface;
use Station\Exception\ToolNotFoundException;
use Station\Tool\ToolEnum;
use Station\Tool\ToolInterface;

interface Inventory
{
    public function addNew(ToolInterface $tool);

    /**
     * @throws ToolNotFoundException
     */
    public function get(EmployInterface $employ, ToolEnum $toolName): ToolInterface;

    public function put(EmployInterface $employ, ToolInterface $tool);
}