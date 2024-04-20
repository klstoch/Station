<?php

namespace Station\Domain\Station\Employ\Schedule\TimeInterval;

final readonly class ScheduleIntervals
{
    public function __construct(
        public TimeInterval $workingTimeInterval,
        public ?TimeInterval $launchTimeInterval = null,
    ) {
    }

    public function isWorkingTime(Time $time): bool
    {
        return $this->workingTimeInterval->isIn($time)
            && !$this->launchTimeInterval?->isIn($time);
    }
}