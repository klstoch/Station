<?php

declare(strict_types=1);

namespace Station\Domain\Work;

/**
 * TODO:
 * 2.7. накачка колес азотом;
 *
 * 2.8. технологическая мойка колес;
 *
 * 2.9. проверка колес на герметичность;
 *
 * 2.10. герметизация борта шины;
 *
 * 2.11. подкачка колес;
 *
 * 2.12. подкачка колес азотом;
 *
 * 2.13. диагностика колес;
 *
 * 2.14. регулировка развал-схождение колес;
 *
 * 2.15. ремонт колес:
 *
 * – установка жгута;
 *
 * – установка камерной заплаты;
 *
 * – установка грибка;
 *
 * – установка пластыря;
 */
enum CompetenceEnum: string
{
    case tyreReplacement = 'tyreReplacement';
    case wheelBalancing = 'wheelBalancing';
    case wheelInflation = 'wheelInflation';
    case repairDamageDisc = 'repairDamageDisc';
    case repairTyre = 'repairTyre';

    public function title(): string
    {
        return match ($this) {
            self::tyreReplacement => 'замена резины',
            self::wheelBalancing => 'балансировка колес',
            self::wheelInflation => 'подкачка колес',
            self::repairDamageDisc => 'ремонт поврежденного диска',
            self::repairTyre => 'ремонт поврежденной шины',
        };
    }
}