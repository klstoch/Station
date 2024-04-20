<?php

declare(strict_types=1);

namespace Station\Domain;

use Station\Domain\Time\Duration;

final readonly class Action
{
    public function __construct(
        public string $title,
        public Duration $duration,
    ) {
    }
}
