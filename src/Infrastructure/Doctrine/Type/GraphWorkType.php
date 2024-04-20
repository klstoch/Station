<?php

namespace Station\Infrastructure\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Station\Employ\Graph\ConstantGraphWork;
use Station\Employ\Graph\GraphWork;
use Station\Employ\Graph\SlidingGraphWork;
use Station\Employ\TimeInterval\GraphIntervals;
use Station\Employ\TimeInterval\Time;
use Station\Employ\TimeInterval\TimeInterval;

class GraphWorkType extends Type
{
    public const NAME = 'graph_work';

    public function getName(): string
    {
        return self::NAME;
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getJsonTypeDeclarationSQL($column);
    }

    /**
     * @throws \JsonException
     */
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ConstantGraphWork|SlidingGraphWork|null
    {
        if ($value === null) {
            return null;
        }

        $graphWorkData = json_decode($value, true, flags: JSON_THROW_ON_ERROR);

        return match ($graphWorkData['type']) {
            ConstantGraphWork::TYPE => $this->convertToPHPConstant($graphWorkData),
            SlidingGraphWork::TYPE => $this->convertToPHPSliding($graphWorkData),
            default => throw new \RuntimeException(sprintf('Received graph work type %s is not supported!', $graphWorkData['type'])), // или другое исключение выбросить?
        };
    }

    public function convertToPHPSliding(array $graphWorkData): SlidingGraphWork
    {
        $launchTimeInterval = !empty($graphWorkData['launchTime']) ? new TimeInterval(
            Time::from($graphWorkData['launchTime']['start']),
            Time::from($graphWorkData['launchTime']['end']),
        ) : null;

        return new SlidingGraphWork(
            workingDays: $graphWorkData['workingDays'],
            holidays: $graphWorkData['holidays'],
            workingTime: new GraphIntervals(
                workingTimeInterval: new TimeInterval(
                    Time::from($graphWorkData['workingTime']['start']),
                    Time::from($graphWorkData['workingTime']['end']),
                ),
                launchTimeInterval: $launchTimeInterval,
            ),
            firstWorkDay: \DateTimeImmutable::createFromFormat('Y-m-d', $graphWorkData['firstWorkDay']),
        );
    }

    public function convertToPHPConstant(array $graphWorkData): ConstantGraphWork
    {
        $daysOfWeek = [];
        foreach ($graphWorkData['daysOfWeek'] as $index => $dayOfWeekData) {
            $launchTimeInterval = !empty($dayOfWeekData['launch_time']) ? new TimeInterval(
                Time::from($dayOfWeekData['launch_time']['start']),
                Time::from($dayOfWeekData['launch_time']['end']),
            ) : null;

            $daysOfWeek[$index] = new GraphIntervals(
                workingTimeInterval: new TimeInterval(
                    Time::from($dayOfWeekData['work_time']['start']),
                    Time::from($dayOfWeekData['work_time']['end']),
                ),
                launchTimeInterval: $launchTimeInterval,
            );
        }
        return new ConstantGraphWork($daysOfWeek);
    }


    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): false|string|null
    {
        if ($value === null) {
            return null;
        }

        if (!$value instanceof GraphWork) {
            throw new \RuntimeException();
        }

        return json_encode($value->toArray());
    }
}