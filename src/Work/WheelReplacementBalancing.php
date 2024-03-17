<?php

namespace Station\Work;

use Station\Employ\EmployInterface;
use Station\Tool\AirGun;
use Station\Tool\BalancingMachine;
use Station\Tool\ToolEnum;
use Station\Tool\TireChangingMachine;


final class WheelReplacementBalancing extends AbstractWork
{
    public static function name(): WorkEnumRequired
    {
        return WorkEnumRequired::wheelReplacementBalancing;
    }

    public function execute(EmployInterface $employ): void
    {
        $this->balanceWheel($employ);
        $this->balanceWheel($employ);
        $this->balanceWheel($employ);
        $this->balanceWheel($employ);
    }


    private function balanceWheel(EmployInterface $employ): void
    {
        $airGun = $employ->selectTool(ToolEnum::airGun);
        assert($airGun instanceof AirGun);

        $tireChangingMachine = $employ->selectTool(ToolEnum::tireChangingMachine);
        assert($tireChangingMachine instanceof TireChangingMachine);

        $balancingMachine = $employ->selectTool(ToolEnum::balancingMachine);
        assert($balancingMachine instanceof BalancingMachine);

        $airGun->spinLeft();
        $airGun->spinLeft();
        $airGun->spinLeft();
        $airGun->spinLeft();
        $airGun->spinLeft();

        $this->logger->log('Относим колесо к станку');
        $this->time->wait(minute: 1);

        $tireChangingMachine->putOff();

        $tireChangingMachine->putOn();

        $this->logger->log('Относим колесо к балансировочному станку');
        $this->time->wait(minute: 1);

        $balancingMachine->setUpBalance();

        $balancingMachine->pulOffBalance();

        $this->logger->log('Относим колесо к автомобилю');
        $this->time->wait(minute: 1);

        $airGun->spinRight();
        $airGun->spinRight();
        $airGun->spinRight();
        $airGun->spinRight();
        $airGun->spinRight();

    }

    public function requiredTools(): array
    {
        return [
            ToolEnum::airGun,
            ToolEnum::balancingMachine,
            ToolEnum::tireChangingMachine,
        ];
    }
}