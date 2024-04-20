<?php

namespace Station\Domain\Work\Amount;


class CalculateByBody //implements AmountWork
{
    public function __construct(private array $priceForBody)
    {

    }
   /*public function calculate(Car $car): Money
    {

    }*/
}