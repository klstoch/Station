<?php

declare(strict_types=1);

namespace Station\Domain\Work;

use Station\Domain\Event\EventDispatcherInterface;
use Station\Domain\Station\Employ\EmployInterface;
use Station\Domain\Tool\ToolEnum;

interface WorkInterface
{
    public static function name(): string;

    public function execute(EmployInterface $employ, EventDispatcherInterface $eventDispatcher): void;

    /**
     * @return array<ToolEnum>
     */
    public function requiredTools(): array;

    public function requiredCompetences():array;
}
