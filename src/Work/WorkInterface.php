<?php

declare(strict_types=1);

namespace Station\Work;

use Station\Employ\EmployInterface;
use Station\Tool\ToolEnum;

interface WorkInterface
{
    public static function name(): WorkEnum;

    public function execute(EmployInterface $employ): void;

    /**
     * @return array<ToolEnum>
     */
    public function requiredTools(): array;
}
