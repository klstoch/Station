<?php

namespace Station\Infrastructure\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Station\Domain\Station\Employ\Schedule\ConstantSchedule;
use Station\Domain\Station\Employ\Schedule\ScheduleInterface;
use Station\Domain\Station\Employ\Schedule\SlidingSchedule;

final class ScheduleType extends Type
{
    public const NAME = 'schedule';

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
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ConstantSchedule|SlidingSchedule|null
    {
        if ($value === null) {
            return null;
        }

        $scheduleData = json_decode($value, true, 512, JSON_THROW_ON_ERROR);

        return match ($scheduleData['type']) {
            ConstantSchedule::TYPE => ConstantSchedule::fromArray($scheduleData),
            SlidingSchedule::TYPE => SlidingSchedule::fromArray($scheduleData),
            default => throw new \RuntimeException(sprintf('Received graph work type %s is not supported!', $scheduleData['type'])),
        };
    }

    /**
     * @throws \JsonException
     */
    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): false|string|null
    {
        if ($value === null) {
            return null;
        }

        if (!$value instanceof ScheduleInterface) {
            throw new \RuntimeException('$value must be of type Schedule');
        }

        return json_encode($value->toArray(), JSON_THROW_ON_ERROR);
    }
}