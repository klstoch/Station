<?php

declare(strict_types=1);

namespace Station\Employ;

use Station\Work\CompetenceEnum;

final class TyreMechanic extends AbstractEmploy
{
    protected array $competences = [
        CompetenceEnum::tyreReplacement,
        CompetenceEnum::wheelBalancing,
        CompetenceEnum::wheelInflation
    ];

}
