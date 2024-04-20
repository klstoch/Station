<?php

namespace Station\Domain\Station\Employ\Schedule;

use Station\Domain\Station\Employ\Schedule\TimeInterval\ScheduleIntervals;
use Station\Domain\Station\Employ\Schedule\TimeInterval\Time;
use Station\Domain\Station\Employ\Schedule\TimeInterval\TimeInterval;

class SlidingSchedule implements ScheduleInterface
{
    public const TYPE = 'sliding';

    private array $daysWork = [];

    public function __construct(
        private readonly int $workingDays,
        private readonly int $holidays,
        private readonly ScheduleIntervals $workingTime = new ScheduleIntervals(
            workingTimeInterval: new TimeInterval(new Time('08:00'), new Time('20:00')),
            launchTimeInterval: new TimeInterval(new Time('12:30'), new Time('13:00')),
        ),
        private readonly \DateTimeInterface $firstWorkDay = new \DateTimeImmutable(),
    ) {
    }

    public function isWorkTime(\DateTimeInterface $dateTime): bool
    {
        if ($dateTime->format('Y-m-d') < $this->firstWorkDay->format('Y-m-d')) {
            return false;
        }

        $date = $dateTime->format('Y-m-d');
        $lastCachedDate = array_key_last($this->daysWork);
        if ($lastCachedDate === null || $date > $lastCachedDate) {
            $formattedSimulatingDate = $lastCachedDate ?? $this->firstWorkDay->format('Y-m-d');
            $simulatingDate = new \DateTime($formattedSimulatingDate);
            $number = $lastCachedDate ? $this->daysWork[$lastCachedDate] : 1;
            while ($simulatingDate <= $dateTime) {
                $formattedSimulatingDate = $simulatingDate->format('Y-m-d');
                $this->daysWork[$formattedSimulatingDate] = $number;
                if ($number < $this->workingDays) {
                    $simulatingDate->modify('+1 day');
                    $number++;
                } else {
                    $simulatingDate->modify(sprintf('+%d days', $this->holidays + 1));
                    $number = 1;
                }
            }
        }
        if (!isset($this->daysWork[$date])) {
            return false;
        }

        $time = Time::from($dateTime->format('H:i'));
        return $this->workingTime->isWorkingTime($time);

    }

    public function toArray(): array
    {
        $translateTimeInterval = static fn(TimeInterval $timeInterval) => [
            'start' => $timeInterval->startTime()->value,
            'end' => $timeInterval->endTime()->value,
        ];
        return [
            'firstWorkDay' => $this->firstWorkDay->format('Y-m-d'),
            'holidays' => $this->holidays,
            'workingDays' => $this->workingDays,
            'workingTime' => $translateTimeInterval($this->workingTime->workingTimeInterval),
            'launchTime' => $this->workingTime->launchTimeInterval !== null
                ? $translateTimeInterval($this->workingTime->launchTimeInterval)
                : null,
            'type'=>self::TYPE,
        ];

    }

    public static function fromArray(array $data): static
    {
        $launchTimeInterval = !empty($graphWorkData['launchTime']) ? new TimeInterval(
            Time::from($graphWorkData['launchTime']['start']),
            Time::from($graphWorkData['launchTime']['end']),
        ) : null;

        return new SlidingSchedule(
            workingDays: $data['workingDays'],
            holidays: $data['holidays'],
            workingTime: new ScheduleIntervals(
                workingTimeInterval: new TimeInterval(
                    Time::from($data['workingTime']['start']),
                    Time::from($data['workingTime']['end']),
                ),
                launchTimeInterval: $launchTimeInterval,
            ),
            firstWorkDay: \DateTimeImmutable::createFromFormat('Y-m-d', $data['firstWorkDay']),
        );
    }
}



