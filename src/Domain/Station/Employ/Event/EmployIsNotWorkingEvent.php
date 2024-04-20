<?php

declare(strict_types=1);

namespace Station\Domain\Station\Employ\Event;

use Station\Domain\Event\AbstractEvent;
use Station\Domain\Station\Employ\AbstractEmploy;

final readonly class EmployIsNotWorkingEvent extends AbstractEvent
{
    public function __construct(
        private AbstractEmploy $employ,
    ) {
    }

    public function whatHappened(): string
    {
        return sprintf('%s в данный момент не работает', $this->employ->getName());
    }
}
