<?php

namespace Station\Infrastructure\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Station\Domain\Work\CompetenceEnum;

final class CompetenceListType extends Type
{
    public const NAME = 'competences';

    public function getName(): string
    {
        return self::NAME;
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getStringTypeDeclarationSQL($column);
    }

    /**
     * @return array<CompetenceEnum>|null
     */
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?array
    {
        if ($value === null) {
            return null;
        }

        $competences = [];
        foreach (explode(',', $value) as $competenceAsString) {
            $competences[] = CompetenceEnum::from((int) $competenceAsString);
        }

        return $competences;
    }

    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): ?string
    {
        if ($value === null) {
            return null;
        }

        $competences = $value;
        if (!is_array($competences)) {
            throw new \RuntimeException('$competences must be array of Competence');
        }

        return implode(',', array_map(static fn(CompetenceEnum $enum) => $enum->name, $competences));
    }
}