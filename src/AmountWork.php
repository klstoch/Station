<?php

namespace Station;

use Chetkov\Money\Money;
use Station\Vehicle\Car;

interface AmountWork
{
    public function calculate(Car $car): Money;

}