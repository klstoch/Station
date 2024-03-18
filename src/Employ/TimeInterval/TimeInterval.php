<?php

namespace Station\Employ\TimeInterval;

readonly class TimeInterval
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
}