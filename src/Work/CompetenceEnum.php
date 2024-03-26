<?php

declare(strict_types=1);

namespace Station\Work;

enum CompetenceEnum: string
{
    case tyreReplacement = 'замена резины';
    case wheelBalancing = 'балансировка колес';
    case wheelInflation = 'подкачка колес';
    case repairDamageDisc = 'ремонт поврежденного диска';
    case repairTyre = 'ремонт поврежденной шины';

    //case wheelReplacementBalancing = 'замена резины и балансировка колес'; // удалить
}
 /*

2.7. накачка колес азотом;

2.8. технологическая мойка колес;

2.9. проверка колес на герметичность;

2.10. герметизация борта шины;

2.11. подкачка колес;

2.12. подкачка колес азотом;

2.13. диагностика колес;

2.14. регулировка развал-схождение колес;

2.15. ремонт колес:

– установка жгута;

– установка камерной заплаты;

– установка грибка;

– установка пластыря;
  */