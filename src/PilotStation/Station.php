<?php

namespace Station\PilotStation;

use Station\BaseClient\ClientBase;
use Station\Employ\EmployeeRepository;
use Station\Employ\Graph\GraphWork;
use Station\Infrastructure\GeneratorID;
use Station\Inventory\Inventory;
use Station\Queue\ClientQueue;
use Station\Time\VirtualTime;

class Station
{
    //private array $employees = [];
    //private array $clients = [];
    private string $id;

    public function __construct(
        private readonly string $name,
        private readonly string $address,
        private readonly GraphWork $graphWork,
        private readonly Inventory $inventory,
        private readonly ClientQueue $clientQueue,
        private readonly VirtualTime $time,
        private readonly ClientBase $clientBase,
        private readonly EmployeeRepository $employeeRepository,
    )
    {
        $this->id = GeneratorID::genID();
    }

    /**
     * @return GraphWork
     */
    public function getGraphWork(): GraphWork
    {
        return $this->graphWork;
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
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return Inventory
     */
    public function getInventory(): Inventory
    {
        return $this->inventory;
    }

    /**
     * @return ClientQueue
     */
    public function getClientQueue(): ClientQueue
    {
        return $this->clientQueue;
    }

    /**
     * @return VirtualTime
     */
    public function getTime(): VirtualTime
    {
        return $this->time;
    }

    /**
     * @return ClientBase
     */
    public function getClientBase(): ClientBase
    {
        return $this->clientBase;
    }

    /**
     * @return EmployeeRepository
     */
    public function getEmployeeRepository(): EmployeeRepository
    {
        return $this->employeeRepository;
    }


}