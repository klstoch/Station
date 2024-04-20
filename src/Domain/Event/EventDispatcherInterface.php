<?php

declare(strict_types=1);

namespace Station\Domain\Event;

interface EventDispatcherInterface
{
    public function dispatch(EventInterface $event): void;
}
