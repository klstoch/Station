<?php

declare(strict_types=1);

namespace Station\Domain\Event;

abstract readonly class AbstractEvent implements EventInterface, \Stringable
{
    public function __toString(): string
    {
        return $this->whatHappened();
    }
}
