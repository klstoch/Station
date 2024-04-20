<?php

declare(strict_types=1);

namespace Station\Domain\Tool\Event;

use Station\Domain\Event\AbstractEvent;
use Station\Domain\Station\Employ\EmployInterface;
use Station\Domain\Tool\ToolInterface;

final readonly class ToolWasBusyEvent extends AbstractEvent
{
    public function __construct(
        private ToolInterface $tool,
        private EmployInterface $employ,
    ) {
    }

    public function whatHappened(): string
    {
        return sprintf('%s взял из инвентаря %s', $this->employ->getName(), $this->tool::name()->title());
    }
}
