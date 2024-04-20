<?php

declare(strict_types=1);

namespace Station\Domain\Station\Employ;

use Doctrine\ORM\Mapping\Entity;
use Station\Domain\Work\CompetenceEnum;

#[Entity]
final class TyreMechanic extends AbstractEmploy
{
    protected array $competences = [
        CompetenceEnum::tyreReplacement,
        CompetenceEnum::wheelBalancing,
        CompetenceEnum::wheelInflation
    ];

}
