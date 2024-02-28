<?php

declare(strict_types=1);

namespace Station\Tool;

enum ToolEnum: string
{
    case airGun = 'пневматический пистолет';
    case tireChangingMachine = 'шиномонтажный станок';
    case balancingMachine = 'балансировчный станок';
    case compressor = 'компрессор';
}
