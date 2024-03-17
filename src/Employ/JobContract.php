<?php

namespace Station\Employ;

class JobContract
{
    public function __construct(
        private readonly GraphWork $graphWork,
        private float $salaryRate,
        private float $interestRate,
    )
    {

    }

    /**
     * @return GraphWork
     */
    public function getGraphWork(): GraphWork
    {
        return $this->graphWork;
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
    public function setInterestRate(float $interestRate): void
    {
        $this->interestRate = $interestRate;
    }

    /**
     * @param float $salaryRate
     */
    public function setSalaryRate(float $salaryRate): void
    {
        $this->salaryRate = $salaryRate;
    }
}