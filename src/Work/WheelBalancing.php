<?php

namespace Station\Work;

use Station\Employ\EmployInterface;
use Station\Tool\AirGun;
use Station\Tool\TireChangingMachine;
use Station\Tool\ToolEnum;
use Station\Tool\BalancingMachine;

final class WheelBalancing extends AbstractWork
{
    public static function name(): WorkEnum
    {
        return WorkEnum::wheelBalancing;
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

        $balancingMachine = $employ->selectTool(ToolEnum::balancingMachine);
        assert($balancingMachine instanceof BalancingMachine);

        $airGun->spinLeft();
        $airGun->spinLeft();
        $airGun->spinLeft();
        $airGun->spinLeft();
        $airGun->spinLeft();

        /*echo 'Относим колесо к станку' . PHP_EOL;
        $this->time->wait(minute: 1);

        $tireChangingMachine->putOff();

        $tireChangingMachine->putOn();*/

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
        ];
    }
}