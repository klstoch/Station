<?php

namespace Station;

use Chetkov\Money\Money;
use Station\Vehicle\BodyEnum;
use Station\Vehicle\Car;
use Station\Vehicle\DamageEnum;
use Station\Vehicle\DiscMaterialEnum;

class CalculateByWheel implements AmountWork
{
    public function __construct(
        private array $priceRadiusTyre,
        private array $priceRepairDamage,
        private array $priceForBody,
        private float $coefficientForRunflat,
        private float $coefficientForDiscMaterial,
    ) {
        if ($this->coefficientForRunflat < 1) {
            throw new \RuntimeException();
        }

        if ($this->coefficientForDiscMaterial < 1) {
            throw new \RuntimeException();
        }
    }

    public function calculate(Car $car): Money
    {
        $priceRepair = 0;
        if ($car->getWheel()->getDiscWheel()->getDamage() !== DamageEnum::stopOut) {
            $priceRepair = $this->priceRepairDamage[$car->getWheel()->getDiscWheel()->getDamage()->value];
            if ($car->getWheel()->getDiscWheel()->getDiscMaterial() !== DiscMaterialEnum::stamping){
                $priceRepair *= $this->coefficientForDiscMaterial;
            }
        }


        $priceReplacementTyre = $this->priceRadiusTyre[$car->getWheel()->getTyre()->getRadius()->value];
        if ($car->getWheel()->getTyre()->isRun_flat()) {
            $priceReplacementTyre *= $this->coefficientForRunflat ;
        }

        $priceBody = 1;
        if ($car->getBody() !== BodyEnum::sedan){
            $priceBody = $this->priceForBody[$car->getBody()->value];
        }
        $amount = $priceReplacementTyre * $priceBody + $priceRepair;

        return Money::RUB($amount);
    }
}