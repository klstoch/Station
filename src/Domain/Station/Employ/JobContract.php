<?php

namespace Station\Domain\Station\Employ;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use Station\Domain\AbstractEntity;
use Station\Domain\Station\Employ\Schedule\ScheduleInterface;

#[Entity]
#[Table(name: 'job_contracts')]
final class JobContract extends AbstractEntity
{
    public function __construct(
        #[Column(name: 'schedule', type: 'schedule')]
        private readonly ScheduleInterface $schedule,

        #[Column(name: 'salary_rate', type: 'decimal', precision: 8, scale: 2)]
        private readonly float $salaryRate,

        #[Column(name: 'interest_rate', type: 'integer')]
        private readonly float $interestRate,

        #[Column(name: 'is_active', type: 'boolean')]
        private readonly bool $isActive,

        #[ManyToOne(targetEntity: AbstractEmploy::class, inversedBy: 'jobContracts')]
        #[JoinColumn(name: 'employ_id', referencedColumnName: 'id', nullable: false)]
        private readonly AbstractEmploy $employ,

        ?string $id = null,
    ) {
        parent::__construct($id);
    }

    public function getEmploy(): AbstractEmploy
    {
        return $this->employ;
    }

    public function getSchedule(): ScheduleInterface
    {
        return $this->schedule;
    }

    public function getInterestRate(): int
    {
        return $this->interestRate;
    }

    public function getSalaryRate(): float
    {
        return $this->salaryRate;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }
}