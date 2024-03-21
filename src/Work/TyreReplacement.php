<?php

declare(strict_types=1);

namespace Station\Work;

use Chetkov\Money\Money;
use Station\Employ\EmployInterface;
use Station\Tool\AirGun;
use Station\Tool\TireChangingMachine;
use Station\Tool\ToolEnum;


final class TyreReplacement extends AbstractWork
{
    public static function name(): string
    {
        return 'Замена резины'; //CompetenceEnum::tyreReplacement;
    }

    public function execute(EmployInterface $employ): void
    {
        $this->replaceWheel($employ);
        $this->replaceWheel($employ);
        $this->replaceWheel($employ);
        $this->replaceWheel($employ);
    }

    private function replaceWheel(EmployInterface $employ): void
    {
        $airGun = $employ->selectTool(ToolEnum::airGun);
        assert($airGun instanceof AirGun);

        $tireChangingMachine = $employ->selectTool(ToolEnum::tireChangingMachine);
        assert($tireChangingMachine instanceof TireChangingMachine);

        $airGun->spinLeft();
        $airGun->spinLeft();
        $airGun->spinLeft();
        $airGun->spinLeft();
        $airGun->spinLeft();

        //$this->amount = Money::RUB(300*4);
        $this->logger->log('Относим колесо к станку');
        $this->time->wait(minute: 1);


        $tireChangingMachine->putOff();

        $tireChangingMachine->putOn();

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
            ToolEnum::tireChangingMachine,
        ];
    }
    public function requiredCompetences(): array
    {
     return [CompetenceEnum::tyreReplacement];
    }
}
