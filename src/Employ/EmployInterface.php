<?php

declare(strict_types=1);

namespace Station\Employ;

use Station\Time\VirtualTime;
use Station\Tool\ToolEnum;
use Station\Tool\ToolInterface;
use Station\Work\WorkInterface;

interface EmployInterface
{
    public function getName(): string;

    public function canExecute(WorkInterface $work): bool;

    public function do(WorkInterface $work): void;
    public function selectTool(ToolEnum $toolName): ToolInterface;

    public function isBusy(): bool;

    public function isWorkTime(\DateTimeInterface $dateTime): bool;

    public function doBreak(): void;

    public function getId(): string;

    public function getJobContract(): JobContract;
}
