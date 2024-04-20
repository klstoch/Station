<?php

namespace Station\Domain\Work\Amount;

use Chetkov\Money\Money;
use Station\Domain\Client\Vehicle\Car;

interface AmountWorkInterface
{
    public function calculate(Car $car): Money;

}