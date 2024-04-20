<?php

namespace Station\Domain\Station\Employ\Schedule;

use Station\Domain\Station\Employ\Schedule\TimeInterval\ScheduleIntervals;
use Station\Domain\Station\Employ\Schedule\TimeInterval\Time;
use Station\Domain\Station\Employ\Schedule\TimeInterval\TimeInterval;

class ConstantSchedule implements ScheduleInterface
{
    public const TYPE = 'constant';

    public function __construct(
        /** @var null|array<ScheduleIntervals> $dayWeek */
        private ?array $dayWeek = null
    ) {
        if ($this->dayWeek === null) {
            for ($i = 1; $i <= 7; $i++) {
                $this->dayWeek[$i] = new ScheduleIntervals(
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

    public static function fromArray(array $data): static
    {
        $daysOfWeek = [];
        foreach ($data['daysOfWeek'] as $index => $dayOfWeekData) {
            $launchTimeInterval = !empty($dayOfWeekData['launch_time']) ? new TimeInterval(
                Time::from($dayOfWeekData['launch_time']['start']),
                Time::from($dayOfWeekData['launch_time']['end']),
            ) : null;

            $daysOfWeek[$index] = new ScheduleIntervals(
                workingTimeInterval: new TimeInterval(
                    Time::from($dayOfWeekData['work_time']['start']),
                    Time::from($dayOfWeekData['work_time']['end']),
                ),
                launchTimeInterval: $launchTimeInterval,
            );
        }
        return new ConstantSchedule($daysOfWeek);
    }
}