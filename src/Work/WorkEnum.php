<?php

declare(strict_types=1);

namespace Station\Work;

enum WorkEnum: string
{
    case tireReplacement = 'замена резины';
    //case wheelInflation = 'подкачка колес';
    case wheelBalancing = 'балансировка колес';
    case wheelReplacementBalancing = 'замена резины и балансировка колес';
    //case repairDamageDisc = 'ремонт поврежденного диска';
}
