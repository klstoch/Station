<?php

declare(strict_types=1);

namespace Station\Domain\Tool;

use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\InheritanceType;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use Station\Domain\AbstractEntity;
use Station\Domain\Event\EventDispatcherInterface;
use Station\Domain\Station\Employ\EmployInterface;
use Station\Domain\Station\Station;
use Station\Domain\Tool\Event\ToolIsFreeAgainEvent;
use Station\Domain\Tool\Event\ToolWasBusyEvent;

#[Entity(repositoryClass: ToolRepositoryInterface::class)]
#[Table(name: 'tools')]
#[InheritanceType('SINGLE_TABLE')]
#[DiscriminatorColumn(name: 'name', type: 'string')]
#[DiscriminatorMap([
    ToolEnum::airGun->value => AirGun::class,
    ToolEnum::balancingMachine->value => BalancingMachine::class,
    ToolEnum::tireChangingMachine->value => TyreChangingMachine::class,
    ToolEnum::compressor->value => Compressor::class,
])]
abstract class AbstractTool extends AbstractEntity implements ToolInterface
{
    private bool $isBusy = false;

    public function __construct(
        #[ManyToOne(targetEntity: Station::class)]
        #[JoinColumn(name: 'station_id', referencedColumnName: 'id')]
        private Station $station,

        ?string $id,
    ) {
        parent::__construct($id);
    }

    public function isBusy(): bool
    {
        return $this->isBusy;
    }

    public function take(EmployInterface $employee, EventDispatcherInterface $eventDispatcher): void
    {
        $eventDispatcher->dispatch(new ToolWasBusyEvent($this, $employee));
        $this->isBusy = true;
    }

    public function put(EmployInterface $employee, EventDispatcherInterface $eventDispatcher): void
    {
        $eventDispatcher->dispatch(new ToolIsFreeAgainEvent($this, $employee));
        $this->isBusy = false;
    }

    public function assignToStation(Station $station): void
    {
        $this->station = $station;
    }
}
