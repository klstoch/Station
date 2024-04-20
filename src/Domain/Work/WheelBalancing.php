<?php

namespace Station\Domain\Work;

use Station\Domain\Action;
use Station\Domain\Time\Duration;
use Station\Domain\Tool\AirGun;
use Station\Domain\Tool\BalancingMachine;
use Station\Domain\Tool\ToolEnum;

final class WheelBalancing extends AbstractWork
{
    public static function name(): string
    {
        return 'Балансировка колес';
    }

    public function requiredTools(): array
    {
        return [
            ToolEnum::airGun,
            ToolEnum::balancingMachine,
        ];
    }

    public function requiredCompetences(): array
    {
        return [CompetenceEnum::wheelBalancing];
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

        $balancingMachine = $this->employ->selectTool(ToolEnum::balancingMachine);
        assert($balancingMachine instanceof BalancingMachine);

        for ($i = 0; $i < 5; $i++) {
            $this->doAction($airGun->spinLeft());
        }

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