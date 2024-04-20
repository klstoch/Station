<?php

declare(strict_types=1);

namespace Station\Domain\Station\Employ\Event;

use Station\Domain\Event\AbstractEvent;

final readonly class EmployActionFinishEvent extends AbstractEvent
{
    public function __construct(
        private string $whatHappened,
    ) {
    }

    public function whatHappened(): string
    {
        return $this->whatHappened;
    }
}
