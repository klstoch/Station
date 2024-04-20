<?php

declare(strict_types=1);

namespace Station\Domain\Station\Employ\Event;

use Station\Domain\Event\AbstractEvent;
use Station\Domain\Station\Employ\AbstractEmploy;
use Station\Domain\Work\WorkInterface;

final readonly class EmployCanNotDoWorkEvent extends AbstractEvent
{
    public function __construct(
        private AbstractEmploy $employ,
        private WorkInterface $work,
        private string $reason,
    ) {
    }

    public function whatHappened(): string
    {
        return sprintf(
            '%s не может выполнить работу "%s", потому что: "%s"',
            $this->employ->getName(),
            $this->work::name(),
            $this->reason,
        );
    }
}
