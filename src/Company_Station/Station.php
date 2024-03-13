<?php

namespace Station\Company_Station;

use Station\Employ\GraphWork;
use Station\Inventory\Inventory;

class Station
{
    private string $id;

    public function __construct(
        private string    $name,
        private string    $address,
        private GraphWork $graphWork,
        private Inventory $inventory,
        //private WorkInterface   $work, вместе с сотрудником
        //private EmployInterface $employ,
        // клиенты могут являться потенциальным свойством (по моему - да)? какие еще свойтва станции?
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

}