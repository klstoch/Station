<?php

namespace Station\Work;

use Chetkov\Money\Money;
use Station\Logger\LoggerWithTiming;
use Station\Time\VirtualTime;

abstract class AbstractWork implements WorkInterface
{
    public function __construct(
        protected VirtualTime $time,
        protected LoggerWithTiming $logger,
        //protected Money $amount
    ) {

    }

    /**
     * @return array<string>
     */
    public static function getAll(): array
    {
        return [
            TyreReplacement::name(),
            WheelBalancing::name(),
            WheelReplacementBalancing::name(),
        ]; // здесь должны быть все виды работ, включая их сложение
    }
}