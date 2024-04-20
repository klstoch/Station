<?php

declare(strict_types=1);

namespace Station\Domain\Tool;

enum ToolEnum: string
{
    case airGun = 'airGun';
    case tireChangingMachine = 'tireChangingMachine';
    case balancingMachine = 'balancingMachine';
    case compressor = 'compressor';

    /**
     * @return string
     */
    public function title(): string
    {
        return match ($this) {
            self::airGun => 'пневматический пистолет',
            self::tireChangingMachine => 'шиномонтажный станок',
            self::balancingMachine => 'балансировочный станок',
            self::compressor => 'компрессор',
        };
    }
}
