<?php

declare(strict_types=1);

namespace Station\Domain\Station\Employ\Event;

use Station\Domain\Event\AbstractEvent;
use Station\Domain\Station\Employ\AbstractEmploy;

final readonly class EmployActionStartEvent extends AbstractEvent
{
    public function __construct(
        private AbstractEmploy $employ,
        private string $action,
    ) {
    }

    public function whatHappened(): string
    {
        return sprintf('%s выполняет "%s"', $this->employ->getName(), $this->action);
    }
}
