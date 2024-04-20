<?php

declare(strict_types=1);

namespace Station\Domain\Tool;

use Station\Domain\Event\EventDispatcherInterface;
use Station\Domain\Station\Employ\EmployInterface;
use Station\Domain\Station\Station;


interface ToolInterface
{
    public function getId(): string;

    public static function name(): ToolEnum;

    public function isBusy(): bool;

    public function take(EmployInterface $employee, EventDispatcherInterface $eventDispatcher): void;

    public function put(EmployInterface $employee, EventDispatcherInterface $eventDispatcher): void;

    public function assignToStation(Station $station): void;
}
