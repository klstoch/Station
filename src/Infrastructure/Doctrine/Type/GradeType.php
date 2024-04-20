<?php

namespace Station\Infrastructure\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Station\Employ\Grade;


class GradeType extends Type
{
    public const NAME = 'grade';

    public function getName():string
    {
        return self::NAME;
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getStringTypeDeclarationSQL($column);
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?Grade
    {
        if ($value === null){
            return null;
        }
        return Grade::from($value);
    }
    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): ?string
    {
        if($value === null){
            return null;
        }

       if(!$value instanceof Grade){
           throw new \RuntimeException();
       }
       return $value->value;
    }
}