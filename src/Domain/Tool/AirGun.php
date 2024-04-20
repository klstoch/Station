<?php

declare(strict_types=1);

namespace Station\Domain\Tool;

use Doctrine\ORM\Mapping\Entity;
use Station\Domain\Action;
use Station\Domain\Time\Duration;

#[Entity]
final class AirGun extends AbstractTool
{
    public static function name(): ToolEnum
    {
        return ToolEnum::airGun;
    }

    public function spinLeft(): Action
    {
        return new Action('Откручиваем гайку', Duration::s(5));
    }

    public function spinRight(): Action
    {
        return new Action('Закручиваем гайку', Duration::s(5));
    }
}
