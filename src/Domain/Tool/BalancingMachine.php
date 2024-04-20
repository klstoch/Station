<?php

namespace Station\Domain\Tool;

use Doctrine\ORM\Mapping\Entity;
use Station\Domain\Action;
use Station\Domain\Time\Duration;

#[Entity]
final class BalancingMachine extends AbstractTool
{
    public static function name(): ToolEnum
    {
        return ToolEnum::balancingMachine;
    }

    public function setUpBalance(): Action
    {
        return new Action('Устанавливаем колесо на балансир', Duration::s(30));
    }

    public function balance(): Action
    {
        return new Action('Балансируем колесо', Duration::m(2));
    }

    public function pulOffBalance(): Action
    {
        return new Action('Снимаем колесо с балансира', Duration::s(30));
    }
}