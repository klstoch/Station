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
}