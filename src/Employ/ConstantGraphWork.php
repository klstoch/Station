<?php

namespace Station\Employ;

readonly class ConstantGraphWork implements GraphWork
{
    public function __construct(
        private array $dayWeek = [
            1 =>['09:00','12:30','13:00','19:00'],
            2 =>['09:00','12:30','13:00','19:00'],
            3 =>['09:00','12:30','13:00','19:00'],
            4 =>['09:00','12:30','13:00','19:00'],
            5 =>['09:00','12:30','13:00','19:00'],
            6 =>['09:00','12:30','13:00','18:00'],
            7 =>['09:00','12:30','13:00','17:00'],
        ]
    ) {

    }

    public function isWorkTime(\DateTimeInterface $dateTime): bool
    {
        $dayOfWeek = $dateTime->format('N');
        [$startWorkingTime, $startLaunchTime, $endLaunchTime, $endWorkingTime] = $this->dayWeek[$dayOfWeek];
        $time = $dateTime->format('H:i');

        $isWorkingTime = $time > $startWorkingTime && $time < $endWorkingTime;
        $isLaunchTime = $time > $startLaunchTime && $time < $endLaunchTime;
        return $isWorkingTime && !$isLaunchTime;

    }
}