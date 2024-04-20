<?php

declare(strict_types=1);

namespace Station\Domain\Work;

use Station\Domain\Action;
use Station\Domain\Time\Duration;
use Station\Domain\Tool\AirGun;
use Station\Domain\Tool\TyreChangingMachine;
use Station\Domain\Tool\ToolEnum;


final class TyreReplacement extends AbstractWork
{
    public static function name(): string
    {
        return 'Замена резины';
    }

    public function requiredTools(): array
    {
        return [
            ToolEnum::airGun,
            ToolEnum::tireChangingMachine,
        ];
    }

    public function requiredCompetences(): array
    {
        return [CompetenceEnum::tyreReplacement];
    }

    protected function doExecute(): void
    {
        for ($i = 0; $i < 4; $i++) {
            $this->replaceTyre();
        }
    }

    private function replaceTyre(): void
    {
        $airGun = $this->employ->selectTool(ToolEnum::airGun);
        assert($airGun instanceof AirGun);

        $tireChangingMachine = $this->employ->selectTool(ToolEnum::tireChangingMachine);
        assert($tireChangingMachine instanceof TyreChangingMachine);

        $spinLeft = $airGun->spinLeft();
        for ($i = 0; $i < 5; $i++) {
            $this->doAction($spinLeft);
        }

        $this->doAction(new Action('Относим колесо к станку', Duration::m(1)));

        $this->doAction($tireChangingMachine->putOff());
        $this->doAction($tireChangingMachine->putOn());

        $this->doAction(new Action('Относим колесо к автомобилю', Duration::m(1)));

        $spinRight = $airGun->spinRight();
        for ($i = 0; $i < 5; $i++) {
            $this->doAction($spinRight);
        }
    }
}
