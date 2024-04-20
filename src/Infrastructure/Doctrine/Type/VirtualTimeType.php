<?php

namespace Station\Infrastructure\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Station\Time\VirtualTime;

class VirtualTimeType extends Type
{
    const NAME = 'virtual_time';

    public function getName():string
    {
        return self::NAME;
    }
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getDateTypeDeclarationSQL($column); // ?
    }
    /**
     * @throws \JsonException
     */
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?VirtualTime
    {
        if ($value === null) {
            return null;
        }

        $virtualTimeData = json_decode($value, true, flags: JSON_THROW_ON_ERROR);
        return new VirtualTime(
            startRealTime: $virtualTimeData['startRealTime'],
            startVirtualTime: \DateTimeImmutable::createFromFormat('Y-m-d', $virtualTimeData['startVirtualTime']),
            scale: $virtualTimeData['scale']
        );
    }
    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): false|string|null
    {
        if ($value === null) {
            return null;
        }

        if (!$value instanceof VirtualTime) {
            throw new \RuntimeException();
        }
        return json_encode($value->toArray());
    }

}