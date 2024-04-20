<?php

namespace Station\Domain\Station;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;
use Station\Domain\AbstractEntity;
use Station\Domain\Client\Client;
use Station\Domain\Station\ClientBase\ClientBaseFactoryInterface;
use Station\Domain\Station\ClientBase\ClientBaseInterface;
use Station\Domain\Station\ClientQueue\ClientQueueFactoryInterface;
use Station\Domain\Station\ClientQueue\ClientQueueInterface;
use Station\Domain\Station\Employ\AbstractEmploy;
use Station\Domain\Station\Employ\EmployInterface;
use Station\Domain\Station\Employ\Schedule\ScheduleInterface;
use Station\Domain\Station\Inventory\InventoryInterface;
use Station\Domain\Station\Inventory\InventoryFactoryInterface;

use Station\Domain\Time\VirtualTime;

#[Entity(repositoryClass: StationRepositoryInterface::class)]
#[Table(name: 'stations')]
class Station extends AbstractEntity
{
    public function __construct(
        #[Column(name: 'name', length: 150)]
        private readonly string $name,

        #[Column(name: 'address', length: 250)]
        private readonly string $address,

        #[Column(name: 'schedule', type: 'schedule')]
        private readonly ScheduleInterface $schedule,

        #[Column(name: 'time', type: 'virtual_time')]
        private readonly VirtualTime $time,

        /** @param array<AbstractEmploy> $employees */
        #[OneToMany(targetEntity: AbstractEmploy::class, mappedBy: 'station')]
        private array $employees = [],

        /** @param array<Client> $clients */
        #[ManyToMany(targetEntity: Client::class, mappedBy: 'stations')]
        private readonly array $clients = [],

        ?string $id = null,
    ) {
        parent::__construct($id);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getSchedule(): ScheduleInterface
    {
        return $this->schedule;
    }

    public function getTime(): VirtualTime
    {
        return $this->time;
    }

    /**
     * @return array<EmployInterface>
     */
    public function getEmployees(): array
    {
        return $this->employees;
    }

    public function addEmploy(EmployInterface $employ): void
    {
        if (!in_array($employ, $this->employees, true)) {
            $this->employees[] = $employ;
        }
    }

    public function getInventory(InventoryFactoryInterface $factory): InventoryInterface
    {
        return $factory->create($this);
    }

    public function getClientQueue(ClientQueueFactoryInterface $factory): ClientQueueInterface
    {
        return $factory->create($this);
    }

    public function getClientBase(ClientBaseFactoryInterface $factory): ClientBaseInterface
    {
        return $factory->create($this);
    }
}