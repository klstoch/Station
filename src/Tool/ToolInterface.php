<?php

declare(strict_types=1);

namespace Station\Tool;

use Station\Employ\EmployInterface;

interface ToolInterface
{
    public static function name(): ToolEnum;

    public function isBusy(): bool;

    public function take(EmployInterface $employ): void;

    public function put(EmployInterface $employ): void;
}
