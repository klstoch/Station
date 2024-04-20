<?php

declare(strict_types=1);

namespace Station\Domain\Event;

interface EventInterface
{
    public function whatHappened(): string;
}
