<?php

namespace Station\Infrastructure\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Station\Vehicle\BodyEnum;
use Station\Vehicle\Car;
use Station\Vehicle\DamageEnum;
use Station\Vehicle\DiscMaterialEnum;
use Station\Vehicle\DiscWheel;
use Station\Vehicle\RadiusEnum;
use Station\Vehicle\Tyre;
use Station\Vehicle\Wheel;

class CarType extends Type
{
    const NAME = 'car';

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
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): Car
    {
        $carData = json_decode($value, true, flags: JSON_THROW_ON_ERROR);
        return new Car(
            wheel: new Wheel(
                discWheel: new DiscWheel(
                    damage: DamageEnum::from($carData['wheel']['disc_wheel']['damage_enum']),
                    discMaterial: DiscMaterialEnum::from($carData['wheel']['disc_wheel']['disc_material_enum']),
                    radius: RadiusEnum::from($carData['wheel']['disc_wheel']['radius_enum'])
                ),
                tyre: new Tyre(
                    isRun_flat: $carData['wheel']['tyre']['is_run_flat'],
                    radius: RadiusEnum::from($carData['wheel']['tyre']['radius_enum']),
                )
            ),
            body: BodyEnum::from($carData['body_enum']));
    }

    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): false|string|null
    {
        if ($value === null) {
            return null;
        }
        if ($value instanceof Car) {
            throw new \RuntimeException();
        }
        return json_encode($value->toArray());
    }
}