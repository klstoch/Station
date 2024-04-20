<?php

declare(strict_types=1);

namespace Station\Tool;

use Station\Employ\EmployInterface;
use Station\PilotStation\Station;


interface ToolInterface
{
    public static function name(): ToolEnum;

    public function isBusy(): bool;

    public function take(EmployInterface $employee): void;

    public function put(EmployInterface $employee): void;

    public function getId(): string;

    public function assignToStation(Station $station): void;
}
