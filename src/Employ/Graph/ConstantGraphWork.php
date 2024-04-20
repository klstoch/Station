<?php

namespace Station\Employ\Graph;

use Station\Employ\TimeInterval\GraphIntervals;
use Station\Employ\TimeInterval\Time;
use Station\Employ\TimeInterval\TimeInterval;

class ConstantGraphWork implements GraphWork
{
    public const TYPE = 'constant';
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

    public function toArray(): array
    {
        $translateTimeInterval = static fn (TimeInterval $timeInterval) => [
            'start' => $timeInterval->startTime()->value,
            'end' => $timeInterval->endTime()->value,
        ];

        $daysOfWeek = [];
        foreach ($this->dayWeek as $index => $dayOfWeek) {
            $daysOfWeek[$index] = [
                'work_time' => $translateTimeInterval($dayOfWeek->workingTimeInterval),
                'launch_time' => $dayOfWeek->launchTimeInterval !== null
                    ? $translateTimeInterval($dayOfWeek->launchTimeInterval)
                    : null,
            ];
        }
        return [
            'type'=> self::TYPE,
            'daysOfWeek' => $daysOfWeek,
        ];
    }
}