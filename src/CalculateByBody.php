<?php

namespace Station;

use Chetkov\Money\Money;
use Station\Vehicle\Car;


class CalculateByBody implements AmountWork
{
    public function __construct(private array $priceForBody)
    {

    }
    public function calculate(Car $car): Money
    {

    }
}