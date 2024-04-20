<?php

namespace Station\Domain\Station\Employ\Schedule\TimeInterval;

final readonly class TimeInterval
{
    public function __construct(
        private Time $start,
        private Time $end
    ) {
    }

    public function isIn(Time $time): bool
    {
        return $this->start->value <= $time->value && $this->end->value > $time->value;
    }

    public function startTime(): Time
    {
        return $this->start;
    }

    public function endTime(): Time
    {
        return $this->end;
    }
}