<?php

declare(strict_types=1);

namespace Station\Domain\Tool;

use Doctrine\ORM\Mapping\Entity;
use Station\Domain\Action;
use Station\Domain\Time\Duration;

#[Entity]
final class TyreChangingMachine extends AbstractTool
{
    public static function name(): ToolEnum
    {
        return ToolEnum::tireChangingMachine;
    }

    public function putOff(): Action
    {
        return new Action('Снимаем покрышку с колеса', Duration::m(2));
    }

    public function putOn(): Action
    {
        return new Action('Одеваем покрышку на колесо', Duration::m(2));
    }
}
