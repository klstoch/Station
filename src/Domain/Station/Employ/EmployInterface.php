<?php

declare(strict_types=1);

namespace Station\Domain\Station\Employ;

use Station\Domain\Action;
use Station\Domain\Event\EventDispatcherInterface;
use Station\Domain\Station\Inventory\InventoryInterface;
use Station\Domain\Tool\ToolEnum;
use Station\Domain\Tool\ToolInterface;
use Station\Domain\Work\CompetenceEnum;
use Station\Domain\Work\WorkInterface;

interface EmployInterface
{
    public function getId(): string;

    public function getName(): string;

    public function canExecute(
        WorkInterface $work,
        InventoryInterface $inventory,
        EventDispatcherInterface $eventDispatcher,
    ): bool;

    /**
     * @throws CanNotBeExecutedException
     */
    public function doWork(
        WorkInterface $work,
        InventoryInterface $inventory,
        EventDispatcherInterface $eventDispatcher,
    ): void;

    public function doAction(Action $action, EventDispatcherInterface $eventDispatcher): void;

    public function selectTool(ToolEnum $toolName): ToolInterface;

    public function isBusy(): bool;

    public function isWorkTime(\DateTimeInterface $dateTime): bool;

    public function doBreak(EventDispatcherInterface $eventDispatcher): void;

    public function addAdditionalCompetence(CompetenceEnum $competence): void;
}
