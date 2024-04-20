<?php

declare(strict_types=1);

namespace Station\Tool;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\InheritanceType;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use Station\Infrastructure\GeneratorID;
use Station\Employ\EmployInterface;
use Station\Logger\LoggerInterface;
use Station\PilotStation\Station;
use Station\Time\VirtualTime;

#[Entity(repositoryClass: ToolRepository::class)]
#[Table(name: 'tools')]
#[InheritanceType('SINGLE_TABLE')]
#[DiscriminatorColumn(name: 'name', type: 'string')]
#[DiscriminatorMap([
    ToolEnum::airGun->value => AirGun::class,
    ToolEnum::balancingMachine->value => BalancingMachine::class,
    ToolEnum::tireChangingMachine->value => TyreChangingMachine::class,
    ToolEnum::compressor->value => Compressor::class,
],)]
abstract class AbstractTool implements ToolInterface
{
    #[Id]
    #[GeneratedValue(strategy: 'SEQUENCE')]
    #[Column(name: 'id', type: 'integer', nullable: false)]
    private readonly string $id;

    public function __construct(
        protected VirtualTime $time,
        protected LoggerInterface $logger,
        #[ManyToOne(targetEntity: Station::class, inversedBy: 'tools')]
        private Station $station,
    )
    {
        $this->id = GeneratorID::genID();
    }

    private bool $isBusy = false;

    public function isBusy(): bool
    {
        return $this->isBusy;
    }

    public function take(EmployInterface $employee): void
    {
        $this->logger->log($employee->getName() . ' принял в работу ' . $this::name()->value);
        $this->isBusy = true;
    }

    public function put(EmployInterface $employee): void
    {
        $this->logger->log($employee->getName() . ' освободил ' . $this::name()->value);
        $this->isBusy = false;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function assignToStation(Station $station): void
    {
        $this->station = $station;
    }
}
