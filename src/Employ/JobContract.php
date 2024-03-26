<?php

namespace Station\Employ;

use Station\Employ\Graph\GraphWork;

class JobContract
{
    public function __construct(
        //private readonly GraphWork $graphWork,
        private float $salaryRate,
        private float $interestRate,
    )
    {

    }

    /**
     * @return int
     */
    public function getInterestRate(): int
    {
        return $this->interestRate;
    }

    /**
     * @return float
     */
    public function getSalaryRate(): float
    {
        return $this->salaryRate;
    }

    /**
     * @param float $interestRate
     */
    public function updateInterestRate(float $interestRate): void
    {
        $this->interestRate = $interestRate;
    }

    /**
     * @param float $salaryRate
     */
    public function updateSalaryRate(float $salaryRate): void
    {
        $this->salaryRate = $salaryRate;
    }
}