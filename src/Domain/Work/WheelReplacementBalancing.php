<?php

namespace Station\Domain\Work;

use Station\Domain\Action;
use Station\Domain\Time\Duration;
use Station\Domain\Tool\AirGun;
use Station\Domain\Tool\BalancingMachine;
use Station\Domain\Tool\ToolEnum;
use Station\Domain\Tool\TyreChangingMachine;


final class WheelReplacementBalancing extends AbstractWork
{
    public static function name(): string
    {
        return 'Замена резины и балансировка колес';
    }

    public function requiredTools(): array
    {
        return [
            ToolEnum::airGun,
            ToolEnum::balancingMachine,
            ToolEnum::tireChangingMachine,
        ];
    }

    public function requiredCompetences(): array
    {
        return [
            CompetenceEnum::tyreReplacement,
            CompetenceEnum::wheelBalancing,
        ];
    }

    protected function doExecute(): void
    {
        for ($i = 0; $i < 4; $i++) {
            $this->balanceWheel();
        }
    }


    private function balanceWheel(): void
    {
        $airGun = $this->employ->selectTool(ToolEnum::airGun);
        assert($airGun instanceof AirGun);

        $tireChangingMachine = $this->employ->selectTool(ToolEnum::tireChangingMachine);
        assert($tireChangingMachine instanceof TyreChangingMachine);

        $balancingMachine = $this->employ->selectTool(ToolEnum::balancingMachine);
        assert($balancingMachine instanceof BalancingMachine);

        for ($i = 0; $i < 5; $i++) {
            $this->doAction($airGun->spinLeft());
        }

        $this->doAction(new Action('Относим колесо к станку', Duration::m(1)));

        $this->doAction($tireChangingMachine->putOff());
        $this->doAction($tireChangingMachine->putOn());

        $this->doAction(new Action('Относим колесо к балансировочному станку', Duration::m(1)));

        $this->doAction($balancingMachine->setUpBalance());
        $this->doAction($balancingMachine->balance());
        $this->doAction($balancingMachine->pulOffBalance());

        $this->doAction(new Action('Относим колесо к автомобилю', Duration::m(1)));

        for ($i = 0; $i < 5; $i++) {
            $this->doAction($airGun->spinRight());
        }
    }
}