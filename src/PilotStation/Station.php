<?php

namespace Station\PilotStation;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;
use Station\BaseClient\ClientBase;
use Station\Client\Client;
use Station\Employ\AbstractEmploy;
use Station\Employ\EmployInterface;
use Station\Employ\Graph\GraphWork;
use Station\Infrastructure\GeneratorID;
use Station\Inventory\Inventory;
use Station\Inventory\InventoryFactoryInterface;
use Station\Queue\ClientQueue;
use Station\Queue\ClientQueueFactoryInterface;
use Station\Time\VirtualTime;

#[Entity]
#[Table(name: 'stations')]
class Station
{
    #[Id]
    #[GeneratedValue(strategy: 'SEQUENCE')]
    #[Column(name: 'id', type: 'integer', nullable: false)]
    private string $id;

    public function __construct(
        #[Column(name: 'station_name', length: 150, nullable: false)]
        private readonly string $name,
        #[Column(name: 'station_address', length: 250, nullable: false)]
        private readonly string $address,
        #[Column(name: 'graph_work', type: 'graph_work', nullable: false)]
        private readonly GraphWork $graphWork,
        //private readonly ClientBase $clientBase,
        #[Column(name: 'time', type: 'virtual_time',)]
        private readonly VirtualTime $time,
        #[OneToMany(targetEntity: Client::class, mappedBy: 'stations')]
        private readonly array $clients = [],
        #[OneToMany(targetEntity: AbstractEmploy::class, mappedBy: 'stations')]
        private readonly array $employees = [],
    ) {
        $this->id = GeneratorID::genID();
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @return GraphWork
     */
    public function getGraphWork(): GraphWork
    {
        return $this->graphWork;
    }

    /**
     * @return VirtualTime
     */
    public function getTime(): VirtualTime
    {
        return $this->time;
    }


    public function getInventory(InventoryFactoryInterface $factory): Inventory
    {
        return $factory->create($this);
    }

    /**
     * @return ClientBase
     */
    /*public function getClientBase(): ClientBase
    {
        return $this->clientBase;
    }*/

    /**
     * @return array<EmployInterface>
     */
    public function getEmployees(): array
    {
        return $this->employees;
    }

    public function getClientQueue(ClientQueueFactoryInterface $factory): ClientQueue
    {
        return $factory->create($this);
    }

    public function add(Client $client): void
    {
        $this->clientBaseRepository->save($client);
    }

    public function get(): Client|null
    {

        $clientBase = $this->clientBaseRepository->getAll();
        return $clientBase[random_int(0, count($clientBase) - 1)];

    }
}