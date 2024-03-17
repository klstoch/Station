<?php

namespace Station\Employ;

class ConstantGraphWork implements GraphWork
{
    public function __construct(
        /** @var null|array<GraphIntervals> $dayWeek */
        private ?array $dayWeek = null
    ) {
        if ($this->dayWeek === null) {
            for ($i = 1; $i <= 7; $i++) {
                $this->dayWeek[$i] = new GraphIntervals(
                    workingTimeInterval: new TimeInterval(Time::from('09:00'), Time::from('20:00')),
                    launchTimeInterval: new TimeInterval(Time::from('12:30'), Time::from('13:00')),
                );
            }
        }
    }

    public function isWorkTime(\DateTimeInterface $dateTime): bool
    {
        $dayOfWeek = $dateTime->format('N');
        $graphIntervals = $this->dayWeek[$dayOfWeek];

        $time = Time::from($dateTime->format('H:i'));

        return $graphIntervals->isWorkingTime($time);

    }
}